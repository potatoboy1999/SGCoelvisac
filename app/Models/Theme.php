<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Temas";
    public $timestamps = true;

    protected $fillable = [
        'rol_id',
        'nombre',
        'estado',
    ];

    public function role(){
        return $this->belongsTo(Role::class,'rol_id','id');
    }

    public function objectives(){
        return $this->hasMany(Objective::class,'tema_id','id');
    }
}
