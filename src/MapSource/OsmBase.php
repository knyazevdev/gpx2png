<?php


namespace Gpx2Png\MapSource;


class OsmBase extends Osm
{
    protected $name = "osm_base";
    protected $url_template = 'https://[a].tile.openstreetmap.org/{$z}/{$x}/{$y}.png';
}