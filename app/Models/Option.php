<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table = "t_sgcv_opciones";
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
        return $this->belongsToMany(Profile::class,"t_sgcv_opcion_perfil","opcion_id","perfil_id");
    }
}
