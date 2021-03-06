<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_roles";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'area_id',
        'estado'
    ];

    public function themes(){
        return $this->hasMany(Theme::class,"rol_id","id");
    }

    public function area(){
        return $this->belongsTo(Area::class,"area_id","id");
    }
}
