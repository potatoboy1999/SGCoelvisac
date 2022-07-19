<?php

namespace App\Http\Controllers;

use App\Mail\TravelAlert;
use App\Mail\TravelValidationAlert;
use App\Models\TravelSchedule;
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

        // find schedule
        $schedule = TravelSchedule::whereNotNull('id')->first();
        
        // return $manager;
        // Mail::to('alejandrodazaculqui@hotmail.com')->send(new TravelAlert('Alejandro Daza', route('agenda.pending'), $schedule));
        Mail::to('alejandrodazaculqui@hotmail.com')->send(new TravelValidationAlert('Finanzas', route('agenda.pending'), $schedule));

        return ["status"=>"ok"];
    }
}
