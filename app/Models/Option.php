<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table = "T_SGCV_Opciones";
    public $timestamps = true;

    protected $fillable = [
        'opcion',
        'url',
        'url_img',
        'url_label',
        'num_orden',
        'num_nivel',
        'opcion_padre_id',
        'tipo_opcion',
        'estado',
    ];

    public function profiles(){
        return $this->belongsToMany(Profile::class,"T_SGCV_Opcion_Perfil","opcion_id","perfil_id");
    }
}
