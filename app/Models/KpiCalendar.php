<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiCalendar extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_kpi_calendario";
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
