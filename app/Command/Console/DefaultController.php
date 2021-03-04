<?php

namespace App\Command\Console;

use Minicli\Command\CommandController;
use Minicli\Input;

class DefaultController extends CommandController
{
    public function handle()
    {
        $input = new Input('S> ');

        while(true) {
            $line = $input->read();
            //break line down
            $args = explode(' ', $line);
            $command = $args[0];
            $subcommand = $args[1] ?? "default";

            if ($command === "exit") {
                return;
            }

            $this->runCommand($command, $subcommand, $args);
        }
    }

    protected function runCommand($command, $subcommand, array $additional_args = [])
    {
        $this->getApp()->runCommand(array_merge([
            'streamaru',
            $command,
            $subcommand
            ], $additional_args
        ));
    }
}