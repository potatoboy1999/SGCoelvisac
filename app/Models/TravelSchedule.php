<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelSchedule extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Agenda_Viajes";
    public $timestamps = true;

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

}
