<?php


namespace Gpx2Png\Models;


class ImageParams
{
    const DEFAULT_WIDTH = 1024;
    const DEFAULT_HEIGHT = 1024;
    const DEFAULT_PADDING = 50;

    public $max_width;
    public $max_height;
    public $padding;

    public function __construct()
    {
        $this->max_width = self::DEFAULT_WIDTH;
        $this->max_height = self::DEFAULT_HEIGHT;
        $this->padding = self::DEFAULT_PADDING;
    }
}