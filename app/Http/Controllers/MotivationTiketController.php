<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\motivation\MotivationAchievement;
use App\Models\motivation\MotivationBage;
use App\Models\motivation\MotivationPrize;
use App\Models\motivation\MotivationTiket;

class MotivationTiketController extends Controller
{
    public function storePrize(Request $request) {
        dd($request);
        $response = [
            "data" => $request->all(),
            "status" => 'OK'
        ];

        return json_encode($response); 
    }
}
