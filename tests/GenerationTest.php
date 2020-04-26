<?php


namespace Gpx2Png\Test;


use Gpx2Png\Gpx2Png;
use Gpx2Png\MapSource\Osm;
use Gpx2Png\Models\ImageParams;
use Gpx2Png\Models\Point;
use Gpx2Png\TrackSource\File;
use PHPUnit\Framework\TestCase;

use ReflectionClass;
use Exception;

class GenerationTest extends TestCase
{
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        try{
            $reflection = new ReflectionClass(get_class($object));
            $method = $reflection->getMethod($methodName);
            $method->setAccessible(true);
            return $method->invokeArgs($object, $parameters);
        }catch (Exception $e){

        }

        return false;
    }

    public function testGenerateFromFile()
    {
        $filepath = __DIR__."/fixtures/strava_track_02.gpx";

        $gpx2png = new Gpx2Png();
        $gpx2png->loadFile($filepath);
        $gpx2png->imageParams->max_width = 1200;
        $gpx2png->imageParams->max_height = 1200;
        $image = $gpx2png->generateImage();

        $image->saveToFile(pathinfo($filepath)['filename'].".png");

        $this->assertFalse($image==false);
    }

    public function testIllegalCustomMapSource()
    {
        $filepath = __DIR__."/fixtures/strava_track_01.gpx";

        $gpx2png = new Gpx2Png();
        $gpx2png->loadFile($filepath);

        try{
            $gpx2png->setMapSourceName('test');
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
        }
    }

    public function testGenerateFromPoints()
    {
        $filepath = __DIR__."/fixtures/strava_track_01.gpx";
        $gpx2png = new Gpx2Png();
        $gpx2png->loadFile($filepath);

        $track = $gpx2png->getTrack();

        unset($gpx2png);
        $gpx2png = new Gpx2Png();
        $points = array();

        foreach ($track->points as $point) {
            $points[] = new Point($point->latitude, $point->longitude);
        }

        $gpx2png->loadPoints($points);
        $image = $gpx2png->generateImage();

        $this->assertFalse($image==false);
    }

    public function testGenerateFromOnePoint()
    {
        $gpx2png = new Gpx2Png();
        $points = array(
            new Point(56.4244756, 47.7069989)
        );

        $gpx2png->loadPoints($points);
        $gpx2png->drawParams->track->endPoint->visible = 0;
        $image = $gpx2png->generateImage();
        $image->saveToFile("test_02.png");

        $this->assertFalse($image==false);
    }

    public function testOsmAutozoomCalc(){
        $filepath = __DIR__."/fixtures/strava_track_01.gpx";

        $source = new File($filepath);
        $track = $source->getTrack();

        $osmSource = new Osm($track, new ImageParams());

        $zoom = $this->invokeMethod($osmSource, 'getAutoZoom');

        $this->assertEquals(16, $zoom, '', 1);
    }

}