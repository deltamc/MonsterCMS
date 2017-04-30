<?
namespace  Monstercms\Lib;
class valid
{
    public static $errors =  array
    (
        'required'   => 'Не заполнено поле',
        'pattern'    => 'Не верный формат',
        'email'      => 'Пожалуйста, введите email',
        'min'        => 'Слишком малеькое значение',
        'max'        => 'Слишком большое  значение',
        'int'        => 'Вводимое значение должно быть целым числом',
        'is_file'    => 'Файл не выбран',
        'file_type'  => 'Не верный формат файла',
        'captcha'    => 'Не верно ввели защитный код'
    );

    public static function required($val, $param)
    {

        return !empty($val);
    }

    public static function max($val, $param)
    {
        if(empty($val)) return true;
        return !($val > $param);
    }

    public static function min($val, $param)
    {
        if(empty($val)) return true;
        return !($val < $param);
    }

    static public function captcha($val, $param = true)
    {

        if(!isset($_SESSION['keystring']) || $_SESSION['keystring'] !=  $val) return false;
        return true;

    }

    public static function pattern($val, $param)
    {

        if(empty($val)) return true;

        if(is_array($param))
        {
            $valid = false;
            foreach($param as $pattern)
            {
                if(preg_match('/^'.$pattern.'$/', $val))  $valid = true;
            }
            return $valid;
        }
        else return preg_match('/^'.$param.'$/', $val);
    }

    public static function email($val, $param)
    {
        if(empty($val)) return true;

        if(function_exists('filter_var')) return filter_var($val, FILTER_VALIDATE_EMAIL);

        $reg = "/^[a-z0-9_\.-]{1,}@[0-9a-z_\.-]{1,}\.[a-z]{2,4}$/";

        return preg_match($reg, $val);
    }

    public static function url($val, $param = true)
    {
        if(empty($val)) return true;
        if(!$param)     return true;
        return filter_var( $val, FILTER_VALIDATE_URL );
    }

    public static function int($val, $param = true)
    {
        if(empty($val)) return true;
        if(!$param)     return true;
        return filter_var( $val, FILTER_VALIDATE_INT );
    }

    public static function file_type($input_name, $value)
    {

        $value = str_replace(' ', '', $value);
        $value = explode(",",$value);

        if(empty($_FILES[$input_name]['tmp_name'])) return true;

        $rash = strtolower(strrchr($_FILES[$input_name]['name'], "."));
        $rash = str_replace('.', '', $rash);
        if(!in_array($rash,$value)) return false;

        return true;

    }
    static public function is_file($input_name)
    {

        if(empty($_FILES[$input_name]['tmp_name']) || $_FILES[$input_name]['size'] == 0)
        {
            return false;
        }
        else
        {
            return true;
        }

    }

    /**
     * @param $val
     * @param array $param
     * $param[0] -string|array cell
     * $param[1] - array args
     * @return bool|mixed
     */
    public static function cell($val, $param)
    {
        if(empty($val)) return true;

        if(!isset($param[1]) || !is_array($param[1])) $param[1] = array($val);
        else array_unshift($param[1], $val);

        return call_user_func_array( $param[0], $param[1]);
    }


}
?>