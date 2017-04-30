<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core\Lang;
?>

<div class="btn-group">
    <a href="/Site/MenuAddItem" class="btn btn-default">
        <i class="fa fa-plus-square" aria-hidden="true"></i>
        <?=Lang::get('Site.addItem')?>
    </a>

    <a href="/Site/AddMeun" class="btn btn-default">
        <i class="fa fa-list" aria-hidden="true"></i>
        <?=Lang::get('Site.addMenu')?>
    </a>
</div>