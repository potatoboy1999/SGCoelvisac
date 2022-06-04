<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = "T_SGCV_Perfiles";
    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'estado',
    ];

    public function options(){
        return $this->belongsToMany(Option::class,"T_SGCV_Opcion_Perfil","perfil_id","opcion_id");
    }

    public function users(){
        return $this->belongsToMany(User::class,"T_SGCV_Usuario_Perfil","perfil_id","usuario_id");
    }
}
