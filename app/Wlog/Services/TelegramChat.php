<?php 

/** 
 * Created By Lazade
 * At 2018-11-05
*/

namespace App\Wlog\Services;
use Telegram;

class TelegramChat
{
    public function sendMessage($text, $mode)
    {
        $response = Telegram::sendMessage([
            'chat_id' => '411653565', 
            'text' => $text,
            'parse_mode' => $mode
        ]);
    }
}

