<?


$form_items = array
(
    array
    (
        'name' => "link",
        'type' => 'text',
        'label' => 'Текст ссылки:',

        'valid' => array
        (
            'required'
        ),
    ),
array(
      'type'=>'tab',
      'label' => 'Выбрать',
      'items' =>array
     (
          array
          (
              'name' => "file",
              'type' => 'filelist',
              'label' => 'Файл:',
              'path' => $conf['widgets']['docfiles']['path'],
              'path_link' => $conf['widgets']['docfiles']['path_link'],

          ),
      )
    ),
array(
      'type'=>'tab',
      'label' => 'Загрузить',
      'items' =>array
     (
          array
          (
              'name' => "fileload",
              'type' => 'file',
              'label' => 'Файл:',
              'help'  => $conf['widgets']['docfiles']['types'],
              'valid' => array
              (
                  'file_type' => $conf['widgets']['docfiles']['types']
              ),

          ),
      )
    ),




)
?>