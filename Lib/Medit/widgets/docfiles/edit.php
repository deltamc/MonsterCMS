<?
//���� ������ ��������� ��� ������, ��������� ����������
if(!isset($this)) exit();
//��������� ������������ �����
$types = str_replace(" ", "", $conf['widgets']['docfiles']['types']);
$types = explode(",", $types);
$upload = new \lib\upload('fileload', 'files', false, $types);

if($upload->error == 0)
    $parameters['file'] = $conf['widgets']['docfiles']['path_link'].'/'.$upload->file;


    $element_art = $this->save_element_art($widget, $parameters, $element_art_id);

    $this->update_element_art_in_page($element_art['id'], $widget, $element_art['cache']);




?>