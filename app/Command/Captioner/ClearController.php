<?php

namespace App\Command\Captioner;

use Minicli\Command\CommandController;

class ClearController extends CommandController
{
    public function handle()
    {
        $config_file = $this->getApp()->config->caption_file;

        if (is_file($config_file)) {
            $file = fopen($config_file, 'w+');
            fwrite($file, "\n");
            fclose($file);
        }

        $this->getApp()->getPrinter()->success("Log cleaned.");
    }
}