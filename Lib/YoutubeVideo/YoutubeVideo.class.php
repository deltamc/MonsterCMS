<?php namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');


class YoutubeVideo
{

    static function getPlayer($url, $width, $height)
    {
        preg_match('/v=([A-Za-z0-9-_]{11})/',$url,$vid);

        if(!isset($vid['1']) ||  $vid['1']=='')
        {
            preg_match('/youtu\.be\/([A-Za-z0-9-_]{11})/',$url,$vid);
        }

        $youtube_id = $vid[1];

        if(empty($youtube_id)) return "";

        return '<object style="height: '.$width.'px; width: '.$height.'px">
        <param name="movie" value="http://www.youtube.com/v/'.$youtube_id.'?version=3&feature=player_detailpage">
        <param name="allowFullScreen" value="true"><param name="allowScriptAccess" value="always">
    <embed src="http://www.youtube.com/v/'.$youtube_id.'?version=3&feature=player_detailpage" type="application/x-shockwave-flash"
           allowfullscreen="true" allowScriptAccess="always" width="'.$width.'" height="'.$height.'">
    </object>';
    }


    static function isYoutubeUrl($url)
    {
        if(preg_match('/youtube\.com/',$url) || preg_match('/youtu\.be/',$url))
            return true;

        return false;
    }


}