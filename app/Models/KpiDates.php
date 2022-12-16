<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiDates extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_kpi_dates";
    public $timestamps = true;

    protected $fillable = [
        'kpi_id',
        'anio',
        'ciclo',
        'real_cantidad',
        'meta_cantidad',
        'estado',
    ];

    public function kpi()
    {
        return $this->belongsTo(Kpi::class, "kpi_id","id");
    }

    public function highlights()
    {
        return $this->hasMany(Highlight::class, "kpi_date","id");
    }
}
