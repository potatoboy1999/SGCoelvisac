<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectiveUsers extends Model
{
    use HasFactory;
    
    protected $table = "t_sgcv_obj_user";
    public $timestamps = true;

    protected $fillable = [
        'objetivo_id',
        'usuario_id',
        'estado',
    ];

    public function stratObjective()
    {
        return $this->belongsTo(ObjectiveUsers::class,"objetivo_id","id");
    }

    public function user()
    {
        return $this->belongsTo(User::class,"usuario_id","id");
    }
}
