<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Comentarios";
    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'actividad_id',
        'usuario_id',
        'estado',
    ];

    public function user(){
        return $this->belongsTo(User::class,"usuario_id","id");
    }

    public function activity(){
        return $this->belongsTo(Activity::class,"actividad_id","id");
    }
}
