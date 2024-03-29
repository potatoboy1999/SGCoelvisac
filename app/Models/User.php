<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "t_sgcv_usuarios";
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'posicion_id',
        'is_admin',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function position(){
        return $this->belongsTo(Position::class,'posicion_id','id');
    }

    public function travel_schedules(){
        return $this->hasMany(TravelSchedule::class,'usuario_id','id');
    }

    public function schedules_validation_one(){
        return $this->hasMany(TravelSchedule::class,'val_uno_por','id');
    }

    public function schedules_validation_two(){
        return $this->hasMany(TravelSchedule::class,'val_dos_por','id');
    }

    public function comments(){
        return $this->hasMany(Comment::class,'usuario_id','id');
    }

    public function mailsAsSender(){
        return $this->hasMany(MailSent::class,"usr_remitente","id");
    }

    public function mailsAsReceiver(){
        return $this->hasMany(MailSent::class,"usr_receptor","id");
    }

    public function profiles(){
        return $this->belongsToMany(Profile::class,"t_sgcv_usuario_perfil","usuario_id","perfil_id");
    }

    public function activities(){
        return $this->belongsToMany(Activity::class,'comments','users_id','activities_id');
    }

    public function reunions(){
        return $this->belongsToMany(Reunion::class,'reunion_presenters','user_id','reunion_id');
    }
}
