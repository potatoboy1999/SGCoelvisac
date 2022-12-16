<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaRoles extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_area_roles";
    public $timestamps = true;

    protected $fillable = [
        'area_id',
        'nombre',
        'estado',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class,"area_id","id");
    }

    public function stratObjectives()
    {
        return $this->hasMany(StratObjective::class,"rol_id","id");
    }
}
