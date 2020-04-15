<?php


namespace Gpx2Png\Models;

class Bounds
{
    public $minLat;
    public $maxLat;
    public $minLon;
    public $maxLon;

    private $inited = false;
    public function isInited(){
        return $this->inited==true;
    }

    public function setInited(){
        $this->inited=true;
    }

    /**
     * @param Point[] $points
     */
    public function setByPointsSet($points)
    {
        foreach ($points as $point) {
            if (!$this->isInited()){
                $this->maxLat = $point->latitude;
                $this->minLat = $point->latitude;
                $this->minLon = $point->longitude;
                $this->maxLon = $point->longitude;

                $this->setInited();
            }else{
                if ($this->maxLat < $point->latitude){
                    $this->maxLat = $point->latitude;
                }
                if ($this->minLat > $point->latitude){
                    $this->minLat = $point->latitude;
                }

                if ($this->maxLon < $point->longitude){
                    $this->maxLon = $point->longitude;
                }
                if ($this->minLon > $point->longitude){
                    $this->minLon = $point->longitude;
                }
            }
        }
    }

    public function getMaxSideDistance()
    {
        $point1 = new Point($this->minLat, $this->minLon);
        $point2 = new Point($this->maxLat, $this->minLon);
        $distance_1 = $point1->getDistanceToPoint($point2);

        $point1 = new Point($this->minLat, $this->minLon);
        $point2 = new Point($this->minLat, $this->maxLon);
        $distance_2 = $point1->getDistanceToPoint($point2);

        return round(max($distance_1, $distance_2)) ;
    }
}