<?php

namespace App\Service;

use Minicli\App;
use Minicli\ServiceInterface;
use DateTime;

class LoggerService implements ServiceInterface
{
    protected $file;

    public function load(App $app)
    {
        $this->file = $app->config->log_file ?? __DIR__ . '/../../var/data/log.txt';
    }

    public function getResource()
    {
        return fopen($this->file, "a+");
    }

    public function write($content)
    {
        $resource = $this->getResource();
        fwrite($resource, $content . "\n");
        fclose($resource);
    }

    public function clear()
    {
        $file = fopen($this->file, "w+");
        fwrite($file, '');
        fclose($file);
    }
}