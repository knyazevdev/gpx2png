<?php

namespace Gpx2Png;

use Gpx2Png\MapSource\Osm;
use Gpx2Png\Models\DrawParams;
use Gpx2Png\Models\ImageParams;
use Gpx2Png\Models\Result;
use Gpx2Png\Models\Track;

use InvalidArgumentException;
use RuntimeException;

class Gpx2Png
{
    private $mapSourceName = "osm_base";

    /**
     * @var Track
     */
    private $track;

    /**
     * @var Osm
     */
    public $mapSource;

    /**
     * @var ImageParams
     */
    private $imageParams;

    /**
     * @var DrawParams
     */
    public $drawParams;

    public function __construct(){
        $this->imageParams = new ImageParams();
        $this->drawParams = new DrawParams();
    }

    public function setMapSourceName($name){
        $this->mapSourceName = $name;
        $this->initMapSource();
    }

    private function initMapSource(){
        if (preg_match_all("/_(\w{1})/", $this->mapSourceName, $matches)){
            foreach ($matches[0] as $i => $item) {
                $this->mapSourceName = str_replace("_".$matches[1][$i], strtoupper($matches[1][$i]), $this->mapSourceName);
            }
        }
        $name = __NAMESPACE__."\\MapSource\\".ucfirst($this->mapSourceName);
        $this->mapSource = new $name($this->track, $this->imageParams);
    }

    public function loadFile($filepath){
        if (!file_exists($filepath)){
            throw new RuntimeException("File is not found");
        }

        $source = new TrackSource\File($filepath);
        $this->track = $source->getTrack();
    }

    public function getTrack(){
        return $this->track;
    }

    public function loadPoints($points)
    {
        if (count($points) == 0){
            throw new InvalidArgumentException('Invalid points format');
        }

        $track = new Track();
        $track->points = $points;
        $this->track = $track;
    }

    public function generateImage(){
        if (!$this->mapSource){
            $this->initMapSource();
        }

        $image = $this->mapSource->getBaseImage();

        if (!$image){
            throw new RuntimeException('Cant generate base image');
        }

        $image->drawTrack($this->track, $this->drawParams->track);

        return new Result($image);
    }
}
