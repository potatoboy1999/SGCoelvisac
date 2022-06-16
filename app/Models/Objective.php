<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_objetivos";
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
        return $this->belongsToMany(Kpi::class,"t_sgcv_obj_kpi","objetivo_id","kpi_id");
    }
    public function activities(){
        return $this->hasMany(Activity::class,'objetivo_id','id')->where('estado',1);
    }
}
