<?php


namespace Gpx2Png\Models;


class ImageParams
{
    const DEFAULT_WIDTH = 1024;
    const DEFAULT_HEIGHT = 1024;
    const MULTIPLE_INDEX = 2;

    public $width;
    public $height;

    public function __construct()
    {
        $this->width = self::MULTIPLE_INDEX*self::DEFAULT_WIDTH;
        $this->height = self::MULTIPLE_INDEX*self::DEFAULT_HEIGHT;
    }
}