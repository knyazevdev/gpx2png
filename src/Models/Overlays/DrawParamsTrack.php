<?php


namespace Gpx2Png\Models\Overlays;


class DrawParamsTrack
{
    public $color = "red";
    public $width = 3;
    public $opacity = 0.8;
    public $startPoint;
    public $endPoint;
    public $distanceLabelsFrequency = 1000;
    public $distanceLabel;

    public function __construct()
    {
        $this->startPoint = new DrawParamsPoint();
        $this->startPoint->setTemplate(DrawParamsPoint::TPL_START_POINT);

        $this->endPoint = new DrawParamsPoint();
        $this->endPoint->setTemplate(DrawParamsPoint::TPL_END_POINT);

        $this->distanceLabel = new DrawParamsLabel();
        $this->distanceLabel->setTemplate(DrawParamsLabel::TPL_KM_LABEL);
    }
}