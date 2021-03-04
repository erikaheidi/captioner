<?php

namespace App\Service;

use App\Formatter\SrtFormatter;
use App\Formatter\SubtitleFormatterInterface;
use App\Formatter\VttFormatter;
use Minicli\App;
use DateTime;

class CaptionerService extends LoggerService
{
    protected $captions_path;

    protected $format = 'srt';

    protected $default_duration = 2;

    protected $caption_index = 1;

    protected $started_at;

    protected $formatters = [];

    public function __construct() {
        $this->formatters = [
            'srt' => new SrtFormatter(),
            'vtt' => new VttFormatter(),
        ];
    }

    public function load(App $app)
    {
        $this->captions_path = $app->config->captions_path ?? __DIR__ . '/../../var/captioner';
        $this->file = $this->captions_path . '/log.srt';

        if ($app->config->has('caption_format')) {
            $this->format = $app->config->caption_format;
        }
    }

    /**
     * @return SubtitleFormatterInterface
     */
    public function getFormatter()
    {
        return isset($this->formatters[$this->format]) ? $this->formatters[$this->format] : $this->formatters['srt'];
    }

    /**
     * Saves current timestamp as starting point to position captions
     */
    public function start()
    {
        $this->started_at = new DateTime();
        $this->caption_index = 1;
        $this->file = $this->getNewCaptionFile();
    }


    public function getNewCaptionFile()
    {
        return $this->captions_path . '/' . date('Y-m-d-H:i:s') . '_session.' . $this->format;
    }

    public function write($content)
    {
        $formatter = $this->getFormatter();
        parent::write($formatter->format($this->caption_index, $content, $this->started_at, $this->default_duration));

        $this->caption_index++;
    }
}