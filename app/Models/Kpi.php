<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;
    
    protected $table = "T_SGCV_Kpis";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'metrica_id',
        'estado',
    ];

    public function kpi_calendar(){
        return $this->hasMany(KpiCalendar::class,"kpi_id","id");
    }

    public function metric(){
        return $this->belongsTo(Metric::class,"metrica_id","id");
    }

    public function objectives(){
        return $this->belongsToMany(Objective::class,"T_SGCV_Obj_kpi","kpi_id",'objectivo_id');
    }
}
