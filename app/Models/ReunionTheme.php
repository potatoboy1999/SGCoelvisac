<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionTheme extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_reu_temas";
    public $timestamps = true;

    protected $fillable = [
        'titulo',
        'reunion_id',
        'estado',
    ];

    public function reunion(){
        return $this->belongsTo(Reunion::class,"reunion_id","id");
    }

    public function documents(){
        return $this->belongsToMany(Document::class,"t_sgcv_reu_document","reu_tema_id","documento_id")->withPivot('area_id','estado','created_at');
    }
}
