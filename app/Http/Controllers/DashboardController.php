<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $page = "dashboard";
        $bcrums = ["Dashboard"];
        return view('dashboard.index',[
            "page"=> $page,
            "bcrums"=>$bcrums
        ]);
    }
}
