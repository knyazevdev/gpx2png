<?php


namespace Gpx2Png\Models\Overlays;


class DrawParamsPoint
{
    const TPL_START_POINT = "start_point";
    const TPL_END_POINT = "end_point";
    const TPL_LIVE_POINT = "live_point";

    public $visible = 1;
    public $radius;
    public $color;
    public $border_width;
    public $border_color;
    public $opacity;

    public function setTemplate($template){
        switch ($template){
            case self::TPL_START_POINT:
                $this->radius = 8;
                $this->color = "green";
                $this->border_width = 3;
                $this->border_color = "#FFF";
                $this->opacity = 0.8;
                break;
            case self::TPL_END_POINT:
                $this->radius = 8;
                $this->color = "black";
                $this->border_width = 3;
                $this->border_color = "#FFF";
                $this->opacity = 0.8;
                break;
            case self::TPL_LIVE_POINT:
                $this->radius = 8;
                $this->color = "lightblue";
                $this->border_width = 3;
                $this->border_color = "#FFF";
                $this->opacity = 0.4;
                break;
        }
    }
}