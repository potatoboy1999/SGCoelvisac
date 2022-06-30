<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionProfile;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function index(Request $request)
    {
        $page = "users";
        $bcrums = ["Users"];

        $profiles = Profile::where('estado', 1);
        $profiles->with(['options'=>function($qOpt){
            $qOpt->where('t_sgcv_opciones.estado', 1)
                ->where('t_sgcv_opcion_perfil.estado', 1)
                ->where('tipo_opcion', 2)
                ->orderBy('num_nivel','asc')
                ->orderBy('num_orden','asc');
        }]);
        $profiles = $profiles->get();
        return view('intranet.profiles.index', [
            'page' => $page,
            'bcrums' => $bcrums,
            'profiles' => $profiles,
        ]);
    }

    public function deactivate(Request $request)
    {
        $profile = Profile::where('id', $request->id)->first();
        if($profile){
            $profile->estado = 0;
            $profile->save();
        }

        return back();
    }

    public function activate(Request $request)
    {
        $profile = Profile::where('id', $request->id)->first();
        if($profile){
            $profile->estado = 1;
            $profile->save();
        }

        return back();
    }

    public function popupSaveShow(Request $request){
        $options = Option::where('estado', 1)
                        ->where('tipo_opcion', 1);
        $options->with(['childrenOption'=>function($q){
            $q->where('estado', 1)
                ->orderBy('num_nivel','asc')
                ->orderBy('num_orden','asc');
        }]);
        $options = $options->orderBy('num_nivel','asc')
                    ->orderBy('num_orden','asc')
                    ->get();
        $profile = null;
        if(isset($request->id)){
            $profile = Profile::where('id',$request->id);
            $profile->with(['options'=>function($qOpt){
                $qOpt->where('t_sgcv_opciones.estado', 1)
                    ->where('t_sgcv_opcion_perfil.estado', 1)
                    ->where('tipo_opcion', 2)
                    ->orderBy('num_nivel','asc')
                    ->orderBy('num_orden','asc');
            }]);

            $profile = $profile->first();
        }

        return view('intranet.profiles.save_profile',[
            'options' => $options,
            'profile' => $profile,
        ]);
    }

    public function popupSaveNew(Request $request){
        $profile = new Profile;
        $profile->descripcion = $request->description;
        $profile->save();

        if(isset($request->options)){
            $parent = null;
            foreach ($request->options as $k => $opt) {
                $option = Option::find($opt);
                if($option){
                    // also add parent - but just once
                    if($parent && $parent == $option->opcion_padre_id){
                        // dont add parent again
                    }else{
                        // add parent
                        $parent = $option->opcion_padre_id;
                        $parent_pivot = new OptionProfile;
                        $parent_pivot->perfil_id = $profile->id;
                        $parent_pivot->opcion_id = $parent;
                        $parent_pivot->estado = 1;
                        $parent_pivot->save();
                    }

                    $pivot = new OptionProfile;
                    $pivot->perfil_id = $profile->id;
                    $pivot->opcion_id = $opt;
                    $pivot->estado = 1;
                    $pivot->save();
                }
            }
        }
        return back();
    }

    public function popupSaveUpdate(Request $request){
        $profile = Profile::where('id',$request->id)->first();
        if($profile){
            $profile->descripcion = $request->description;
            $profile->save();

            //delete all options and set again
            OptionProfile::where('perfil_id', $profile->id)->delete();

            if(isset($request->options)){
                $parent = null;
                foreach ($request->options as $k => $opt) {
                    $option = Option::find($opt);
                    if($option){
                        // also add parent - but just once
                        if($parent && $parent == $option->opcion_padre_id){
                            // dont add parent again
                        }else{
                            // add parent
                            $parent = $option->opcion_padre_id;
                            $parent_pivot = new OptionProfile;
                            $parent_pivot->perfil_id = $profile->id;
                            $parent_pivot->opcion_id = $parent;
                            $parent_pivot->estado = 1;
                            $parent_pivot->save();
                        }

                        $pivot = new OptionProfile;
                        $pivot->perfil_id = $profile->id;
                        $pivot->opcion_id = $opt;
                        $pivot->estado = 1;
                        $pivot->save();
                    }
                }
            }
        }

        return back();
    }

}
