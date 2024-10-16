<?php

namespace App\Http\Controllers;

use App\Imports\BillsImport;
use App\Models\Bill;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BillController extends Controller
{
    public function index(Request $request){
        $bills = DB::table('bills')->get();
        return view('admin.bills.index', compact('bills'));
    }

    public function reports(Request $request){

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-30');

        if(filled($request->startDate)){
            $startDate = $request->startDate;
        }else{
            $request->merge(['startDate'=> $startDate]);
        }        
        if(filled($request->endDate)){
            $endDate = $request->endDate;
        }else{
            $request->merge(['endDate'=> $endDate]);
        }

        $client = (filled($request->agent) && !empty($request->agent)) ? $request->agent : '';

        $statusList = Bill::whereBetween('inscription_date',[$startDate,$endDate]);
        if($client != '') {
            $statusList = $statusList->where('userfield_agent', $client);
        }
        $paymentInfo = $billInfo = $contractInfo = $chartInfo = $statusList;

        $statusList = $statusList->get()->groupBy('status');
        $paymentInfo = $paymentInfo->get()->groupBy('payment_type');
        $billInfo = $billInfo->get()->groupBy('bill');
        $contractInfo = $contractInfo->get()->groupBy('contract_type');

        $statusCount = ['Contrat effectif' => 0, 'Contrat non effectif' => 0];
        $payment = ['effectif' => 0, 'non effectif' => 0];
        foreach ($statusList as $key => $value) {
            switch ($key) {
                case 'Contrat effectif':
                    $statusCount['Contrat effectif'] = count($value);
                    $payment['effectif'] += $value->sum('commission');
                    break;
                default:
                    $statusCount['Contrat non effectif'] += count($value);
                    $payment['non effectif'] += $value->sum('commission');
                    break;
            }
        }
        $paymentChart = $bChart = $contractChart = [];
        foreach ($paymentInfo as $pk => $pv) {
            $paymentChart[$pk] = count($pv);
        }
        foreach ($billInfo as $bk => $bv) {
            $bChart[$bk] = count($bv);
        }
        foreach ($contractInfo as $ck => $cv) {
            $contractChart[$ck] = count($cv);
        }
        
        $statusChart = $billChart = [];
        
        $chartInfo = $chartInfo->orderBy('inscription_date')->get();
        foreach ($chartInfo as $bill) {
            $index = date('d M', strtotime($bill->inscription_date));
            if(!in_array($index, array_keys($statusChart))) {
                $statusChart[$index] = ['label' => $index, 'effectif' => 0, 'non effectif' => 0];
                $billChart[$index] = ['label' => $index, 'paid' => 0, 'unpaid' => 0];
            }
            switch ($bill->status) {
                case 'Contrat effectif':
                    $statusChart[$index]['effectif'] += $bill->commission;
                    $billChart[$index]['paid'] += 1;
                    break;
                default:
                    $statusChart[$index]['non effectif'] += $bill->commission;
                    $billChart[$index]['unpaid'] += 1;
                    break;
            }
        }
        
        $chart['labels'] = array_column($statusChart, 'label');
        $chart['paid'] = array_column($statusChart, 'effectif');
        $chart['unpaid'] = array_column($statusChart, 'non effectif');

        $chart['paidBills'] = array_column($billChart, 'paid');
        $chart['unpaidBills'] = array_column($billChart, 'unpaid');

        $chart['payment_lbl'] = array_keys($paymentChart);
        $chart['payment_val'] = array_values($paymentChart);
        
        $chart['bill_lbl'] = array_keys($bChart);
        $chart['bill_val'] = array_values($bChart);
        
        $chart['cont_lbl'] = array_keys($contractChart);
        $chart['cont_val'] = array_values($contractChart);

        $agentList = Bill::select('userfield_agent')->distinct()->get()->pluck('userfield_agent')->toArray();

        return view('admin.bills.reports', compact('statusCount', 'payment', 'chart', 'agentList'));
    }
    public function import(Request $request)
    {
        if ($request->hasFile('file')) {

            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls',
            ]);

            $file = $request->file('file');
            $data = Excel::toArray(new BillsImport, $file);
            $header = $records = [];
            $mapping = [
                'bill_id' => 'EAN',
                'userfield_agent' => 'Userfield_Agent',
                'agent' => 'Agent',
                'status' => 'Statut',
                'payment_type' => 'Type de paiement',
                'bill' => 'factures',
                'b2c_b2b' => 'individual',
                'inscription_date' => 'DateInscription',
                'consumption' => 'Consommation',
                'contract_type' => 'contract_type',
                'product_type' => 'Type de paiement',

            ];
            if(isset($data[0]) && isset($data[0][1])){
                // unset($data[0][0]);
                $header = $data[0][0];
                $importData = [];
                foreach($data[0] as $key => $value){
                    if($key > 0){
                        $record = array_combine($header, $value);
                        $row = [];
                        if($record['EAN'] == null){
                            continue;
                        }
                        foreach($mapping as $k => $v){
                            if(isset($record[$v])){
                                if ($v == 'DateInscription'){
                                    $UNIX_DATE = ($record[$v] - 25569) * 86400;
                                    $row[$k] = gmdate("Y-m-d H:i:s", $UNIX_DATE);
                                }else{
                                    $row[$k] = trim($record[$v], "'");
                                }
                            }else{
                                if($v == "Agent"){
                                    $row[$k] = 'Empty Agent';
                                }else if($v == 'Consommation'){
                                    $row[$k] = 0;
                                }
                            }
                        }
                        // if(count($row) != 11){
                        //     dd($row);
                        // }
                        $row['commission'] = 0;
                        if(isset($row['consumption'])){
                            // Commmission calculation
                            switch ($row['b2c_b2b']) {
                                case 'Residentiel':
                                    $row['commission'] +=  55;
                                    break;
                                case 'Commercial':
                                    $row['commission'] +=  85;
                                    break;
                            }
                            // Payment type calculation
                            if($row['payment_type'] == 'Domiciliation'){
                                $row['commission'] += 5;
                            }
                            // Bill type calculation
                            if($row['bill'] == 'E-mail'){
                                $row['commission'] += 5;
                            }
                        }
                        $row['created_at'] = date('Y-m-d H:i:s');
                        $row['updated_at'] = date('Y-m-d H:i:s');
                        $row['id'] = $row['bill_id'];
                        $importData[] = $row;
                        if(count($importData) == 100){
                            Bill::upsert($importData,['id'], array_keys($mapping));
                            $importData = [];
                        }
                    }
                }
                Bill::upsert($importData,['id'], array_keys($mapping));
            }
            return back()->with('success', 'Data Imported successfully.');
        }
        return view('admin.bills.import');
    }
}
