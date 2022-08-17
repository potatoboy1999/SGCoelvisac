<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Document;
use App\Models\Reunion;
use App\Models\ReunionConsolidado;
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
        // return $branches->toArray();

        return view('intranet.reunions.index',[
            "page"=>$page,
            "bcrums" => $bcrums,
            "year" => $year,
            "month" => $month,
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

    public function viewReunion(Request $request)
    {
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));

        $areas = Area::where('estado',1)->where('vis_matriz', 1)->get();

        $reunion = Reunion::where('estado','>',0)
                        ->where('fecha',$year.'-'.$month.'-28');
        $reunion->with(['documents'=>function($docQ){
            $docQ->where('t_sgcv_reu_document.estado', 1);
            $docQ->where('t_sgcv_documentos.estado', 1);
        }]);

        $reunion->with(['consolidado_documents'=>function($docQ){
            $docQ->where('t_sgcv_reu_consolidado.estado', 1);
            $docQ->where('t_sgcv_documentos.estado', 1);
        }]);
        
        $reunion = $reunion->first();

        return view('intranet.reunions.details',[
            "year" => $year,
            "month" => $month,
            "reunion" => $reunion,
            'areas' => $areas,
        ]);
    }

    public function viewFrontReunion(Request $request)
    {
        $year = intval(isset($request->year)?$request->year:date('Y'));
        $month = intval(isset($request->month)?$request->month:date('m'));

        $areas = Area::where('estado',1)->where('vis_matriz', 1)->get();

        $reunion = Reunion::where('estado','>',0)
                        ->where('fecha',$year.'-'.$month.'-28');
        $reunion->with(['documents'=>function($docQ){
            $docQ->where('t_sgcv_reu_document.estado', 1);
            $docQ->where('t_sgcv_documentos.estado', 1);
        }]);

        $reunion->with(['consolidado_documents'=>function($docQ){
            $docQ->where('t_sgcv_reu_consolidado.estado', 1);
            $docQ->where('t_sgcv_documentos.estado', 1);
        }]);
        
        $reunion = $reunion->first();

        return view('front.reunions.details',[
            "year" => $year,
            "month" => $month,
            "reunion" => $reunion,
            'areas' => $areas,
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
        $reunion->fecha = date_format(date_create_from_format('d/m/Y',$request->date),'Y-m-28');
        $reunion->estado = 1;
        $reunion->save();

        $sizeMax = 8388608; // 8MB
        $destinationPath = 'uploads';
        $i = 0; // themes loop index
        foreach ($request->theme as $key => $theme_name) {

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
                                                $reuDoc->reunion_id = $reunion->id;
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

        // Remove deleted documents
        if(isset($request->docs_deleted)){
            ReunionDocument::whereIn('documento_id', $request->docs_deleted)->update(['estado' => 0]);
            Document::whereIn('id',$request->docs_deleted)->update(['estado' => 0]);
        }

        // Edit existing themes

        $sizeMax = 8388608; // 8MB
        $destinationPath = 'uploads';
        $i = 0; // themes loop index
        foreach ($request->theme as $key => $theme_name) {            

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
                                                $reuDoc->reunion_id = $reunion->id;
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

    public function storeDocument(Request $request)
    {
        if($request->reunion == 0){
            $reunion = new Reunion();
            $reunion->usuario_id = Auth::user()->id;
            $reunion->fecha = $request->date;
            $reunion->estado = 1;
            $reunion->save();
        }else{
            $reunion = Reunion::find($request->reunion);
        }
        if($reunion){
            $alert = "";
            $sizeMax = 8388608; // 8MB
            $valMimes = ["application/pdf"]; // pdf
            $destinationPath = 'uploads';

            if($request->hasFile("file") && $request->file("file")->isValid()){
                $file = $request->file;

                $ogName = $file->getClientOriginalName();
                $ogExtension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $mime = $file->getMimeType();

                if($size <= $sizeMax){
                    //Move Uploaded File
                    $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                    $newName = "file".$now->format("Ymd-His-u")."02.".$ogExtension;
                    $file->move($destinationPath, $newName);
    
                    $doc = new Document;
                    $doc->nombre = substr($ogName, 0, 150);
                    $doc->file = $newName;
                    $doc->estado = 1;
                    $doc->save();

                    if($request->area != 0){
                        $reuDoc = new ReunionDocument;
                        $reuDoc->area_id = $request->area;
                        $reuDoc->reunion_id = $reunion->id;
                        $reuDoc->documento_id = $doc->id;
                        $reuDoc->estado = 1;
                        $reuDoc->save();
                    }else{
                        $reuDoc = new ReunionConsolidado;
                        $reuDoc->reunion_id = $reunion->id;
                        $reuDoc->documento_id = $doc->id;
                        $reuDoc->estado = 1;
                        $reuDoc->save();
                    }
                }else{
                    return [
                        'status'=> 'error',
                        'code'=> 3, // file too big
                        'alert' => 'Problemas con el archivo adjunto',
                        'reunion'=> $reunion,
                    ];
                }
            }else{
                return [
                    'status'=> 'error',
                    'code'=> 2, // file not valid
                    'alert' => 'Problemas con el archivo adjunto',
                    'reunion'=> $reunion,
                ];
            }
            return [
                'status'=> 'ok',
                'reunion'=> $reunion,
                'document' => $doc
            ];
        }else{
            return [
                'status'=>'error',
                'code'=> 1, // reunion not created or found
                'msg'=> 'Reunion not found'
            ];
        }
    }

    public function deleteDocument(Request $request)
    {
        ReunionDocument::where('documento_id', $request->doc_id)->update(['estado' => 0]);
        ReunionConsolidado::where('documento_id', $request->doc_id)->update(['estado' => 0]);
        Document::where('id',$request->doc_id)->update(['estado' => 0]);

        return ['status'=>'ok'];
    }
}
