<?php

namespace App\Http\Controllers;

use App\Imports\BillsImport;
use App\Models\Bill;
use App\Models\Telco;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TelcoController extends Controller
{
    public function index(Request $request){
        $telco = DB::table('telco')->get();
        return view('admin.telco.index', compact('telco'));
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

        $statusList = Telco::whereBetween('registration_date',[$startDate,$endDate]);
        if($client != '') {
            $statusList = $statusList->where('supervisor_firstname', $client);
        }

        $sceInfo = $productInfo = $chartInfo = $statusList;

        $statusList = $statusList->get()->groupBy('status');
        $productInfo = $productInfo->get()->groupBy('base_product_name');
        $sceInfo = $sceInfo->get()->groupBy('scenario');
        
        $statusCount = ['Active' => 0, 'Other' => 0];
        $payment = ['Active' => 0, 'Other' => 0];
        foreach ($statusList as $key => $value) {
            switch ($key) {
                case 'ACTIVATED':
                    $statusCount['Active'] = count($value);
                    $payment['Active'] += $value->sum('commission');
                    break;
                default:
                    $statusCount['Other'] += count($value);
                    $payment['Other'] += $value->sum('commission');
                    break;
                // case 'INCOMPLETE':
                //     $statusCount['Other'] += count($value);
                //     $payment['Other'] += $value->sum('commission');
                //     break;
                // case 'IN_DELIVERY':
                //     $statusCount['Other'] += count($value);
                //     $payment['Other'] += $value->sum('commission');
                //     break;
            }
        }
        
        $scrChart = $statChart = $productChart = $statusChart = $billChart = [];
        foreach ($productInfo as $pk => $pv) {
            $productChart[$pk] = count($pv);
        }
        foreach ($statusList as $sk => $sv) {
            $statChart[$sk] = count($sv);
        }
        foreach ($sceInfo as $sck => $scv) {
            $scrChart[$sck] = count($scv);
        }

        
        $chartInfo = $chartInfo->orderBy('registration_date')->get();
        foreach ($chartInfo as $bill) {
            $index = date('d M', strtotime($bill->registration_date));
            if(!in_array($index, array_keys($statusChart))) {
                $statusChart[$index] = ['label' => $index, 'effectif' => 0, 'non effectif' => 0];
                $billChart[$index] = ['label' => $index, 'paid' => 0, 'unpaid' => 0];
            }
            switch ($bill->status) {
                case 'ACTIVATED':
                    $statusChart[$index]['effectif'] += $bill->commission;
                    $billChart[$index]['paid'] += 1;
                    break;
                default:
                    $statusChart[$index]['non effectif'] += $bill->commission;
                    $billChart[$index]['unpaid'] += 1;
                    break;
                // case 'INCOMPLETE':
                //     $statusChart[$index]['non effectif'] += $bill->commission;
                //     $billChart[$index]['unpaid'] += 1;
                //     break;
                // case 'IN_DELIVERY':
                //     $statusChart[$index]['non effectif'] += $bill->commission;
                //     $billChart[$index]['unpaid'] += 1;
                //     break;
            }
        }
        
        $chart['labels'] = array_column($statusChart, 'label');
        $chart['paid'] = array_column($statusChart, 'effectif');
        $chart['unpaid'] = array_column($statusChart, 'non effectif');

        $chart['paidBills'] = array_column($billChart, 'paid');
        $chart['unpaidBills'] = array_column($billChart, 'unpaid');

        $chart['prod_lbl'] = array_keys($productChart);
        $chart['prod_val'] = array_values($productChart);

        $chart['stat_lbl'] = array_keys($statChart);
        $chart['stat_val'] = array_values($statChart);

        $chart['src_lbl'] = array_keys($scrChart);
        $chart['src_val'] = array_values($scrChart);

        $agentList = Telco::select('supervisor_firstname')->distinct()->get()->pluck('supervisor_firstname')->toArray();

        return view('admin.telco.reports', compact('statusCount', 'payment', 'chart', 'agentList'));
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
                'contract_id' => 'contract_id',
                'payment_mode' => 'payment_mode',
                'contract_type' => 'customer_type',
                'order_id' => 'order_id',
                'status' => 'status',
                'registration_date' => 'registration_date',
                'activation_date' => 'activation_date',
                'scenario' => 'scenario',
                'base_product_name' => 'base_product_name',
                'supervisor_firstname' => 'supervisor_firstname',
            ];
            if(isset($data[0]) && isset($data[0][1])){
                unset($data[0][0]);
                $header = $data[0][1];
                foreach($data[0] as $key => $value){
                    if($key > 1){
                        $record = array_combine($header, $value);
                        $row = [];
                        foreach($mapping as $k => $v){
                            if(isset($record[$v])){
                                if($v == 'registration_date' || $v == 'activation_date'){
                                    $record[$v] = date('Y-m-d', strtotime(str_replace('/', '-', $record[$v])));
                                }
                                $row[$k] = $record[$v];
                            }else{
                                if($v = 'activation_date'){
                                    $row[$k] = Null;
                                }
                            }
                        }
                        $row['commission'] = 0;
                        if(isset($row['scenario'])){
                            // Commmission calculation
                            switch ($row['scenario']) {
                                case 'port_in':
                                    if(strpos($row['base_product_name'], 'Flash') !== false){
                                        $row['commission'] +=  45;
                                    }else if(strpos($row['base_product_name'], 'Star') !== false){
                                        $row['commission'] +=  70;
                                    }else if(strpos($row['base_product_name'], 'Spark') !== false){
                                        $row['commission'] +=  25;
                                    }else if(strpos($row['base_product_name'], 'Glow') !== false){
                                        $row['commission'] +=  25;
                                    }
                                    break;
                                case 'new':
                                    if(strpos($row['base_product_name'], 'Flash') !== false){
                                        $row['commission'] +=  25;
                                    }else if(strpos($row['base_product_name'], 'Star') !== false){
                                        $row['commission'] +=  35;
                                    }else if(strpos($row['base_product_name'], 'Spark') !== false){
                                        $row['commission'] +=  15;
                                    }else if(strpos($row['base_product_name'], 'Glow') !== false){
                                        $row['commission'] +=  15;
                                    }
                                    break;
                            }
                        }
                        $row['created_at'] = date('Y-m-d H:i:s');
                        $row['updated_at'] = date('Y-m-d H:i:s');
                        $row['id'] = str_replace("O-", "", $row['order_id']);
                        $importData[] = $row;
                        // if(count($row) != 14){
                        //     dd($row);
                        // }
                        if(count($importData) == 100){
                            Telco::upsert($importData,['id'], array_keys($mapping));
                            $importData = [];
                        }
                    }
                }
                Telco::upsert($importData,['id'], array_keys($mapping));
            }
            return back()->with('success', 'Data Imported successfully.');
        }
        return view('admin.telco.import');
    }
}
