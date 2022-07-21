<?php

namespace App\Http\Controllers;

use App\Mail\TravelAlert;
use App\Mail\TravelValidationAlert;
use App\Models\TravelSchedule;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

    public function testPdf(){
        $schedule = TravelSchedule::whereNotNull('id')->first();
        if($schedule){

            // --------- DOWNLOAD PDF ---------------
            // $pdf = FacadePdf::loadView('pdf.activity_report', [
            //     "schedule" => $schedule
            // ]);
            // return $pdf->download('report-'.time().'.pdf');

            // --------- STREAM PDF ---------------
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('pdf.activity_report', [
                "schedule" => $schedule
            ]);

            // --------- STORE PDF ---------------
            $path = public_path('pdf/');
            $fileName =  'report-'.time().'.pdf' ;
            $pdf->save($path . '/' . $fileName);

            // return $pdf->download($fileName);

            return $pdf->stream();
            
            // return view('pdf.activity_report', [
            //     "schedule" => $schedule
            // ]);
        }
        return ['status'=>'ok'];
    }
}
