<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportActivity extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Reporte_Actividades";
    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'tipo',
        'acuerdo',
        'fecha_comienzo',
        'fecha_fin',
        'agenda_viaje_id',
        'actividades_viaje_id',
        'estado',
    ];

    public function travelSchedule(){
        return $this->belongsTo(TravelSchedule::class,"agenda_viaje_id","id");
    }

    public function travelActivity(){
        return $this->belongsTo(TravelActivity::class,"actividades_viaje_id","id");
    }
}
