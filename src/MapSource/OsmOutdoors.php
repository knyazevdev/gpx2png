<?php


namespace Gpx2Png\MapSource;


class OsmOutdoors extends Osm
{
    protected $name = "osm_outdoors";
    protected $url_template = 'https://tile.thunderforest.com/outdoors/{$z}/{$x}/{$y}.png?apikey=9b81b0baeee04858b5ecaee9d8796814';
}