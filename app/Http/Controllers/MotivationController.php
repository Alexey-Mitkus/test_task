<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\motivation\MotivationAchievement;
use App\Models\motivation\MotivationBage;
use Auth;

class MotivationController extends Controller
{
    //
    public function index() {

        $user = Auth::check() ? Auth::user() : null;

        // $verify = $user->hasPermissionTo(\Spatie\Permission\Models\Permission::where('name', 'verified')->first()->id);
        // $inside = $user->hasPermissionTo(\Spatie\Permission\Models\Permission::where('name', 'internal')->first()->id);


        $bages = MotivationBage::all();
        $data = collect([]);
        
        foreach($bages as $bage) {
            $achievments = MotivationAchievement::where('bage_id', $bage->id)->get()->toArray();

            $data->push([
                'id'    => $bage->id,
                'title' => $bage->title,
                'count' => $bage->count,
                'achievments' => $achievments
            ]);
        }

        return view('motivation.index', [
            'user' => true,
            'bages' => $data
        ]);
    }
}
