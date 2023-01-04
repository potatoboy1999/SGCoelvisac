<?php

namespace App\Console\Commands;

use App\Mail\ObjAlert;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAlertEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:sendAlerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will alert users that they have pending actions not finished';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('estado', 1)->get();
        $emails = [];
        foreach($users as $user){
            $emails[] = $user->email;
        }
        if(sizeof($emails) > 0){
            //Mail::to(['alejandrodazaculqui@hotmail.com', 'numbworld1999@gmail.com', 'alejandro@reservhotel.com'])
            Mail::to($emails)
            ->send(new ObjAlert(route('objectives')));
        }
        return ["message"=>"Job finished"];
    }
}
