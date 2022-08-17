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
        'usuario_id',
        'fecha',
        'estado',
    ];

    public function userCreator()
    {
        return $this->belongsTo(User::class,"usuario_id","id");
    }

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
        return $this->belongsToMany(Document::class,"t_sgcv_reu_document","reunion_id","documento_id")->withPivot('area_id','estado','created_at');
    }

    public function consolidado_documents(){
        return $this->belongsToMany(Document::class,"t_sgcv_reu_consolidado","reunion_id","documento_id")->withPivot('estado','created_at');
    }
}
