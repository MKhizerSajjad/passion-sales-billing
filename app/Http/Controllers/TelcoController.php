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
        
        $dateFilter = (filled($request->days) && !empty($request->days)) ? $request->days : 30;
        $dateRange = Carbon::now()->subDays($dateFilter);
        $client = (filled($request->supervisor) && !empty($request->supervisor)) ? $request->supervisor : '';

        $statusList = Telco::whereDate( 'registration_date', '>=', $dateRange);
        if($client != '') {
            $statusList = $statusList->where('supervisor_firstname', $client);
        }

        $statusList = $statusList->get()->groupBy('status');

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
        
        $statusChart = $billChart = [];
        $chartInfo = Telco::whereDate( 'registration_date', '>=', $dateRange);
        if($client != '') {
            $chartInfo = $chartInfo->where('supervisor_firstname', $client);
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
                        if(count($row) > 0 && isset($row['order_id'])){
                            // Setting update bill records
                            $telcoObj = DB::table('telco')->where('order_id', $row['order_id'])->first();
                            $row['updated_at'] = Carbon::now();
                            if($telcoObj){
                                DB::table('telco')->where('order_id', $row['order_id'])->update($row);
                            }else{
                                $row['created_at'] = Carbon::now();
                                DB::table('telco')->insert($row);
                            }
                        }
                    }
                }
            }
            return back()->with('success', 'Data Imported successfully.');
        }
        return view('admin.telco.import');
    }
}
