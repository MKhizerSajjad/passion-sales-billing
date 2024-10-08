<?php

namespace App\Http\Controllers;

use App\Imports\BillsImport;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BillController extends Controller
{
    public function index(){
        $bills = DB::table('bills')->get();
        return view('admin.bills.index', compact('bills'));
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
                'bill_id' => 'ID contract',
                'userfield_agent' => 'Userfield_Agent',
                'agent' => 'Agent Name',
                'status' => 'Status',
                'payment_type' => 'Payment type',
                'bill' => 'Bill type',
                'b2c_b2b' => 'B2c/B2B',
                'inscription_date' => 'Inscription Date',
                'consumption' => 'consumption',
                'contract_type' => 'contract_type',
                'product_type' => 'Product type',

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
                                if ($v == 'Inscription Date'){
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
                    }
                }
            }
            return back()->with('success', 'Data Imported successfully.');
        }
        return view('admin.bills.import');
    }
}
