<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Cases;
use App\Models\Telco;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(12)->toDateString();
        $dateE = Carbon::now()->startOfMonth()->addMonth(1)->toDateString();

        $agent = (filled($request->agent) && !empty($request->agent)) ? $request->agent : '';
        $supervisor = (filled($request->supervisor) && !empty($request->supervisor)) ? $request->supervisor : '';

        $bills = Bill::select('id', 'inscription_date', 'commission', 'status')->whereBetween('inscription_date',[$dateS,$dateE]);

        if($agent != ''){
            $bills = $bills->where('userfield_agent', $agent);
        }
        
        $bills = $bills->get()->groupBy(function ($date) {
            return Carbon::parse($date->inscription_date)->format('m');
        });

        $telco = Telco::select('id', 'registration_date', 'commission', 'status')->whereBetween('registration_date',[$dateS,$dateE]);
        
        if($supervisor != ''){
            $telco = $telco->where('supervisor_firstname', $supervisor);
        }
        $telco = $telco->get()->groupBy(function ($date) {
            return Carbon::parse($date->registration_date)->format('m');
        });

        $chart = [];
        foreach ($bills as $key => $value) {
            // $filtered = ($value->pluck('commission', 'status'));
            $filtered = $value->filter(function($item){
                return $item->status == 'Contrat effectif';
            });

            $comm = array_sum($filtered->pluck('commission')->toArray());
            
            $chart[$key] = ['bills' => count($value), 'telco' => 0, 'com_b' => $comm, 'com_t' => 0];
        }
        foreach ($telco as $key => $value) {
            // $comm = array_sum($value->pluck('commission')->toArray());
            $filtered = $value->filter(function($item){
                return $item->status == 'ACTIVATED';
            });

            $comm = array_sum($filtered->pluck('commission')->toArray());
            
            if(in_array($key, array_keys($chart))){
                $chart[$key] = ['bills' => $chart[$key]['bills'], 'telco' => count($value), 'com_b' => $chart[$key]['com_b'], 'com_t' => $comm];
            }else{
                $chart[$key] = ['bills' => 0, 'telco' => count($value), 'com_b' => 0, 'com_t' => $comm];
            }
        }
        ksort($chart);
        $month['labels'] = array_keys($chart);
        if(is_array($month['labels']) && count($month['labels'])>0){
            $month['labels'] = array_values(array_reduce($month['labels'],function($rslt,$m){ $rslt[$m] = date('F',mktime(0,0,0,$m,10)); return $rslt; }));
        }
        
        $month['energy'] = array_column($chart, 'bills');
        $month['telco'] = array_column($chart, 'telco');
        $month['com_b'] = array_column($chart, 'com_b');
        $month['com_t'] = array_column($chart, 'com_t');

        // Total Count Current Month
        $currMonth = date('m');
        $energyChart = ['Contrat effectif' => 0, 'Contrat en attente' => 0];
        $bills = Bill::select('status')->whereRaw('MONTH(inscription_date) = ?' ,[$currMonth]) ->whereRaw('YEAR(inscription_date) = ?' ,[date('Y')])->get()->groupBy('status');
        if(count($bills)>0){
            foreach($bills as $key => $bill){
                switch ($key) {
                    case 'Contrat effectif':
                        $energyChart['Contrat effectif'] = count($bill);
                        break;
                    default:
                        $energyChart['Contrat en attente'] += count($bill);
                        break;
                }
            }
        }

        $month['bill_pie']['label'] = array_keys($energyChart);
        $month['bill_pie']['values'] =  array_values($energyChart);

        $telcoChart = [];
        $telco = Telco::select('status')->whereRaw('MONTH(registration_date) = ?' ,[$currMonth]) ->whereRaw('YEAR(registration_date) = ?' ,[date('Y')])->get()->groupBy('status');

        $month['telso_pie'] = ['label' => ['Paid', 'Un-Paid'], 'values' => ['0'=>0, '1'=>0]];
        foreach ($telco as $telKey => $val) {
            if($telKey == 'ACTIVATED'){
                $month['telso_pie']['values'][0] += count($val); 
            }else{
                $month['telso_pie']['values'][1] += count($val);
            }
            
            // $telcoChart[$telKey] = count($val);
        }

        // if(count($telcoChart)>0){
        //     $month['telso_pie'] = ['label' => array_keys($telcoChart), 'values' => array_values($telcoChart)];
        // }
        // dd($month);

        $agentList = Bill::select('userfield_agent')->distinct()->get()->pluck('userfield_agent')->toArray();
        $supervisorList = Telco::select('supervisor_firstname')->distinct()->get()->pluck('supervisor_firstname')->toArray();
        
        return view('admin.dashboard', compact('month', 'agentList', 'supervisorList'));

        if(Auth::user()->user_type != 3) {

            $vendorID = null; // Initialize the variable

            if(Auth::user()->user_type ==2) {
                $vendorID = Auth::user()->id;
            }

            // Get from helper to make cases status dynamic
            $statusMappings = getLeadStatus(null, null);
            $caseStatements = [];
            foreach ($statusMappings as $status => $label) {
                $caseStatements[] = "WHEN status = {$status} THEN '{$label}'";
            }
            $caseSql = implode(" ", $caseStatements);

            $caseStatusCounts = DB::table('leads')
            ->select(
                'status',
                DB::raw('count(*) as count'),
                DB::raw("CASE {$caseSql} ELSE 'Unknown' END as label")
            )
            ->when(isset($vendorID), function ($query) use ($vendorID) {
                return $query->where('id', $vendorID);
            })
            ->groupBy('status')->get();



            $caseStatusCounts2 = DB::table('leads')
            ->select(
                DB::raw('DATE_FORMAT(dated, "%Y-%m-%d") as date'),
                DB::raw('DATE_FORMAT(dated, "%Y") as year'),
                DB::raw('DATE_FORMAT(dated, "%m") as month'),
                DB::raw('count(*) as total_cases'),
                DB::raw('SUM(CASE WHEN status = 7 THEN 1 ELSE 0 END) as resolved_cases')
            )
            ->when(isset($vendorID), function ($query) use ($vendorID) {
                return $query->where('id', $vendorID);
            })
            ->groupBy('year', 'month', 'date')->get();


            $caseStatusCounts3 = DB::table('cases')
            ->select(
                DB::raw('DATE_FORMAT(start_datetime, "%Y-%m-%d") as date'),
                DB::raw('DATE_FORMAT(start_datetime, "%Y") as year'),
                DB::raw('DATE_FORMAT(start_datetime, "%m") as month'),
                'status',
                DB::raw('count(*) as count'),
                DB::raw("CASE
                    WHEN status = 1 THEN 'Process Start'
                    WHEN status = 2 THEN 'Under observation'
                    WHEN status = 3 THEN 'Negotiating'
                    WHEN status = 4 THEN 'Waiting for customer response'
                    WHEN status = 6 THEN 'Waiting for 3rd party response'
                    WHEN status = 7 THEN 'Suspended'
                    WHEN status = 8 THEN 'Withdrawed'
                    WHEN status = 9 THEN 'Resolved'
                    ELSE 'Unknown'
                END as label"),
            )
            ->when(isset($vendorID), function ($query) use ($vendorID) {
                return $query->where('employee_id', $vendorID);
            })
            ->groupBy('year', 'month', 'date', 'status')
            ->get();

            $casesAmounts = Cases::selectRaw('DATE(start_datetime) as date')
            ->selectRaw('SUM(total_amount) as total_amount')
            ->selectRaw('SUM(commission_amount) as commission_amount')
            ->selectRaw('SUM(total_amount - commission_amount) as profit_amount')
            ->when(isset($vendorID), function ($query) use ($vendorID) {
                return $query->where('employee_id', $vendorID);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

            return view('admin.dashboard', compact('caseStatusCounts', 'caseStatusCounts2', 'caseStatusCounts3', 'casesAmounts'));

        } else {
            return view('admin.dashboard');
        }
    }
}
