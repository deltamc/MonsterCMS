<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * Class Events
 * @package Monstercms\Core
 */
class Events {

    private static $events = array();

    /**
     * С помощью этого метода можно подписаться на событие
     * @param $key - ключ
     * @param $module - имя модуля
     * @param $method - метод
     * @param int $priority - приоритет
     */
    public static function subs($key, $module, $method, $priority = 0)
    {
        $moduleEvent = null;
        $event  = null;

        list($moduleEvent, $event) = explode('.', $key);

        self::$events[$moduleEvent][$event][] = array
        (
            'module'    => $module,
            'method'    => $method,
            'priority'  => $priority
        );
    }

    /**
     * Вызов события
     * @param $key - имя события
     * @param string $returnType - тип возвращаемого значения
     * void - ничего
     * array_merge - массив. Возвращаемые массивы сливается в одни (array_merge)
     * array - массив. Возвращаемые массивы объединяется в один (array_push)
     * string - строка. Возвращаемые стоки сливаются в одну (конкатенация)
     * @param array $parameters - параметры которые будут доступны в экземпляре класса EventParam (метод getParam)
     * экземпляр класса EventParam передается виде параметра в метод-обработчик события
     *
     * @return array|null|string
     * @throws \Exception
     */
    public static function cell($key, $returnType = 'void', $parameters = array())
    {
        $module = null;
        $event  = null;

        list($module, $event) = explode('.', $key);

        if (!self::isSubs($key)) return null;

        usort(
            self::$events[$module][$event],
            function ($a, $b) {
                if ($a["priority"] == $b["priority"]) {
                    return -1;
                }
                if ($a["priority"] < $b["priority"]) {
                    return 0;
                }
                return 1;
            }
        );

        $out = null;

        foreach (self::$events[$module][$event] as $eventItem )
        {

            $ep = new EventParam($key);
            $ep->setParamArray($parameters);

            $moduleObj = Module::get($eventItem['module']);

            if (!method_exists($moduleObj, $eventItem['method'])) {
                $method = htmlspecialchars($eventItem['method']);
                $module = htmlspecialchars($eventItem['module']);
                throw new \Exception("Method '{$method}' not found. Module '{$module}'");
            }

            $out_temp = call_user_func_array
            (
                array(Module::get($eventItem['module']), $eventItem['method']),
                array($ep)
            );

            if ($returnType == 'void' || empty($out_temp)) continue;

            if (is_array($out_temp) && $returnType === 'array_merge') {
                if (is_null($out)) $out = array();
                $out = array_merge($out, $out_temp);

            } elseif (is_array($out_temp) && $returnType == 'array') {
                if (is_null($out)) $out = array();
                array_push($out, $out_temp);

            } elseif (is_string($out_temp)  && $returnType == 'string') {
                if (is_null($out)) $out = '';
                $out .= $out_temp;
            }
        }

        return $out;
    }

    /** Метод проверяет есть ли событие с ключем $key
     * @param $key
     * @return bool
     */
    public static function isSubs($key)
    {
        $module = null;
        $event  = null;

        list($module, $event) = explode('.', $key);

        return isset(self::$events[$module][$event]);
    }

    /**
     * Метод выводит список событий
     */
    public static function view()
    {
        var_dump(self::$events);
    }

    /**
     * Метод вызывает события формы
     * @param array $formItems - Элементы формы

     * @param array $params - параметры для передачи в методы которые подписаны на событие
     * @return array
     */
    public static function eventsForm(array $formItems, $params = array())
    {
        $out = array();

        foreach ($formItems as $it) {
            if (isset($it['items']) && is_array($it['items'])) {
                $it['items'] = self::eventsForm($it['items'], $params);
                $out[] = $it;
            } else {
                if (isset($it['type']) && $it['type'] == 'event') {

                    if(!is_array($params)) {
                        $params = array();
                    }

                    $items = self::cell($it['event'], 'array_merge', $params);

                    if (is_array($items)) {
                        foreach ($items as $item) {
                            $out[] = $item;
                        }
                    }
                } else {
                    $out[] = $it;
                }
            }
        }

        return $out;
    }


}

