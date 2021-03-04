<?php

namespace App\Formatter;

use \DateTime;

interface SubtitleFormatterInterface
{
    /**
     * @param $index
     * @param $caption
     * @param DateTime $started_at
     * @param $default_duration
     * @return string
     */
    public function format($index, $caption, DateTime $started_at, $default_duration);
}