<?php namespace Monstercms\Modules\Site;

class SiteMap  extends \Monstercms\Core\ModelAbstract{

    private $xml = '';
    private $xmlUrls;
    private $encoding = 'UTF-8';




    private function xmlStart()
    {
        $this->xml .='<?xml version="1.0" encoding="'.$this->encoding.'"?>'.PHP_EOL;
        $this->xml .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
    }


    private function xmlEnd()
    {
        $this->xml .= '</urlset>'.PHP_EOL;
    }

    /**
     * <url> Родительский тег для каждой записи URL-адреса. Остальные теги являются дочерними для этого тега.
     *
     * @param $loc - URL-адрес страницы. Этот URL-адрес должен начинаться с префикса (например, HTTP) и заканчиваться косой чертой,
     * если Ваш веб-сервер требует этого. Длина этого значения не должна превышать 2048 символов.
     *
     * @param string $lastmod -  Дата последнего изменения файла. Эта дата должна быть в формате W3C Datetime. Этот формат
     * позволяет при необходимости опустить сегмент времени и использовать формат ГГГГ-ММ-ДД.
     * Обратите внимание, что этот тег не имеет отношения к заголовку "If-Modified-Since (304)", который может вернуть сервер,
     * поэтому поисковые системы могут по-разному использовать информацию из этих двух источников.
     *
     * @param string $changefreq -  Вероятная частота изменения этой страницы. Это значение предоставляет общую информацию
     * для поисковых систем и может не соответствовать точно частоте сканирования этой страницы. Допустимые значения:
     * always, hourly, daily, weekly, monthly, yearly, never
     * Значение"всегда" должно использоваться для описания документов, которые изменяются при каждом доступе к этим документам.
     * Значение "никогда" должно использоваться для описания архивных URL-адресов.
     *
     * @param string $priority - Приоритетность URL относительно других URL на Вашем сайте. Допустимый диапазон значений
     * — от 0,0 до 1,0. Это значение не влияет на процедуру сравнения Ваших страниц со страницами на других сайтах — оно
     * только позволяет указать поисковым системам, какие страницы, по Вашему мнению, более важны для сканеров.
     */

    public function xmlUrl($loc, $lastmod = '', $changefreq = '', $priority = '')
    {
        $this->xmlUrls .= '<url>'.PHP_EOL;

        $this->xmlUrls .= '<loc>'.self::replace_chars($loc).'</loc>'.PHP_EOL;

        if(!empty($lastmod))    $this->xmlUrls .= '<lastmod>'.$loc.'</lastmod>'.PHP_EOL;
        if(!empty($changefreq)) $this->xmlUrls .= '<changefreq>'.$changefreq.'</changefreq>'.PHP_EOL;
        if(!empty($priority))   $this->xmlUrls .= '<priority>'.$priority.'</priority>'.PHP_EOL;

        $this->xmlUrls .= '</url>'.PHP_EOL;
    }

    /**
     * Функция заменяет недопустимые символы.
     * @param $s - строка для замены
     * @return Возвращает строку с замененными символами
     */
    static function replace_chars($s)
    {
        $from = array('"', '&', '>', '<', '\'');
        $to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
        //$to = array(' ', ' ', ' ', ' ', ' ');
        $s = str_replace($from, $to, $s);


        $s = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $s);
        //$s = iconv('windows-1251','utf-8',  $s);
        return trim($s);
    }

    /**
     * генерация SiteMap из БД
     * @param $sql - SQL Запрос. пример: SELECT id_m FROM cms_menu
     * @param $urlTemp - шаблон url. пример: http://mysite.ru/?p=%id_m%
     */

    public function generateFromDatabase($sql, $urlTemp)
    {

        $result = $this->db->query($sql);

        while($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $url = $urlTemp;

            foreach($row as $key => $val) {
                $url = str_replace('%'.$key.'%',$val, $url );
            }

            $this->xmlUrl($url);
        }

    }

    public function generateFromArray(array $urls)
    {
        foreach ($urls as $url) {
            $this->xmlUrl($url);
        }

    }

    public function generate(){
        $tUrl       = DB_TABLE_URL;
        $tItemsMenu = $this->config['db_table_menu_items'];

        $sql = "SELECT url.url as `page_url`
                FROM `{$tItemsMenu}` as item
                     INNER JOIN  `{$tUrl}` AS url
                     ON item.url_id = url.id WHERE `index`=1";

        $urlTemp = SITE_URL . '/%page_url%' . URL_SEMANTIC_END;

        $this->generateFromDatabase($sql, $urlTemp);
    }

    function __toString()
    {
        $this->xml .= $this->xmlStart();
        $this->xml .= $this->xmlUrls;
        $this->xml .= $this->xmlEnd();
        return $this->xml;
    }


}