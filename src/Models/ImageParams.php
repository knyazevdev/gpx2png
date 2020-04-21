<?php


namespace Gpx2Png\Models;


class ImageParams
{
    const DEFAULT_WIDTH = 1024;
    const DEFAULT_HEIGHT = 1024;

    public $width;
    public $height;

    public function __construct()
    {
        $this->width = self::DEFAULT_WIDTH;
        $this->height = self::DEFAULT_HEIGHT;
    }
}