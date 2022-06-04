<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = "t_sgcv_perfiles";
    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'estado',
    ];

    public function options(){
        return $this->belongsToMany(Option::class,"t_sgcv_opcion_perfil","perfil_id","opcion_id");
    }

    public function users(){
        return $this->belongsToMany(User::class,"t_sgcv_usuario_perfil","perfil_id","usuario_id");
    }
}
