<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    use HasFactory;
    
    protected $table = "t_sgcv_reuniones";
    public $timestamps = true;

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'estado',
    ];

    public function reunionThemes(){
        return $this->hasMany(ReunionTheme::class,"reunion_id","id");
    }

    public function reunionPresenters(){
        return $this->hasMany(ReunionPresenter::class,"reunion_id","id");
    }

    public function users(){
        return $this->belongsToMany(User::class,"t_sgcv_reu_presentadores","reunion_id","usuario_id");
    }

    public function documents(){
        return $this->belongsToMany(Documents::class,"t_sgcv_reu_document","reunion_id","documento_id");
    }
}
