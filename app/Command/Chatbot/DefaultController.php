<?php

namespace App\Command\Chatbot;

use App\Service\ChatbotService;
use App\Service\NotificationService;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    /** @var string[]  Accounts to not show in your screen (this won't mute them in the Twitch chat) */
    protected $muted_accounts = [
    ];

    /** @var string[]  */
    protected $log_messages = [
        'pretzelrocks' // messages are not shown on screen, instead saved to a notifications.txt  file that you can load from OBS
    ];

    public function handle()
    {
        $this->getPrinter()->info("Starting Minichat for Twitch...");

        /** @var ChatbotService $chatbot */
        $chatbot = $this->getApp()->chatbot;
        $client = $chatbot->getClient();

        if (!$client->connect()) {
            $this->getPrinter()->error("It was not possible to connect.");
        }

        while (true) {
            $content = $client->read();

            if ($content === null) {
                sleep(5);
                continue;
            }

            if (in_array($content['nick'], $this->muted_accounts)) {
                continue;
            }

            if ($content['type'] === 'command') {
                $command = $content['command'];

                if ($chatbot->botHasCommand($command)) {
                    $chatbot->runCommand($command, ['author' => $content['nick'], 'message' => $content['message']]);
                    continue;
                }
            }

            /** @var NotificationService $notifications */
            $notifications = $this->getApp()->notifications;

            if ($notifications) {
                if (in_array($content['nick'], $this->log_messages)) {
                    //log message instead of showing on screen
                    $notifications->new($content['message']);
                    continue;
                }
            }

            $this->printMessage($content['nick'], $content['message']);
        }
    }

    protected function printMessage($nick, $message)
    {
        $style_nick = "info";

        if ($nick === $this->getApp()->config->twitch_user) {
            $style_nick = "info_alt";
        }

        $this->getPrinter()->out($nick, $style_nick);
        $this->getPrinter()->out(': ');
        $this->getPrinter()->out($message);
        $this->getPrinter()->newline();
    }
}