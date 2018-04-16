<?php namespace Monstercms\Widgets\Text;

use Monstercms\Core;
use Monstercms\Modules\Widgets as ModuleWidgets;

class Widget extends ModuleWidgets\WidgetAbstract implements ModuleWidgets\WidgetInterface
{


    /**
     * Метод возвращает массив с формой редактирования
     * @return array
     */
    public function getFormEdit()
    {
        return include(WIDGET_DIR . DS . $this->widgetName . DS . 'Form.php');
    }

    /**
     * Метод возвращает массив с формой добавления
     * @return array
     */
    public function getFormAdd()
    {
        return include(WIDGET_DIR . DS . $this->widgetName . DS . 'Form.php');
    }

    /**
     * Метод возвращает представления виджета
     * @param $vars - массив переменных которые видны в шаблоне
     *  @return string
     */
    public function getView(array $vars){
        return $this->view->get('View.php', $vars);
    }

    /**
     * Метод возвращает url иконки
     *  @return string
     */
    public  function getIco()
    {
        return '/Widgets/' . $this->widgetName . '/ico.png';
    }

    /**
     * Метод возвращает название виджета
     *  @return string
     */
    public function getName()
    {
        return "Параграф";
    }


    /**
     * Метод возвращает версию модуля Widget с которой он совместим
     * @return string
     */
    public function compatibility()
    {
        return '1.0';
    }



    /**
     * Размер окна в формате wxh, например: 700x800
     * если метод возвращает пустое заначение или false, то окно не отображается
     * @return string|false
     */
    public function getAddFormWindowSize()
    {
        return '800x620';
    }

    /**
     * Размер окна с формой редактирования виджета в формате wxh, например: 700x800
     * если метод возвращает пустое заначение или false, то окно не отображается
     * @return string|false
     */
    public function getEditFormWindowSize()
    {
        return '800x620';
    }

    /**
     * Массив с параметрами. ключ=>значение
     * Данный массив заполнит форму добавления виджета.
     * После заполнения формы добавления/редактирования виджета
     * пост параметры ($_POST) ключи которых совпадают с ключами параметров виджета,
     * значения параметров виджетов будут сохранены в БД
     * @return array
     */
    public function getParameters()
    {
        return array(
            'text|html'      => '',
        );
    }

    public function deleteAfter(array $data, array $params) {
        $text = $params['text'];
        $pageId = $data['page_id'];
        $result = array();

        $uploadDir = str_replace('/', '\/', UPLOAD_DIR);

        $pattern = '/'.$uploadDir.'\/Widgets\/([a-f0-9]*\.\w{3,5})/';

        preg_match_all($pattern,$text, $result);
        $result = $result[1];


        if (empty($result)) return;

        foreach ($result as $item) {

            @unlink(UPLOAD_DIR . DS . 'Widgets' .  DS . $item);
            //@unlink(UPLOAD_DIR . DS . 'Widgets' .  DS . '0' . DS . $item);
        }
    }

    /**
     * Вес сортировки, чем больше вес тем ниже будет отображаться иконка виджета в панели
     * @return int
     */
    public function getOrder(){
        return 1;
    }
}