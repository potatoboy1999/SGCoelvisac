<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $page = "users";
        $bcrums = ["Users"];
        
        $users = User::whereNotNull('estado');
        $users->orderBy('nombre','asc');
        $users = $users->get();

        return view('intranet.users.index',[
            'page' => $page,
            'bcrums' => $bcrums,
            'users' => $users
        ]);
    }

    public function deactivate(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        if($user){
            $user->estado = 0;
            $user->save();
        }
        // return ['status'=>'ok'];
        return back();
    }

    public function activate(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        if($user){
            $user->estado = 1;
            $user->save();
        }
        // return ['status'=>'ok'];
        return back();
    }

    public function popupSaveShow(Request $request)
    {
        $profiles = Profile::where('estado', 1)
                            ->orderBy('descripcion')
                            ->get();
        $areas = Area::where('estado', 1)
                    ->orderBy('nombre');
        $areas->with(['positions'=>function($qPos){
            $qPos->where('estado', 1)
                ->orderBy('nombre');
        }]);
        $areas = $areas->get();

        $user = null;
        if(isset($request->id)){
            $user = User::find($request->id);
        }

        return view('intranet.users.save_user',[
            'user' => $user,
            'profiles' => $profiles,
            'areas' => $areas,
            'areas_arr' => $areas->toArray()
        ]);
    }

    public function popupSaveNew(Request $request)
    {
        $user = new User;
        $user->nombre = $request->nombre;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->posicion_id = $request->position;
        $user->estado = 1;
        $user->save();

        $pivot = new UserProfile;
        $pivot->perfil_id = $request->profile;
        $pivot->usuario_id = $user->id;
        $pivot->estado = 1;
        $pivot->save();

        return back();
    }
    
    public function popupSaveUpdate(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        $user->nombre = $request->nombre;
        $user->email = $request->email;
        $user->posicion_id = $request->position;
        if($request->password != ""){
            $user->password = bcrypt($request->password);
        }
        $user->estado = 1;
        $user->save();

        //delete all options and set again
        UserProfile::where('usuario_id', $user->id)->delete();

        $pivot = new UserProfile;
        $pivot->perfil_id = $request->profile;
        $pivot->usuario_id = $user->id;
        $pivot->estado = 1;
        $pivot->save();

        return back();
    }
    
}
