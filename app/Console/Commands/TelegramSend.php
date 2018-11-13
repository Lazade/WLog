<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Wlog\Services\TelegramChat;

class TelegramSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegramsend {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message to Telegram via telegram-bot';

    // 
    protected $telegram;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->telegram = new TelegramChat();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $message = $this->argument('message');
        $this->telegram->sendMessage($message, 'HTML');
    }
}
