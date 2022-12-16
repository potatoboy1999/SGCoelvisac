<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    use HasFactory;
    protected $table = "t_sgcv_highlights";
    public $timestamps = true;

    protected $fillable = [
        'kpi_date',
        'descripcion',
        'status',
    ];

    public function kpiDate(){
        return $this->belongsTo(KpiDates::class, "kpi_date", "id");
    }
}
