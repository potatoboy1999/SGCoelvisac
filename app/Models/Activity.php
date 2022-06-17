<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_actividades";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'objetivo_id',
        'fecha_comienzo',
        'fecha_fin',
        'doc_politicas_id',
        'cumplido',
        'estado',
    ];

    public function comments(){
        return $this->hasMany(Comment::class, "actividad_id", "id");
    }
    
    public function objective(){
        return $this->belongsTo(Objective::class, "objetivo_id", "id");
    }

    public function docAdjacents(){
        return $this->belongsToMany(Document::class,"t_sgcv_act_docs", "actividad_id", "documento_id")->wherePivot('estado', 1);
    }

    public function docPolicy(){
        return $this->belongsTo(Document::class, "doc_politicas_id", "id");
    }
}
