<?php


namespace Gpx2Png\MapSource;

use Gpx2Png\Models\ImageParams;
use Gpx2Png\Models\Track;

abstract class TileMapSource implements MapSourceInterface
{

    const ZOOM_MODE_AUTO = 'auto';
    const ZOOM_MODE_MANUAL = 'manual';

    public $zoomMode;
    public $zoomValue;
    public $imageDetailsIndex = 1;

    protected $tilesDir;

    /**
     * @var Track
     */
    protected $track;
    /**
     * @var ImageParams
     */
    protected $imageParams;

    public function __construct(Track $track, ImageParams $imageParams)
    {
        $this->track = $track;
        $this->imageParams = $imageParams;
        $this->setZoom(0);
        $this->setTilesDirectory(sys_get_temp_dir().'/tiles');
    }

    public function setImageDetailsIndex($index){
        $this->imageDetailsIndex = $index;
    }

    public function setTilesDirectory($directory)
    {
        $this->tilesDir = $directory;
    }

    public function setZoom($zoom_value){
        if ($zoom_value==0){
            $this->zoomMode = self::ZOOM_MODE_AUTO;
        }else{
            $this->zoomMode = self::ZOOM_MODE_MANUAL;
        }

        $this->zoomValue = $zoom_value;
    }

    public function getBaseImage()
    {

    }
}