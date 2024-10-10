<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Cases;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        
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
        return view('admin.dashboard', compact('statusCount', 'payment', 'chart', 'agentList'));

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
