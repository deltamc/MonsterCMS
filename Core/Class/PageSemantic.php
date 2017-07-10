<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

class PageSemantic
{
    private $title       = '';
    private $description = '';
    private $keywords    = '';
    private $canonical   = '';
    private $noIndex     = false;
    private $theme       = '';

    private static $instance = null;

    private $db;
    private $dbTable;


    private function __construct() {}

    protected function __clone() {}

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance          = new self();
            self::$instance->db      = Mcms::DB();
            self::$instance->dbTable = DB_TABLE_PAGE_SEMANTIC;
        }

        return self::$instance;
    }

    /**
     * Метод возвращает значение тега <title>
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Метод устанавливает значение  тега <title>
     * @return string
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Метод возвращает значение тега <meta name="description" />
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Метод устанавливает значение тега <meta name="description" />
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Метод возвращает значение тега <meta name="keywords" />
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Метод устанавливает значение тега <meta name="keywords" />
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Будет ли индексироваться страница поисковыми роботами
     * @return boolean
     */
    public function isNoindex()
    {
        return $this->noIndex;
    }

    /**
     * Будет ли индексироваться страница поисковыми роботами
     *
     * @param boolean $noIndex
     */
    public function setNoindex($noIndex)
    {
        $this->noIndex = (bool) $noIndex;
    }

    /**
     * Метод возвращает значение тега
     * <link rel="canonical" href="" />
     *
     * @return string
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * @param string $canonical
     */
    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;
    }

    /**
     * Метод устанавливает тему
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Метод возвращает название темы
     * @return string $theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Установить значения из базы данных
     *
     * $pageHead = Core\PageHead::init();
     * $pageHead->setData($moduleName, $idObject);
     *
     * @param $module
     * @param $objectId - ид объекта, для которого предназначены данные,
     *                    например, ид страница или ид записи в блоге.
     * @return bool
     */
    public function setData($module, $objectId) {

        $objectId = (int) $objectId;

        $data = $this->getData($objectId, $module);

        if(!empty($data)) {
            $this->title       = $data['seo_title'];
            $this->description = $data['seo_description'];
            $this->keywords    = $data['seo_keywords'];
            $this->canonical   = $data['seo_canonical'];
            $this->noIndex     = (bool) $data['seo_noindex'];
            $this->theme       = $data['theme'];

            return true;
        }

        return false;
    }

    /**
     * Считывает значения из базы данных
     * @param $objectId
     * @param $module
     * @return null|array
     */
    private function getData($objectId, $module)
    {
        $objectId = (int) $objectId;

        $sql = "SELECT * FROM `{$this->dbTable}` WHERE `object_id`=? AND `module`=? LIMIT 1";

        $stmt=$this->db->prepare($sql);
        $stmt->execute(array($objectId, $module));

        return $stmt->fetch();
    }

    public function save($module, $objectId)
    {
        $objectId = (int) $objectId;

        $values = array(
            'object_id'       => $objectId,
            'module'          => $module,
            'seo_title'       => $this->title,
            'seo_description' => $this->description,
            'seo_keywords'    => $this->keywords,
            'seo_canonical'   => $this->canonical,
            'seo_noindex'     => (int) $this->noIndex,
            'theme'           => (empty($this->theme)) ? 'NULL' : $this->theme,
        );
        $data = $this->getData($objectId, $module);

        if (!empty($data)) {
            $this->db->update($values, $this->dbTable, $data['id']);
        } else {
            $this->db->insert($values, $this->dbTable);

        }

    }







}