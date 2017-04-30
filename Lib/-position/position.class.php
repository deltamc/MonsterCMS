<?php

/**
 * Created by PhpStorm.
 * User: bioma_000
 * Date: 25.11.2015
 * Time: 23:41
 */
namespace  Monstercms\Lib;

class position
{
    private $db;

    public $posTable     = 'article_position';
    public $itemIdRow    = 'article_id';
    public $catalogIdRow = 'catalog_id';
    public $positionRow  = 'position';


    function __construct()
    {
        global $DB;

        $this->db = $DB;

    }

    public function getMax($catalog)
    {
        $catalog = intval($catalog);

        $sql = 'SELECT MAX(`' . $this->positionRow . '`) FROM `' . $this->posTable .
               '` WHERE ' . $this->catalogIdRow . ' = ' . $catalog;

        $pos = $this->db->getRow($sql);

        return intval($pos[0]);

    }

    public function add($item, $catalog, $position)
    {
        $item     = intval($item);
        $catalog  = intval($catalog);
        $position = intval($position);

        $list = array
        (
            $this->itemIdRow    => $item,
            $this->catalogIdRow => $catalog,
            $this->positionRow  =>$position
        );

        $this->db->insert($list, $this->posTable);
    }

    public function newItem($item, $catalog)
    {
        $max = $this->getMax($catalog);
        $max++;
        $this->add($item, $catalog, $max);
    }

}