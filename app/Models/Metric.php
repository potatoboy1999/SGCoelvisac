<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use HasFactory;
    protected $table = "T_SGCV_Metricas";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'simbolo',
        'estado',
    ];

    public function kpis(){
        return $this->hasMany(Kpi::class,"metric_id","id");
    }
}
