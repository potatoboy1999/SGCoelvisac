<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Objetivos";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'tema_id',
        'estado',
    ];

    public function theme(){
        return $this->belongsTo(Theme::class, "tema_id","id");
    }
    public function kpis(){
        return $this->belongsToMany(Kpi::class,"T_SGCV_Obj_kpi","objetivo_id","kpi_id");
    }
    public function activities(){
        return $this->hasMany(Activity::class,'objetivo_id','id');
    }
}
