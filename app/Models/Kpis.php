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

    public static $months = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

    public static function getCicleDef()
    {
        return [
            "men" => [
                "count" => 12,
                "label" => "mes"
            ],
            "bim" => [
                "count" => 6,
                "label" => "Bimestre"
            ],
            "tri" => [
                "count" => 4,
                "label" => "Trimestre"
            ],
            "sem" => [
                "count" => 2,
                "label" => "Semestre"
            ],
            "anu" => [
                "count" => 1,
                "label" => date('Y'),
                "label_now" => date('Y'),
                "label_future" => date('Y',strtotime('+1 year'))
            ],
        ];
    }

    public function kpiDates()
    {
        return $this->hasMany(KpiDates::class,"kpi_id","id");
    }

    public function objective()
    {
        return $this->belongsTo(StratObjective::class,"objetivo_id","id");
    }
}
