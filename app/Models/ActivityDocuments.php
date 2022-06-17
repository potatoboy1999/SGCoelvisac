<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDocuments extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_act_docs";
    public $timestamps = true;

    protected $fillable = [
        'actividad_id',
        'documento_id',
        'estado',
    ];
}
