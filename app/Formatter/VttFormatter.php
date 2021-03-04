<?php


namespace App\Formatter;


use DateTime;

class VttFormatter implements SubtitleFormatterInterface
{
    public function format($index, $caption, DateTime $started_at, $default_duration)
    {
        $current_time = new DateTime();
        $current_time_diff = $current_time->diff($started_at);

        $final_time = new DateTime("+ $default_duration seconds");
        $final_time_diff = $final_time->diff($started_at);
        $content_format = "%s --> %s\n%s\n\n";

        return sprintf($content_format, $current_time_diff->format('%H:%I:%S,000'), $final_time_diff->format('%H:%I:%S,000'), $caption);
    }
}