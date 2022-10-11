<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Services\AnalysisService;
use App\Services\DecileService;
use App\Services\RFMService;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $subQuery = Order::betweenDate($request->startDate, $request->endDate); 

        if($request->type === 'perDay'){  //日別
            list($data, $labels, $totals) = AnalysisService::perDay($subQuery);
        }

        if($request->type === 'perMonth'){  //月別
            list($data, $labels, $totals) = AnalysisService::perMonth($subQuery);
        }

        if($request->type === 'perYear'){  //年別
            list($data, $labels, $totals) = AnalysisService::perYear($subQuery);
        }

        if($request->type === 'decile'){  //デシル
            list($data, $labels, $totals) = DecileService::decile($subQuery);
        }

        if($request->type === 'rfm'){  
            list($data, $totals, $eachCount) = RFMService::rfm($subQuery, $request->rfmPrms);

            return response()->json([
                'data' => $data,
                'type' => $request->type,
                'labels' => $eachCount,
                'totals' => $totals,
            ], Response::HTTP_OK); 
        }

        return response()->json([
            'data' => $request->startDate
        ], Response::HTTP_OK);

    }

}



