<?

namespace  Monstercms\Lib;

class littletempl
{
    private $path;
    private $path_alt;

    /**
     * @param $path - путь к директории с шаблонами
     * @param $path_alt - путь к директории с шаблонами (наследование)
     * если файла нет в папке шаблона $path, шаблонизатор попытается загрузить файл с $path_alt
     */
    function __construct($path, $path_alt = null)
    {
        $this->path     = $path;
        $this->path_alt = $path_alt;
    }

    /**
     * @param $tpl_file - файл шаблона
     * @param array $tags - массив (ключ => значение) переменных которые видны в шаблоне
     * @return string
     */

    public function get($tpl_file, $tags = array())
    {
        if(is_array($tags) && count($tags) > 0) extract($tags);



        if     (is_file($this->path     . '/' . $tpl_file)) $tpl_file = $this->path     . '/' . $tpl_file;
        elseif (is_file($this->path_alt . '/' . $tpl_file)) $tpl_file = $this->path_alt . '/' . $tpl_file;
        else                                                return "";

        $out = '';

        Ob_start ();
        Ob_implicit_flush (0);

        include ($tpl_file);
        $out = ob_get_contents ();
        Ob_end_clean ();

        return $out;
    }


}
?>