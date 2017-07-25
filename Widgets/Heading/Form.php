<?php
return array(
    array
    (
            'name' => "heading",
            'type' => 'text',
            'label' => 'Заголовок*:',
            'valid' => array
            (
                'required'
            ),
    ),
    array
        (
            'name' => "level",
            'type' => 'select',
            'label' => 'Тип:',
            'options' => array
            (
                1=> "Заголовок 1 уровня",
                2=> "Заголовок 2 уровня",
                3=> "Заголовок 3 уровня",
                4=> "Заголовок 4 уровня",
                5=> "Заголовок 5 уровня",
                6=> "Заголовок 6 уровня",
            )
        ),
    array
        (
            'type' => 'submit',
            'value' => ' Сохранить '
        ),
);