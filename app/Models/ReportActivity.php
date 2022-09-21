<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportActivity extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_reporte_actividades";
    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'tipo',
        'acuerdo',
        'fecha_comienzo',
        'fecha_fin',
        'es_cerrado',
        'cerrado_por',
        'agenda_viaje_id',
        'actividades_viaje_id',
        'estado',
    ];

    /* estado
    ==============
        0 = Deleted
        1 = Not started
        2 = Working on it
        3 = Finished
        4 = Not Finished
    */

    public function travelSchedule(){
        return $this->belongsTo(TravelSchedule::class,"agenda_viaje_id","id");
    }

    public function travelActivity(){
        return $this->belongsTo(TravelActivity::class,"actividades_viaje_id","id");
    }

    public function closedByUser(){
        return $this->belongsTo(User::class,"cerrado_por","id");
    }
}
