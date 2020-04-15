<?php


namespace Gpx2Png\Models;


use Gpx2Png\Models\Overlays\DrawParamsTrack;

class DrawParams
{
    /**
     * @var DrawParamsTrack
     */
    public $track;

    public function __construct()
    {
        $this->track = new DrawParamsTrack();
    }
}