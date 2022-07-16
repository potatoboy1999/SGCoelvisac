<?php

namespace App\Http\Controllers;

use App\Mail\TravelAlert;
use App\Mail\TravelValidationAlert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index(){
        $page = "dashboard";
        $bcrums = ["Dashboard"];
        return view('intranet.dashboard.index',[
            "page"=> $page,
            "bcrums"=>$bcrums
        ]);
    }

    public function testMail(){
        // $user = Auth::user();
        // $area = $user->position->area_id;

        // // find manager
        // $manager = User::join('t_sgcv_posiciones','t_sgcv_usuarios.posicion_id','t_sgcv_posiciones.id')
        // ->where('t_sgcv_posiciones.es_gerente', 1)
        // ->where('t_sgcv_posiciones.area_id',$area)
        // ->first();

        // return $manager;
        Mail::to('alejandrodazaculqui@hotmail.com')->send(new TravelAlert('Alejandro Daza', route('agenda.pending') ));
    }
}
