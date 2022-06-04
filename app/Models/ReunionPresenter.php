<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionPresenter extends Model
{
    use HasFactory;

    protected $table = "T_SGCV_Reu_presentadores";
    public $timestamps = true;

    protected $fillable = [
        'usuario_id',
        'reunion_id',
        'estado',
    ];

    public function user(){
        return $this->belongsTo(Users::class,"usuario_id","id");
    }

    public function reunion(){
        return $this->belongsTo(Reunion::class,"reunion_id","id");
    }
}
