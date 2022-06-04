<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionTheme extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Reu_Temas";
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
        return $this->belongsToMany(Document::class,"T_SGCV_Reu_document","reu_tema_id","documento_id");
    }
}
