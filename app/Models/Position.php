<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Posiciones";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'area_id',
        'estado',
    ];

    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function users(){
        return $this->hasMany(Users::class,'positions_id','id');
    }
}
