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
                "name" => "Mes",
                "count" => 12,
                "label" => "mes",
                "cicles" => [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
            ],
            "bim" => [
                "name" => "Bimestre",
                "count" => 6,
                "label" => "Bimestre",
                "cicles" => [[1,2],[3,4],[5,6],[7,8],[9,10],[11,12]]
            ],
            "tri" => [
                "name" => "Trimestre",
                "count" => 4,
                "label" => "Trimestre",
                "cicles" => [[1,2,3],[4,5,6],[7,8,9],[10,11,12]]
            ],
            "sem" => [
                "name" => "Semestre",
                "count" => 2,
                "label" => "Semestre",
                "cicles" => [[1,2,3,4,5,6],[7,8,9,10,11,12]]
            ],
            "anu" => [
                "name" => "Anual",
                "count" => 1,
                "label" => date('Y'),
                "label_now" => date('Y'),
                "label_future" => date('Y',strtotime('-1 year')),
                "cicles" => [[1,2,3,4,5,6,7,8,9,10,11,12]]
            ],
        ];
    }
    public static function getTypeDef(){
        return [
            "per" => [
                "name" => "Porcentaje",
            ],
            "mon" => [
                "name" => "Moneda",
            ],
            "doc" => [
                "name" => "Entregable",
            ],
            "uni" => [
                "name" => "Unidad",
            ],
            "rat" => [
                "name" => "Ratio",
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
