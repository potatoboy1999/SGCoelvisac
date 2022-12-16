<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpis extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_kpis";
    public $timestamps = true;

    protected $fillable = [
        'objetivo_id',
        'nombre',
        'descripcion',
        'formula',
        'frecuencia',
        'tipo',
        'meta',
        'estado',
    ];

    /* === frecuencia ==
        - mensual
        - bimestral
        - trimestral
        - semestral
        
       === tipo ==
        - porcentaje
        - moneda
        - entregable
    */

    public function kpiDates()
    {
        return $this->hasMany(KpiDates::class,"kpi_id","id");
    }

    public function objective()
    {
        return $this->belongsTo(StratObjective::class,"objetivo_id","id");
    }
}
