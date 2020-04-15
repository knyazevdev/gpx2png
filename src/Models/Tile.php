<?php


namespace Gpx2Png\Models;


class Tile
{
    public $x;
    public $y;
    public $z;

    public function __construct($x, $y, $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getUrl($template){
        $url = $template;
        if (preg_match_all("/\[([\w,]+)]/", $url, $matches)){
            foreach ($matches[0] as $i => $item) {
                $options = explode(",", $matches[1][$i]);
                $url = str_replace($matches[0][$i], $options[mt_rand(0, count($options)-1)], $url);
            }
        }

        $url = str_replace('{$z}', $this->z, $url);
        $url = str_replace('{$y}', $this->y, $url);
        $url = str_replace('{$x}', $this->x, $url);

        return $url;
    }
}