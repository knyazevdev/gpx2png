<?php


namespace Gpx2Png\MapSource;

use claviska\SimpleImage;
use Gpx2Png\Helper\Common;
use Gpx2Png\Models\MapImage;
use Gpx2Png\Models\Point;
use Gpx2Png\Models\Tile;

use Exception;

class Osm extends TileMapSource {

    const TILE_SIZE = 256;
    const EARTH_EQUATORIAL_CIRCUMFERENCE = 40075016.686;

    protected $url_template;
    protected $name;

    protected function getAutoZoom(){
        $bounds = $this->track->getBounds();
        $maxSideDistance = $bounds->getMaxSideDistance();

        $minTilesNumberOnAxis = ceil(max($this->imageDetailsIndex*$this->imageParams->width, $this->imageDetailsIndex*$this->imageParams->height) / self::TILE_SIZE);

        $distanceForOneTile = $maxSideDistance/$minTilesNumberOnAxis;

        /**
         * https://wiki.openstreetmap.org/wiki/Zoom_levels
         */

        $latitude_rad = deg2rad($bounds->minLat);
        $zoom = floor(log(self::EARTH_EQUATORIAL_CIRCUMFERENCE*cos($latitude_rad) / $distanceForOneTile, 2))    ;

        return $zoom;
    }

    /**
     * @return array[][]
     */
    protected function getTilesSet(){
        if ($this->zoomMode==self::ZOOM_MODE_AUTO){
            $zoom = $this->getAutoZoom();
        }else{
            $zoom = $this->zoomValue;
        }

        $bounds = $this->track->getBounds();
        $tiles = array();

        $tile_from = $this->getTileForPoint(new Point($bounds->maxLat, $bounds->minLon), $zoom);
        $tile_to = $this->getTileForPoint(new Point($bounds->minLat, $bounds->maxLon), $zoom);

        for ($y=$tile_from->y; $y<=$tile_to->y; $y++){
            if (!isset($tiles[$y])){
                $tiles[$y] = array();
            }
            for ($x=$tile_from->x; $x<=$tile_to->x; $x++){
                $tiles[$y][$x] = new Tile($x, $y, $zoom);
            }
        }

        return $tiles;
    }

    protected function getTileForPoint(Point $point, $zoom){
        $tileX = (int)floor((($point->longitude + 180) / 360) * (pow(2, $zoom)));
        $tileY = (int)floor(
            (1 - log(tan(deg2rad($point->latitude)) + 1 / cos(deg2rad($point->latitude))) / M_PI) / 2 * (pow(2, $zoom))
        );

        return new Tile($tileX, $tileY, $zoom);
    }

    protected function getPointByTile(Tile $tile){
        $lat = rad2deg(atan(sinh(M_PI * (1 - 2 * $tile->y / pow(2, $tile->z)))));
        $lon = $tile->x / pow(2, $tile->z) * 360.0 - 180.0;

        return new Point($lat, $lon);
    }

    protected function getTilePath(Tile $tile){
        return $this->tilesDir.'/'.$this->name.'/'.$tile->z.'/'.$tile->x.'/'.$tile->y.'.png';
    }

    protected function getTileFile(Tile $tile){
        $tilePath = $this->getTilePath($tile);
        if (!file_exists($tilePath)){
            try{
                Common::downloadImage($tile->getUrl($this->url_template), $tilePath);
            }catch (Exception $e){

            }
        }

        return $tilePath;
    }

    /**
     * @param  array[][] $tiles_set
     * @return MapImage
     * @throws Exception
     */
    public function mergeTilesSet($tiles_set){
        $tiles_set = array_values($tiles_set);
        foreach ($tiles_set as $i => $tile_line) {
            $tiles_set[$i] = array_values($tile_line);
        }
        $tiles_width = count($tiles_set[0]);
        $tiles_height = count($tiles_set);

        $mapImage = new MapImage();

        $img = new SimpleImage();
        $img->fromNew($tiles_width*self::TILE_SIZE, $tiles_height*self::TILE_SIZE, '#FFF');

        foreach ($tiles_set as $i => $tile_line) {
            foreach ($tile_line as $k => $tile) {
                $tilePath = $this->getTileFile($tile);
                if (file_exists($tilePath)){
                    $img->overlay($tilePath, 'top left', 1, $k*256, $i*256);
                }
            }
        }

        if ($this->imageDetailsIndex>1){
            $img->resize($img->getWidth()/$this->imageDetailsIndex);
        }

        $mapImage->palette = $img;

        $left_top_tile = $tiles_set[0][0];
        $right_bottom_tile = $tiles_set[count($tiles_set)-1][count($tiles_set[0])-1];

        $lat_direction_sign = $right_bottom_tile->y > $left_top_tile->y ? 1 : -1;
        $lon_direction_sign = $right_bottom_tile->x > $left_top_tile->x ? 1 : -1;

        $mapImage->setBoundPoints(
            $this->getPointByTile($left_top_tile),
            $this->getPointByTile(new Tile($right_bottom_tile->x+$lon_direction_sign*1, $right_bottom_tile->y+$lat_direction_sign*1, $right_bottom_tile->z))
        );

        return $mapImage;
    }

    public function getBaseImage()
    {
        try{
            $image = $this->mergeTilesSet($this->getTilesSet());
        }catch (Exception $e){

        }

        return $image;
    }
}