<?php


namespace Gpx2Png\Models\Overlays;


class DrawParamsLabel
{
    const TPL_KM_LABEL = "km_label";

    public $text_size;
    public $text_color;
    public $opacity;
    public $border_color;
    public $border_width;

    public function setTemplate($template){
        switch ($template){
            case self::TPL_KM_LABEL:
                $this->text_color = "black";
                $this->text_size = 12;
                $this->opacity = 0.9;
                $this->border_color = "black";
                $this->bg_color = "white";
                $this->border_width = 0;
                break;
        }
    }
}