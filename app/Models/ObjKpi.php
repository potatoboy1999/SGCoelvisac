<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjKpi extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Obj_kpi";
    public $timestamps = true;

    protected $fillable = [
        'kpi_id',
        'objectivo_id',
        'estado'
    ];

    public function objective(){
        return $this->belongsTo(Objective::class,'objectivo_id','id');
    }
    
    public function kpi(){
        return $this->belongsTo(Kpi::class,'kpi_id','id');
    }
}
