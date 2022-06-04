<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionProfile extends Model
{
    use HasFactory;
    protected $table = "T_SGCV_Opcion_Perfil";
    public $timestamps = true;

    protected $fillable = [
        'perfil_id',
        'opcion_id',
        'estado',
    ];

    public function option(){
        return $this->belongsTo(Option::class,"opcion_id","id");
    }

    public function profile(){
        return $this->belongsTo(Profile::class,"perfil_id","id");
    }
}
