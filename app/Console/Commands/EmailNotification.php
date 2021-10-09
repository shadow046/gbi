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
        $Ticket = Ticket::query()->whereNull('Notified')->first();
        if ($Ticket) {
            $config = array(
                'driver'     => \config('mailconf.driver'),
                'host'       => \config('mailconf.host'),
                'port'       => \config('mailconf.port'),
                'from'       => \config('mailconf.from'),
                'encryption' => \config('mailconf.encryption'),
                'username'   => \config('mailconf.username'),
                'password'   => \config('mailconf.password'),
            );
            Config::set('mail', $config);
            Mail::send('responder', ['email'=>'jerome.lopez.aks2018@gmail.com'],function( $message){ 
                $message->to('jerome.lopez.aks2018@gmail.com', 'Jerome Lopez')->subject('Account Details'); 
                $message->from('no-reply@apsoft.com.ph', 'BSMS support');
                $message->bcc('jolopez@ideaserv.com.ph','emorej046@gmail.com');
            });
        }
        $this->info('Successfully sent email');
    }
}
