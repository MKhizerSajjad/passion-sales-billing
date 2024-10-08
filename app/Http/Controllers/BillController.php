<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $rows = [];
            $data = $file->get();
            dd($data);
            foreach ($data as $key => $value) {
                $rows[] = str_getcsv($value);
            }

            DB::table('bills')->insert($rows);
        }
        return view('admin.bills.import');
    }
}
