<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_areas";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'estado',
    ];

    public function positions(){
        return $this->hasMany(Position::class,'area_id','id');
    }

    public function reu_documents(){
        return $this->belongsToMany(Document::class,'t_sgcv_reu_document','area_id','documento_id');
    }
}