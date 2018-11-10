<?php 

/** 
 * Created By Lazade
 * At 2018-11-05
*/

namespace App\Wlog\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use App\Wlog\Services\TelegramChat;

class TelegramChatHandler extends AbstractProcessingHandler
{

    public function __construct($level = Logger::NOTICE, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        $telegram = new TelegramChat();
        $postData = [
            'notification' => 'Laravel Error Log',
            'overview' => $record['datetime']->format('Y-m-d H:i:s') . ' - ' . $record["level"] . ' - ' . $record["level_name"],
            'text' => $record['message'],
        ];
        $post = json_encode($postData);
        $telegram->sendMessage($post, 'HTML');
    }
}