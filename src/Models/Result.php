<?php


namespace Gpx2Png\Models;

use RuntimeException;
use Exception;

class Result
{
    /**
     * @var MapImage
     */
    public $image;

    public function __construct($image)
    {
        $this->image = $image;
    }

    private function prepare(){

    }

    public function saveToFile($filename)
    {
        try{
            $this->prepare();
            $this->image->palette->toFile($filename);
        }catch (Exception $e){
            throw new RuntimeException($e->getMessage());
        }
    }

    public function output()
    {
        $this->prepare();
        $this->image->palette->toScreen();
    }

    public function download($filename)
    {
        $this->prepare();
        $this->image->palette->toDownload($filename);
    }

    public function data()
    {
        $this->prepare();
        return $this->image->palette->toString();
    }
}