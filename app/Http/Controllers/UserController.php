<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $page = "users";
        $bcrums = ["Users"];
        
        $users = User::where('estado', 1);
        $users->orderBy('nombre','asc');
        $users = $users->get();

        return view('intranet.users.index',[
            'page' => $page,
            'bcrums' => $bcrums,
            'users' => $users
        ]);
    }
}
