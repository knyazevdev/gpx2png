<?php


namespace Gpx2Png\MapSource;


class OsmTopo extends Osm
{
    protected $name = "osm_topo";
    protected $url_template = 'https://[a,b,c].tile.opentopomap.org/{$z}/{$x}/{$y}.png';
}