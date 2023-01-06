<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectiveComment extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_obj_comentarios";
    public $timestamps = true;

    protected $fillable = [
        'objetivo_id',
        'usuario_id',
        'descripcion',
        'estado',
    ];

    public function objective()
    {
        return $this->belongsTo(StratObjective::class,'objetivo_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'usuario_id','id');
    }
}
