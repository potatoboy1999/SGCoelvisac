<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;
    
    protected $table = "t_sgcv_kpis";
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
        return $this->belongsToMany(Objective::class,"t_sgcv_obj_kpi","kpi_id",'objectivo_id');
    }
}
