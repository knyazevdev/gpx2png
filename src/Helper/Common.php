<?php


namespace Gpx2Png\Helper;

use RuntimeException;

class Common
{
    public static function downloadImage($url, $target)
    {
        $userAgent = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36';

        $opts = array('http'=>array('header'=>"User-Agent: $userAgent\r\n"));
        $context = stream_context_create($opts);
        $getContents = file_get_contents("{$url}", false, $context);

        if ($getContents){
            self::createDirectory(dirname($target));
            file_put_contents($target, $getContents);
        }else{
            throw new RuntimeException('Can not download image '.$url);
        }
    }

    public static function createDirectory($path, $mode=0777){
        if (!is_dir(dirname($path))){
            self::createDirectory(dirname($path), $mode);
        }

        if (!is_dir($path)){
            $res = @mkdir($path, $mode);
            chmod($path, $mode);
            return $res;
        }else{
            return true;
        }
    }
}