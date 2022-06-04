<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelActivity extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Actividades_Viajes";
    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'tipo',
        'agenda_viaje_id',
        'estado',
    ];

    public function travel_schedule(){
        return $this->belongsTo(TravelSchedule::class,"agenda_viaje_id","id");
    }

    public function report_activities(){
        return $this->hasMany(ReportActivity::class,"actividades_viaje_id","id");
    }
}
