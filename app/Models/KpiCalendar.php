<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiCalendar extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Kpi_Calendario";
    public $timestamps = true;

    protected $fillable = [
        'kpi_id',
        'mes',
        'anio',
        'valor',
        'tipo',
        'estado',
    ];

    public function kpi(){
        return $this->belongsTo(Kpi::class, "kpi_id", "id");
    }
}
