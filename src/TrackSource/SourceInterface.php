<?php


namespace Gpx2Png\TrackSource;

use Gpx2Png\Models\Track;

interface SourceInterface
{
    /**
     * @return Track
     */
    public function getTrack();
}