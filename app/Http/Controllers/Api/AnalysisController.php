<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $subQuery = Order::betweenDate($request->startDate, $request->endDate); 

        if($request->type === 'perDay')  //日別だったら
        { 
            $subQuery->where('status', true)
            ->groupBy('id')
            ->selectRaw('SUM(subtotal) as totalPerPurchase,
            DATE_FORMAT(created_at, "%Y%m%d") as date')
            ->groupBy('date');
        
            $data = DB::table($subQuery)  //日別の合計クエリ
            ->groupBy('date') 
            ->selectRaw('date, sum(totalPerPurchase) as total') 
            ->get();   

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        } 

        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $labels,
            'totals' => $totals,
        ], Response::HTTP_OK); 

        return response()->json([
            'data' => $request->startDate
        ], Response::HTTP_OK);

    }

}



