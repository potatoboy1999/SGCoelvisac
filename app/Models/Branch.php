<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Sedes";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'telefono',
        'direccion',
        'estado',
    ];

    public function travel_schedules(){
        return $this->hasMany(TravelSchedule::class,"sede_id","id");
    }
}
