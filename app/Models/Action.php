<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_acciones";
    public $timestamps = true;

    protected $fillable = [
        'objetivo_id',
        'hito',
        'nombre',
        'inicio',
        'fin',
        'fecha_final',
        'estado',
    ];

    public function objective()
    {
        return $this->belongsTo(StratObjective::class,"objective_id","id");
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class,"t_sgcv_accion_doc", "accion_id", "documento_id")->wherePivot('estado', 1);
    }
    
}
