<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Scopes\Subtotal;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $startDate = '2022-08-01';
        $endDate = '2022-08-31';

        // $period = Order::betweenDate($startDate, $endDate)
        // ->groupBy('id')
        // ->selectRaw('id, sum(subtotal) as total,
        // customer_name, status, created_at')
        // ->orderBy('created_at')
        // ->paginate(50);

        // dd($period);

        // $subQuery = Order::betweenDate($startDate, $endDate)
        // ->where('status', true)
        // ->groupBy('id')
        // ->selectRaw('id, sum(subtotal) as totalParPurchase,
        // DATE_FORMAT(created_at, "%Y%m%d") as date');

        // $date = DB::table($subQuery)
        // ->groupBy('date')
        // ->selectRaw('date, sum(totalParPurchase) as total' )
        // ->get();

        // dd($date);

        // 購買ID毎にまとめる 
        $subQuery = Order::betweenDate($startDate, $endDate) 
            ->groupBy('id') 
            ->selectRaw('id, customer_id, customer_name,
            SUM(subtotal) as totalPerPurchase'); 

        // 会員毎にまとめて購入金額順にソート 上の続き
        $subQuery = DB::table($subQuery) 
            ->groupBy('customer_id') 
            ->selectRaw('customer_id, customer_name,
            sum(totalPerPurchase) as total') 
            ->orderBy('total', 'desc')->get();

            dd($subQuery);

        return Inertia::render('Analysis');
    }
}
