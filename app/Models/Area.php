<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Areas";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'estado',
    ];

    public function positions(){
        return $this->hasMany(Position::class,'area_id','id');
    }

    public function reu_documents(){
        return $this->belongsToMany(Document::class,'T_SGCV_Reu_document','area_id','documento_id');
    }
}
