<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Mail;
use Config;
class EmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a ticket number via email';

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
        $Ticket = Ticket::query()
            ->join('Data', 'Code', 'StoreCode')
            ->whereNotNull('Email')
            ->where('Notified', 'No')
            ->where('TaskStatus', '!=', 'Submitted')
            ->whereNotNull('StoreCode')
            ->first();
        if ($Ticket && $Ticket->Email) {
            $config = array(
                'driver'     => \config('mailconf.driver'),
                'host'       => \config('mailconf.host'),
                'port'       => \config('mailconf.port'),
                'from'       => \config('mailconf.from'),
                'encryption' => \config('mailconf.encryption'),
                'username'   => \config('mailconf.username'),
                'password'   => \config('mailconf.password')
            );
            Config::set('mail', $config);
            Mail::send('responder', [
                'Store'=>$Ticket->ContactPerson,
                'Ticket'=>$Ticket->TaskNumber
            ],
            function( $message) use ($Ticket){ 
                $message->to($Ticket->Email, 'Goldilocks '.$Ticket->Store_Name)->subject($Ticket->TaskNumber); 
                $message->from('noreply@apsoft.com.ph', 'NO REPLY');
                $message->bcc('jolopez@ideaserv.com.ph','jerome.lopez.aks2018@gmail.com');
            });
            Ticket::where('TaskNumber', $Ticket->TaskNumber)->update(['Notified'=>'yes']);
        }
        $this->info('Successfully sent email');
    }
}
