<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFile extends Model
{
    use HasFactory;
    protected $table = "t_sgcv_report_files";
    public $timestamps = true;

    protected $fillable = [
        'agenda_viaje_id',
        'documento_id',
        'estado',
    ];
    
}
