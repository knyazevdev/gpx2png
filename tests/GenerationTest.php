<?php


namespace Gpx2Png\Test;


use Gpx2Png\Gpx2Png;
use Gpx2Png\MapSource\Osm;
use Gpx2Png\Models\ImageParams;
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
        $filepath = __DIR__."/fixtures/strava_track_03.gpx";

        $gpx2png = new Gpx2Png();
        $gpx2png->loadFile($filepath);
        $image = $gpx2png->generateImage();

        $this->assertFalse($image==false);
    }

    public function testOsmAutozoomCalc(){
        $filepath = __DIR__."/fixtures/strava_track_01.gpx";

        $source = new File($filepath);
        $track = $source->getTrack();

        $osmSource = new Osm($track, new ImageParams());

        $zoom = $this->invokeMethod($osmSource, 'getAutoZoom');

        $this->assertEquals(15, $zoom);
    }

}