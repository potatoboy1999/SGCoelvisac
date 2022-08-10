<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Document;
use App\Models\Reunion;
use App\Models\ReunionDocument;
use App\Models\ReunionPresenter;
use App\Models\ReunionTheme;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReunionController extends Controller
{
    public function backIndex(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Reunión"];
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        $cal_type = isset($request->cal_type)?$request->cal_type : 1;
        // return $branches->toArray();

        return view('intranet.reunions.index',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "year" => $year,
            "month" => $month,
            "cal_type" => $cal_type,
        ]);
    }

    public function frontIndex(Request $request)
    {
        $page = 'result_reunion';
        $m_areas = Area::where('vis_matriz', 1)
                    ->where('estado',1)
                    ->get();
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        return view('front.reunions.index',[
            "page" => $page,
            "m_areas" => $m_areas,
            "year" => $year,
            "month" => $month,
        ]);
    }

    public function viewCalendar(Request $request)
    {
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));
        $cal_type = isset($request->cal_type)?$request->cal_type : 1;
        $endMonth = $month + 1;
        $endYear = $year;
        if($endMonth > 12){
            $endMonth = 1;
            $endYear = $year+1;
        }

        $reunions = Reunion::where('estado','>',0);

        if($cal_type == 1){
            $reunions->where('fecha','>=',$year.'-'.$month.'-01')
                    ->where('fecha','<',($endYear).'-'.$endMonth.'-01')
                    ->orderBy('fecha','asc');
        }else{
            $reunions->where('fecha','>=',$year.'-01-01')
                    ->where('fecha','<',($year+1).'-01-01')
                    ->orderBy('fecha','asc');
        }
        $reunions = $reunions->get();

        return view('intranet.reunions.calendar',[
            "year" => $year,
            "month" => $month,
            "reunions" => $reunions
        ]);
    }

    public function createReunion(Request $request)
    {
        $page   = "objectives";
        $bcrums = ["Reunión"];
        $date   = $request->date;
        $areas = Area::where('estado',1)
                    ->where('vis_matriz',1)
                    ->select('id','nombre')
                    ->orderBy('nombre','asc')
                    ->get();
        return view('intranet.reunions.create_modify',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "date" => $date,
            'areas' => $areas,
            'areas_arr' => $areas->toArray()
        ]);
    }

    public function deleteReunion(Request $request)
    {
        $reunion = Reunion::find($request->reunion_id);
        if($reunion){
            $reunion->estado = 0;
            $reunion->save();
            return ['status'=>'ok'];
        }
        return ['status'=>'error', 'msg' => 'No se encontro la reunión en la base de datos'];
    }

    public function createModify(Request $request)
    {
        $page   = "objectives";
        $bcrums = ["Reuniones"];
        $date   = $request->date;
        $areas = Area::where('estado',1)
                    ->where('vis_matriz',1)
                    ->select('id','nombre')
                    ->orderBy('nombre','asc')
                    ->get();
        $reunion = Reunion::where('id', $request->id);
        $reunion->with(['reunionThemes' => function($qTheme){
            $qTheme->where('estado', 1);
            $qTheme->with(['documents' => function($qDoc){
                $qDoc->where('t_sgcv_documentos.estado', 1);
                $qDoc->orderBy('t_sgcv_reu_document.area_id', 'asc');
                $qDoc->orderBy('t_sgcv_reu_document.created_at', 'asc');
            }]);
        }]);
        $reunion = $reunion->first();
        // return $reunion;
        return view('intranet.reunions.create_modify',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "date" => $date,
            'areas' => $areas,
            'areas_arr' => $areas->toArray(),
            'reunion' => $reunion
        ]);
    }

    public function storeReunion(Request $request)
    {
        $alerts = [];

        $reunion = new Reunion;
        $reunion->usuario_id = Auth::user()->id;
        $reunion->titulo = $request->title;
        $reunion->descripcion = $request->description;
        $reunion->fecha = date_format(date_create_from_format('d/m/Y',$request->date),'Y-m-d');
        $reunion->estado = 1;
        $reunion->save();

        // add users presenters
        foreach ($request->users as $k => $user) {
            $presenter = new ReunionPresenter;
            $presenter->usuario_id = $user;
            $presenter->reunion_id = $reunion->id;
            $presenter->estado = 1;
            $presenter->save();
        }

        $sizeMax = 8388608; // 8MB
        $destinationPath = 'uploads';
        $i = 0; // themes loop index
        foreach ($request->theme as $key => $theme_name) {
            $theme = new ReunionTheme;
            $theme->titulo = $theme_name;
            $theme->reunion_id = $reunion->id;
            $theme->estado = 1;
            $theme->save();

            // print("TEMA: ".$theme_name." | KEY: ".$key."<br>");
            $x = 0;
            foreach ($request->area[$key] as $area_key => $area_id) {
                // $area_id = $request->area[$key][$x];
                // print("AREA: ".$area_id."<br>");
                foreach ($request->files as $themes) {
                    foreach ($themes as $theme_key => $area_files) {
                        if($theme_key == $key){
                            $cnter = 0;
                            foreach ($area_files as $a_key => $files) {
                                if($a_key == $area_key){
                                    foreach($files as $k => $file){
                                        if($file->isValid()){
                                            // print("FILE: NAME: ".$file->getClientOriginalName()."<br>");

                                            $ogName = $file->getClientOriginalName();
                                            $ogExtension = $file->getClientOriginalExtension();
                                            $size = $file->getSize();
                                            $mime = $file->getMimeType();

                                            if($size <= $sizeMax){
                                                //Move Uploaded File
                                                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                                                $newName = "file".$now->format("Ymd-His-u")."02.".$ogExtension;
                                                $file->move($destinationPath, $newName);
                                
                                                $tempDoc = new Document;
                                                $tempDoc->nombre = substr($ogName, 0, 150);
                                                $tempDoc->file = $newName;
                                                $tempDoc->estado = 1;
                                                $tempDoc->save();
                                
                                                $reuDoc = new ReunionDocument;
                                                $reuDoc->area_id = $area_id;
                                                $reuDoc->reu_tema_id = $theme->id;
                                                $reuDoc->documento_id = $tempDoc->id;
                                                $reuDoc->estado = 1;
                                                $reuDoc->save();

                                            }else{
                                                $alerts[] = "<br>Problemas con uno o varios archivos adjuntos";
                                            }
                                        }
                                    }
                                    break;
                                }
                                $cnter++;
                            }
                            break;
                        }
                    }
                }
                // print("<br><br>");
                $x++;
            }
            // print('<br>');
            $i++;
        }

        return redirect()->route('results.index')->with([
            'item_status' => true, 
            'item_msg' => 'Nuevo item creado'
        ]);
    }

    public function updateReunion(Request $request){
        // return $request->all();

        $reunion = Reunion::find($request->reunion_id);
        $reunion->titulo = $request->title;
        $reunion->descripcion = $request->description;
        $reunion->save();

        // renew presenters list
        ReunionPresenter::where('reunion_id', $request->reunion_id)
                        ->delete();

        foreach ($request->users as $k => $user) {
            $presenter = new ReunionPresenter;
            $presenter->usuario_id = $user;
            $presenter->reunion_id = $reunion->id;
            $presenter->estado = 1;
            $presenter->save();
        }

        // Remove deleted themes & documents
        if(isset($request->themes_deleted)){
            foreach ($request->themes_deleted as $theme){
                // disable theme
                $theme = ReunionTheme::find($theme);
                $theme->estado = 0;
                $theme->save();

                // disable documents
                $reu_docs = ReunionDocument::where('reu_tema_id', $theme)->get();
                $docs_id = [];
                foreach ($reu_docs as $doc) {
                    $docs_id[] = $doc->id;
                }
                if(sizeof($docs_id) > 0){
                    ReunionDocument::whereIn('documento_id', $docs_id)->update(['estado' => 0]);
                    Document::whereIn('id',$docs_id)->update(['estado' => 0]);
                }
            }
        }

        // Remove deleted documents
        if(isset($request->docs_deleted)){
            ReunionDocument::whereIn('documento_id', $request->docs_deleted)->update(['estado' => 0]);
            Document::whereIn('id',$request->docs_deleted)->update(['estado' => 0]);
        }

        // Edit existing themes
        $j = 0;
        foreach ($request->theme_ids as $k => $theme_id) {
            $j = $k;
            $theme = ReunionTheme::find($theme_id);
            $z = 0;
            $title = "";
            foreach($request->theme as $theme_name){
                if($j == $z){
                    $title = $theme_name;
                    break;
                }
                $z++;
            }
            $theme->titulo = $title;
            $theme->save();
        }

        $sizeMax = 8388608; // 8MB
        $destinationPath = 'uploads';
        $i = 0; // themes loop index
        foreach ($request->theme as $key => $theme_name) {
            if($i > $j){
                $theme = new ReunionTheme;
                $theme->titulo = $theme_name;
                $theme->reunion_id = $reunion->id;
                $theme->estado = 1;
                $theme->save();
            }else{
                $theme = ReunionTheme::find($request->theme_ids[$i]);
            }

            // print("TEMA: ".$theme_name." | KEY: ".$key."<br>");
            $x = 0;
            foreach ($request->area[$key] as $area_key => $area_id) {
                // $area_id = $request->area[$key][$x];
                // print("AREA: ".$area_id."<br>");
                foreach ($request->files as $themes) {
                    foreach ($themes as $theme_key => $area_files) {
                        if($theme_key == $key){
                            $cnter = 0;
                            foreach ($area_files as $a_key => $files) {
                                if($a_key == $area_key){
                                    foreach($files as $k => $file){
                                        if($file->isValid()){
                                            // print("FILE: NAME: ".$file->getClientOriginalName()."<br>");

                                            $ogName = $file->getClientOriginalName();
                                            $ogExtension = $file->getClientOriginalExtension();
                                            $size = $file->getSize();
                                            $mime = $file->getMimeType();

                                            if($size <= $sizeMax){
                                                //Move Uploaded File
                                                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                                                $newName = "file".$now->format("Ymd-His-u")."02.".$ogExtension;
                                                $file->move($destinationPath, $newName);
                                
                                                $tempDoc = new Document;
                                                $tempDoc->nombre = substr($ogName, 0, 150);
                                                $tempDoc->file = $newName;
                                                $tempDoc->estado = 1;
                                                $tempDoc->save();
                                
                                                $reuDoc = new ReunionDocument;
                                                $reuDoc->area_id = $area_id;
                                                $reuDoc->reu_tema_id = $theme->id;
                                                $reuDoc->documento_id = $tempDoc->id;
                                                $reuDoc->estado = 1;
                                                $reuDoc->save();

                                            }else{
                                                $alerts[] = "<br>Problemas con uno o varios archivos adjuntos";
                                            }
                                        }
                                    }
                                    break;
                                }
                                $cnter++;
                            }
                            break;
                        }
                    }
                }
                // print("<br><br>");
                $x++;
            }
            // print('<br>');
            $i++;
        }

        return redirect()->back()->with([
            'item_status' => true, 
        ]);

        return $request->all();
    }

    public function viewReunions(Request $request)
    {
        $page = "objectives";
        $bcrums = ["Reuniones"];
        $user = Auth::user();
        $reunions = Reunion::where('t_sgcv_reuniones.estado', 1);
        if($user->position->area_id != 1){
            // only see reunions current user created or is involved in
            $reunions->where(function($q) use ($user){
                $q->where('t_sgcv_reuniones.usuario_id', $user->id);
                $q->orWhere('t_sgcv_reu_presentadores.usuario_id', $user->id);
            });
        }
        $reunions->join('t_sgcv_reu_presentadores','t_sgcv_reu_presentadores.reunion_id','t_sgcv_reuniones.id');
        $reunions->select('t_sgcv_reuniones.id','t_sgcv_reuniones.titulo','t_sgcv_reuniones.fecha');
        $reunions->groupBy('t_sgcv_reuniones.id','t_sgcv_reuniones.titulo','t_sgcv_reuniones.fecha');
        $reunions->orderBy('t_sgcv_reuniones.fecha', 'desc');
        $reunions = $reunions->get();

        return view('intranet.reunions.list',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "reunions"=>$reunions
        ]);
    }

    public function showPopup(Request $request)
    {
        $areas = Area::where('estado',1)
        ->where('vis_matriz',1)
        ->select('id','nombre')
        ->orderBy('nombre','asc')
        ->get();
        
        $reunion = Reunion::where('id', $request->id);
        $reunion->with(['reunionThemes' => function($qTheme){
            $qTheme->where('estado', 1);
            $qTheme->with(['documents' => function($qDoc){
                $qDoc->where('t_sgcv_documentos.estado', 1);
                $qDoc->orderBy('t_sgcv_reu_document.area_id', 'asc');
                $qDoc->orderBy('t_sgcv_reu_document.created_at', 'asc');
            }]);
        }]);
        $reunion = $reunion->first();

        return view('intranet.reunions.reunion_popup',[
            'reunion' => $reunion,
            'areas' => $areas,
            'source' => isset($request->source)?$request->source:'back'
        ]);
    }
}
