<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionDocument extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Reu_document";
    public $timestamps = true;

    protected $fillable = [
        'area_id',
        'reu_temas_id',
        'documento_id',
        'estado',
    ];

    public function area(){
        return $this->belongsTo(User::class,"area_id","id");
    }

    public function theme(){
        return $this->belongsTo(Theme::class,"reu_tema_id","id");
    }

    public function document(){
        return $this->belongsTo(Document::class,"documento_id","id");
    }
}
