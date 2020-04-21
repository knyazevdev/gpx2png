<?php


namespace Gpx2Png\Models;


use claviska\SimpleImage;
use Exception;
use Gpx2Png\Models\Overlays\DrawParamsLabel;
use Gpx2Png\Models\Overlays\DrawParamsPoint;
use Gpx2Png\Models\Overlays\DrawParamsTrack;

class MapImage
{
    /**
     * @var SimpleImage
     */
    public $palette;

    /**
     * @var Point
     */
    private $leftTopPoint;

    /**
     * @var Point
     */
    private $rightBottomPoint;

    private $pixelDistance;

    private function getPointXY(Point $point)
    {
        $pointXY = new PointXY();

        $projectedPoint = new Point($point->latitude, $this->leftTopPoint->longitude);

        $pointXY->y = round($projectedPoint->getDistanceToPoint($this->leftTopPoint) / $this->pixelDistance);

        $projectedPoint = new Point($this->leftTopPoint->latitude, $point->longitude);
        $pointXY->x = round($projectedPoint->getDistanceToPoint($this->leftTopPoint) / $this->pixelDistance);

        return $pointXY;
    }

    private function updatePixelLatLonParams(){
        $paletteWidth = $this->palette->getWidth();
        $paletteDistance = $this->leftTopPoint->getDistanceToPoint(new Point($this->leftTopPoint->latitude, $this->rightBottomPoint->longitude));

        $this->pixelDistance = $paletteDistance / $paletteWidth;
    }

    public function setBoundPoints(Point $leftTopPoint, Point $rightBottomPoint){
        $this->leftTopPoint = $leftTopPoint;
        $this->rightBottomPoint = $rightBottomPoint;

        $this->updatePixelLatLonParams();
    }

    private function createOverlayPalette(){
        $overlayPalette = new SimpleImage();
        return $overlayPalette->fromNew($this->palette->getWidth(), $this->palette->getHeight());
    }


    public function drawTrack(Track $track, DrawParamsTrack $drawParams)
    {
        $prevPoint = $track->getFirstPoint();
        $overlayPalette = $this->createOverlayPalette();

        foreach ($track->points as $i => $point) {
            $distanceToPrevPoint = $point->getDistanceToPoint($prevPoint);

            if ($distanceToPrevPoint >=50){
                $xyFrom = $this->getPointXY($prevPoint);
                $xyTo = $this->getPointXY($point);
                $overlayPalette->line($xyFrom->x, $xyFrom->y, $xyTo->x, $xyTo->y, $drawParams->color, $drawParams->width);

                $prevPoint = $point;
            }
        }

        $this->palette->overlay($overlayPalette, 'center', $drawParams->opacity);

        if ($drawParams->distanceLabelsFrequency>0){
            $prevPoint = $track->getFirstPoint();
            $overlayPalette = $this->createOverlayPalette();

            foreach ($track->points as $i => $point) {

                $distanceToPrevPoint = $point->getDistanceToPoint($prevPoint);
                if ($drawParams->distanceLabelsFrequency>0){
                    if (!$point->distance){
                        $point->distance = $prevPoint->distance + $distanceToPrevPoint;
                    }
                }

                if ($distanceToPrevPoint >=50){
                    $xyTo = $this->getPointXY($point);

                    $distanceLabelsFrequency = $drawParams->distanceLabelsFrequency;
                    $kmLabelValue = floor($point->distance/ $distanceLabelsFrequency);
                    if ($point->distance>$kmLabelValue*$distanceLabelsFrequency && $prevPoint->distance<$kmLabelValue*$distanceLabelsFrequency){
                        $overlayPalette->ellipse($xyTo->x, $xyTo->y, 2 * $drawParams->width, 2 * $drawParams->width, $drawParams->distanceLabel->text_color, 'filled');
                        $this->drawLabel($point, $kmLabelValue*$distanceLabelsFrequency/1000, $drawParams->distanceLabel);
                    }

                    $prevPoint = $point;
                }
            }
            $this->palette->overlay($overlayPalette, 'center', $drawParams->opacity);
        }

        if ($drawParams->startPoint->visible){
            $this->drawPoint($track->getFirstPoint(), $drawParams->startPoint);
        }

        if ($drawParams->endPoint->visible){
            $this->drawPoint($track->getLastPoint(), $drawParams->endPoint);
        }

    }

    public function drawPoint(Point $point, DrawParamsPoint $drawParams)
    {
        $overlayPalette = $this->createOverlayPalette();

        $xy = $this->getPointXY($point);

        if ($drawParams->border_width){
            $size = (2 * $drawParams->radius+$drawParams->border_width);
            $overlayPalette->ellipse($xy->x, $xy->y, $size, $size, $drawParams->border_color, 'filled');
        }

        $size = 2 * $drawParams->radius;
        $overlayPalette->ellipse($xy->x, $xy->y, $size, $size, $drawParams->color, 'filled');

        $this->palette->overlay($overlayPalette, 'center', $drawParams->opacity);
    }

    public function drawLabel(Point $point, $text, DrawParamsLabel $drawParams)
    {
        $overlayPalette = $this->createOverlayPalette();

        $xy = $this->getPointXY($point);

        try{
            $overlayPalette->text($text, array(
                'size'=>$drawParams->text_size,
                'color'=>$drawParams->text_color,
                'fontFile'=>__DIR__.'/../files/fonts/ptsans-webfont.ttf',
                'anchor'=>'top left',
                'xOffset'=>$xy->x+10,
                'yOffset'=>$xy->y+10
            ), $boundary);

            if ($drawParams->border_width){
                $overlayPalette->roundedRectangle($boundary['x1']-6, $boundary['y1']-6, $boundary['x2']+10, $boundary['y2']+4, 3, $drawParams->border_color, $drawParams->border_width);
            }
        }catch (Exception $e){

        }


        $this->palette->overlay($overlayPalette, 'center', $drawParams->opacity);
    }


}