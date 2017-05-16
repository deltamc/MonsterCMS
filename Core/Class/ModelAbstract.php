<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib\datebase;
use Monstercms\Core\MCMS;

/**
 * Абстрактный класс
 * Class ModelAbstract
 */
class ModelAbstract
{
    protected $config;
    protected $moduleName;
    protected $db;
    protected $dbTable;


    function __construct($config)
    {

        $this->db = Mcms::DB();

        $this->config = $config;

        if(isset($this->config['db_table'])) {
            $this->dbTable = $this->config['db_table'];
        }

        if(isset($this->config['module'])) {
            $this->moduleName = $this->config['module'];
        }

    }
}