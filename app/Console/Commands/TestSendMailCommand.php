<?php

namespace App\Console\Commands;

use App\Mail\TestSendMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-send-mail-command {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Mail::to($this->argument('email'))->send(new TestSendMail());    
    }
}
