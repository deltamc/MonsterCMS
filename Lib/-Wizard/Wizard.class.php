<?php namespace Monstercms\Lib;

use Monstercms\Lib\Form;


class Wizard
{

    private $forms_items = array();
    private $forms = array();
    private $forms_full = array();

    private $is_submit = false;

    function __construct()
    {

        $args = func_get_args();
        $i=0;
        foreach($args as $var)
        {
            if(!is_array($var)) continue;

            $this->forms_items[] = $var;
            $this->forms[$i] = new form(array('id' => 'wizard_' . $i ));
            $this->forms[$i]->add_items($var);
            $i++;
        }
    }

    public function getForm($index = 0)
    {

        //What form of output
        if(!$index) $index = $this->getThisFormIndex();




        $items = array();//$this->forms_items[$index];

        //forms that are already filled are adding to the current, see invisible fields

        for($i=0, $s=$index; $i< $s; $i++) {

            $items_old = $this->forms_items[$i];

            $items = $this->addHideItems($items, $items_old);

            $items[]  = array('type' => 'hidden',
                'name' => 'wizard_' . $i,
                'value' => 'true');
        }

        $form = $this->forms[$index];

        if(!empty($this->forms_full[$index])) $form->full($this->forms_full[$index]);

        $html = '';

        $form->add_items($items);
        $form->full($_POST);

        $html = "";

        if(!$form->is_submit())     $html  = $form->render();
        elseif (!$form->is_valid()) $html =  $form->error();
        elseif (sizeof($this->forms) > $index+1){

            $html = $this->getForm($index+1);
        }

        return $html;




    }

    public function isSubmit()
    {

        for ($i=0, $s=sizeof($this->forms); $i< $s; $i++)
        {

            if (!$this->forms[$i]->is_submit()) return false;
        }


        if ( !$this->forms[$i-1]->is_valid() ) return false;

        return true;
    }

    private function getThisFormIndex()
    {
        for ($i=0, $s=sizeof($this->forms); $i< $s; $i++)
        {

            if ($this->forms[$i]->is_submit())
                //return ($i+1 > $s) ? $s: $i+1;
                return $i;
        }

        return 0;
    }

    public function addHideItems(array $items, array $items_add)
    {

        foreach($items_add as $it)
        {
            if(isset($it['items']) && is_array($it['items']))
            {
                $out = array();
                $items = array_merge($items, $this->addHideItems($out, $it['items']) );
            }
            else
            {
                if(isset($it['name'])) $items[] = array('type' => 'hidden', 'name' => $it['name']);
            }
        }

        return $items;

    }

    public function full()
    {
        $args = func_get_args();

        foreach($args as $var)
        {
            if(is_array($var))
                $this->forms_full[] = $var;
        }
    }


}