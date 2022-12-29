<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StratObjective extends Model
{
    use HasFactory;
    
    // also includes Specific Objetives (where obj_estrategico_id != null)
    protected $table = "t_sgcv_objetivos_estrat";
    public $timestamps = true;

    protected $fillable = [
        'codigo',
        'obj_estrategico_id',
        'area_id',
        'rol_id',
        'dimension_id',
        'nombre',
        'estado',
    ];

    public function stratObjective()
    {
        return $this->belongsTo(StratObjective::class,"obj_estrategico_id","id");
    }

    public function area()
    {
        return $this->belongsTo(Area::class, "area_id","id");
    }

    public function rol()
    {
        return $this->belongsTo(AreaRoles::class,"rol_id","id");
    }

    public function dimension()
    {
        return $this->belongsTo(Dimensions::class,"dimension_id","id");
    }

    public function users()
    {
        return $this->belongsToMany(User::class,"t_sgcv_obj_user","objetivo_id","usuario_id")->wherePivot('estado',1);
    }

    public function kpis()
    {
        return $this->hasMany(Kpis::class,"objetivo_id","id");
    }

    public function actions()
    {
        return $this->hasMany(Action::class,"objetivo_id","id");
    }
}
