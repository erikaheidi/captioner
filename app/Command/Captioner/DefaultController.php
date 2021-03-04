<?php

namespace App\Command\Captioner;

use App\Service\CaptionerService;
use App\Service\LoggerService;
use Minicli\Command\CommandController;
use Minicli\Input;

class DefaultController extends CommandController
{
    public function handle()
    {
        /** @var LoggerService $logger */
        $logger = $this->getApp()->logger;

        /** @var CaptionerService $captioner */
        $captioner = $this->getApp()->captioner;
        $captioner->start();
        $input = new Input('C> ');

        while(true) {
            $line = $input->read();

            if ($line === "exit") {
                return;
            }

            if ($line === "clear") {
                $logger->clear();
                //restart captioner
                $captioner->start();
                continue;
            }

            if ($line === "start") {
                $captioner->start();
                continue;
            }

            $logger->write($line);
            $captioner->write($line);
        }
    }
}