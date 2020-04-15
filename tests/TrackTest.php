<?php

namespace Gpx2Png\Test;

use Gpx2Png\TrackSource\File;
use PHPUnit\Framework\TestCase;

class TrackTest extends TestCase{

    public function test_bounds()
    {
        $this->assertTrue(true);
    }

    public function test_file_loading()
    {
        $filepath = __DIR__."/fixtures/strava_track_01.gpx";
        $source = new File($filepath);
        $track = $source->getTrack();
        $points = $track->points;

        $this->assertEquals(71, $points, "", 10);
    }
}
