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
        'area_id',
        'hito',
        'nombre',
        'inicio',
        'fin',
        'fecha_final',
        'estado',
    ];

    /* estado
    ==============
        0 = Deleted
        1 = Not started
        2 = Working on it
        3 = Finished
        4 = Not Finished
    */

    public function objective()
    {
        return $this->belongsTo(StratObjective::class,"objetivo_id","id");
    }

    public function area()
    {
        return $this->belongsTo(Area::class,"area_id","id");
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class,"t_sgcv_accion_doc", "accion_id", "documento_id")->wherePivot('estado', 1);
    }
    
}
