<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionConsolidado extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_reu_consolidado";
    public $timestamps = true;

    protected $fillable = [
        'reunion_id',
        'documento_id',
        'estado',
    ];

    public function reunion(){
        return $this->belongsTo(Reunion::class,"reunion_id","id");
    }

    public function document(){
        return $this->belongsTo(Document::class,"documento_id","id");
    }
}
