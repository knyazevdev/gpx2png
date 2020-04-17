<?php


namespace Gpx2Png\TrackSource;

use Gpx2Png\Models\Point;
use Gpx2Png\Models\Track;
use phpGPX\Helpers\GeoHelper;
use phpGPX\phpGPX;

class File implements SourceInterface{
    private $filepath;
    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    public function getTrack()
    {
        $track = new Track();

        $phpGPX = new phpGPX();
        $file = $phpGPX->load($this->filepath);
        $points = $file->tracks[0]->getPoints();

        $prev_point = null;
        $total_distance = 0;
        foreach ($points as $point) {
            $track_point = new Point($point->latitude, $point->longitude, $point->time->getTimestamp());
            $track_point->elevation = $point->elevation;

            $total_distance += !is_null($prev_point) ? round(GeoHelper::getDistance($point, $prev_point)) : 0;
            $track_point->distance = $total_distance;
            $track->points[] = $track_point;

            $prev_point = $point;
        }

        return $track;
    }
}