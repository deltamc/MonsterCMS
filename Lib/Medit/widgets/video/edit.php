<?
//���� ������ ��������� ��� ������, ��������� ����������
if(!isset($this)) exit();
//��������� ������������ �����
if(!empty($_POST['v_url']))

{
    $url = $parameters['v_url'];
    if(\lib\vkVideo::isVkUrl($url) && !preg_match("/video_ext\.php/", $url)  )
    {
        $vkVideo = new \lib\vkVideo($url);
        $url = $vkVideo->getUrlForIframe();
        $parameters['v_url'] = $url;
    }

    $element_art = $this->save_element_art($widget, $parameters, $element_art_id);

    $this->update_element_art_in_page($element_art['id'], $widget, $element_art['cache']);

}


?>