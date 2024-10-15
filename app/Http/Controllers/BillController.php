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
        $dateFilter = (filled($request->days) && !empty($request->days)) ? $request->days : 30;
        $dateRange = Carbon::now()->subDays($dateFilter);
        $client = (filled($request->agent) && !empty($request->agent)) ? $request->agent : '';

        $statusList = Bill::
                      whereDate( 'inscription_date', '>=', $dateRange);
        if($client != '') {
            $statusList = $statusList->where('userfield_agent', $client);
        }
        $statusList = $statusList->get()->groupBy('status');

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
        
        $statusChart = $billChart = [];
        $chartInfo = Bill::
                     whereDate( 'inscription_date', '>=', $dateRange);
        if($client != '') {
            $chartInfo = $chartInfo->where('userfield_agent', $client);
        }            
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
                'bill_id' => 'id',
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
                        foreach($mapping as $k => $v){
                            if(isset($record[$v])){
                                if ($v == 'DateInscription'){
                                    $UNIX_DATE = ($record[$v] - 25569) * 86400;
                                    $row[$k] = gmdate("Y-m-d H:i:s", $UNIX_DATE);
                                }else{
                                    $row[$k] = $record[$v];
                                }
                            }
                        }
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
                        if(count($row) > 0 && isset($row['bill_id'])){
                            // Setting update bill records
                            $billObj = DB::table('bills')->where('bill_id', $row['bill_id'])->first();
                            $row['updated_at'] = Carbon::now();
                            if($billObj){
                                DB::table('bills')->where('bill_id', $row['bill_id'])->update($row);
                            }else{
                                $row['created_at'] = Carbon::now();
                                DB::table('bills')->insert($row);
                            }
                        }
                        // $row['created_at'] = Carbon::now();
                        // $importData[] = $row;
                        // Bill::updateOrCreate($row,['bill_id'=>$row['bill_id']]);
                    }
                }
                // dd(count($importData));
            }
            return back()->with('success', 'Data Imported successfully.');
        }
        return view('admin.bills.import');
    }
}
