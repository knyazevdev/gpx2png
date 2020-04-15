<?php


namespace Gpx2Png\MapSource;


class OsmHiking extends Osm
{
    protected $name = "osm_hiking";
    protected $url_template = 'https://maps.refuges.info/hiking/{$z}/{$x}/{$y}.png';
}