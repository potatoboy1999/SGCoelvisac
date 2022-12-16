<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionDocuments extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_accion_doc";
    public $timestamps = true;

    protected $fillable = [
        'accion_id',
        'documento_id',
        'estado',
    ];

    public function accion()
    {
        return $this->belongsTo(Action::class,"accion_id","id");
    }

    public function document()
    {
        return $this->belongsTo(Document::class,"documento_id","id");
    }
}
