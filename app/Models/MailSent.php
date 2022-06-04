<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailSent extends Model
{
    use HasFactory;
    protected $table = "T_SGCV_Correos";
    public $timestamps = true;

    protected $fillable = [
        'usr_remitente',
        'usr_receptor',
        'agenda_viaje_id',
        'estado',
    ];

    public function userSender(){
        return $this->belongsTo(User::class,"usr_remitente","id");
    }

    public function userReceiver(){
        return $this->belongsTo(User::class,"usr_receptor","id");
    }

    public function travelSchedule(){
        return $this->belongsTo(TravelSchedule::class,"agenda_viaje_id", "id");
    }
}
