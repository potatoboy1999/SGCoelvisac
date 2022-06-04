<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $table = "T_SGCV_Usuario_Perfil";
    public $timestamps = true;

    protected $fillable = [
        'perfil_id',
        'usuario_id',
        'estado',
    ];

    public function profile(){
        return $this->belongsTo(Profile::class,"perfil_id","id");
    }

    public function user(){
        return $this->belongsTo(Profile::class,"usuario_id","id");
    }
}
