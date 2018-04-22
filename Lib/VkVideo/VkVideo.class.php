<?php namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: Danila
 * Date: 23.01.2016
 * Time: 17:24
 */
class VkVideo
{

    public $url, $id, $oid,  $hash;

    function __construct($url)
    {
        $this->url    = $url;
        $this->id     = $this->getId($url);
        $this->oid    = $this->getOid($url);
        $this->hash   = $this->getHash( $this->oid. '_' . $this->id);

    }

    static function getPlayer($url, $width, $height)
    {
        return '<iframe src="' . $url . '"
         width="' . $width . '" height="' . $height . '"  frameborder="0"></iframe>';
    }

    public function getUrlForIframe()
    {
        return "//vk.com/video_ext.php?oid=" . $this->oid . "&id=" .
        $this->id.'&hash=' . $this->hash . "&hd=2";
    }

    public function player($width, $height)
    {
        return '<iframe src="//vk.com/video_ext.php?oid='.$this->oid.'&id='.
        $this->id.'&hash='.$this->hash.'&hd=2"
         width="'.$width.'" height="'.$height.'"  frameborder="0"></iframe>';
    }

    private function getHash($video_vk_id)
    {
        $url = "http://vk.com/al_video.php?act=show_inline&al=1&autoplay=1&module=public&mute=1&video=".$video_vk_id."&width=395";

       

        $url_for_ifarme = null;

        $content = file_get_contents($url);

        preg_match("/\"hash2\":\"([a-f0-9]*)\"/", $content, $video_hash);

        unset($content);

        if(empty($video_hash[1])) return false;
        return  $video_hash[1];

    }

    private function getId($video_vk_url)
    {

        preg_match("/video[-\d]+_([\d]+)/", $video_vk_url, $video_id);
        if(empty($video_id[1])) return false;

        return  $video_id[1];

    }

    private function getOid($video_vk_url)
    {

        preg_match("/video([-\d]+)_/", $video_vk_url, $video_id);
        if(empty($video_id[1])) return false;

        return  $video_id[1];

    }

    static function isVkUrl($url)
    {
        if(preg_match("/vk\.com/", $url)) return true;
        else return false;
    }
}