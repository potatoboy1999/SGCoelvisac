<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimensions extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_dimensiones";
    public $timestamps = true;

    protected $fillable = [
        'pilar_id',
        'nombre',
        'estado',
    ];

    public function pilar()
    {
        return $this->belongsTo(Pilars::class, "pilar_id", "id");
    }

    public function stratObjectives()
    {
        return $this->hasMany(StratObjective::class,"dimension_id","id");
    }
}
