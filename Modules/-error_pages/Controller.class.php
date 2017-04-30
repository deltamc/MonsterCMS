<?php namespace Monstercms\Modules\error_pages;

use \Monstercms\Core as Core;

class Controller extends  Core\Controller
{

    public function action404()
    {
        print "404 Error :(";

        exit();
    }

    public function action403()
    {
        print "403 Error :(";

        exit();
    }
}