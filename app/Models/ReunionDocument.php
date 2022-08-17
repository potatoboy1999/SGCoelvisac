<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionDocument extends Model
{
    use HasFactory;

    protected $table = "t_sgcv_reu_document";
    public $timestamps = true;

    protected $fillable = [
        'area_id',
        'reunion_id',
        'documento_id',
        'estado',
    ];

    public function area(){
        return $this->belongsTo(User::class,"area_id","id");
    }

    public function reunion(){
        return $this->belongsTo(Reunion::class,"reunion_id","id");
    }

    public function document(){
        return $this->belongsTo(Document::class,"documento_id","id");
    }
}
