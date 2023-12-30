<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        //SELECT status, count(status) as number FROM `order` GROUP BY status;
        $datas = DB::table('order')
        ->selectRaw('status, count(*) as number')
        ->groupBy('status')
        ->get();

        $result = [];
        $result[] = ['Status', 'Number'];
        foreach($datas as $data){
            $result[] = [ucfirst($data->status), $data->number];
        }

        // SELECT DATE_FORMAT(created_at, '%Y%m') as monthYear, count(*) as number
        // FROM `order`
        // GROUP BY monthYear; 
        $dataOrderNumber = DB::table('order')
        ->selectRaw("DATE_FORMAT(created_at, '%Y%m') as monthYear, count(*) as number")
        ->groupBy('monthYear')
        ->get();

        $resultOrderNumber = [];
        $resultOrderNumber[] = ['Month Year', 'Number'];
        foreach($dataOrderNumber as $data){
            $resultOrderNumber[] = [$data->monthYear, $data->number];
        }

        return view('admin.pages.dashboard')
        ->with('result', $result)
        ->with('resultOrderNumber', $resultOrderNumber);
    }
}
