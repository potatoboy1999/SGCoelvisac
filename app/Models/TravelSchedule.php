<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelSchedule extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_agenda_viajes";
    public $timestamps = true;

    // STATUS
    // ======================================
    // 0 = eliminado
    // 1 = enviado a gerente de area
    // 2 = aprovado por el gerente de area
    // 3 = rechazado por el gerente de area
    // 4 = enviado a area de gestion
    // 5 = aprovado a area de gestion
    // 6 = rechazado a area de gestion

    // VALIDACION
    // ======================================
    // 0 = not set
    // 1 = no aprovado
    // 2 = aprobado

    protected $fillable = [
        'usuario_id',
        'sede_id',
        'viaje_comienzo',
        'viaje_fin',
        'vehiculo',
        'hospedaje',
        'viaticos',
        'estado',
        'validacion_uno',
        'validacion_dos',
    ];

    public function user(){
        return $this->belongsTo(User::class,"usuario_id","id");
    }

    public function branch(){
        return $this->belongsTo(Branch::class,"sede_id","id");
    }

    public function activities(){
        return $this->hasMany(TravelActivity::class,"agenda_viaje_id","id");
    }

    public function reportActivities(){
        return $this->hasMany(ReportActivity::class, "agenda_viaje_id", "id");
    }

}
