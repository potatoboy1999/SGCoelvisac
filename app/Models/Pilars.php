<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilars extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_pilares";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function dimensions()
    {
        return $this->hasMany(Dimensions::class,"pilar_id","id");
    }
}
