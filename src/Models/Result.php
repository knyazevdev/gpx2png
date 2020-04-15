<?php


namespace Gpx2Png\Models;

use RuntimeException;
use Exception;

class Result
{
    /**
     * @var MapImage
     */
    private $image;

    public function __construct($image)
    {
        $this->image = $image;
    }

    public function saveToFile($filename)
    {
        try{
            $this->image->palette->toFile($filename);
        }catch (Exception $e){
            throw new RuntimeException($e->getMessage());
        }
    }

    public function output()
    {
        $this->image->palette->toScreen();
    }

    public function download($filename)
    {
        $this->image->palette->toDownload($filename);
    }
}