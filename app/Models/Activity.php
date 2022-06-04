<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Actividades";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'objetivo_id',
        'fecha_comienzo',
        'fecha_fin',
        'doc_politicas_id',
        'doc_adjunto_id',
        'estado',
    ];

    public function comments(){
        return $this->hasMany(Comment::class, "actividad_id", "id");
    }
    
    public function objective(){
        return $this->belongsTo(Objective::class, "objetivo_id", "id");
    }

    public function docAdjacent(){
        return $this->belongsTo(Document::class, "doc_adjunto_id", "id");
    }

    public function docPoliticas(){
        return $this->belongsTo(Document::class, "doc_politicas_id", "id");
    }
}
