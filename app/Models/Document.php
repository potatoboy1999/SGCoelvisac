<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Documentos";
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'estado',
    ];

    public function activities(){
        return $this->hasMany(Activity::class,"documento_id","id");
    }

    public function reu_documents(){
        return $this->hasMany(ReunionDocument::class,"documento_id","id");
    }
}
