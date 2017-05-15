<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Lang;
$s=0;
$url = '/' . $moduleName . '/';
?>

<br />
<br />
<div class="panel-group" id="accordion">


<?php foreach ($menu_list as $menu):?>
    <div class="panel panel-default" id="mcms-list-page-<?=$menu['id']?>-panel">
        <div class="panel-heading"  data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$s?>">
            <?=$menu['name']?> <span class="save">Save...</span>
            <span class="load">Load...</span>

        </div>
        <div class="panel-config">
            <a clsss="dropdown-toggle" data-toggle="dropdown" href="#" ><i class="fa fa-gear"></i></a>
            <ul class="dropdown-menu pull-right">
                <li><a href="<?=$url?>EditMenu/id/<?=$menu['id']?>">
                        <i class="fa fa-pencil"></i> <?=Lang::get('Site.edit');?></a></li>
                <li><a role="menu-delete" data-menu-id="<?=$menu['id']?>"><i class="fa fa-trash-o"></i> <?=Lang::get('Site.delete');?></a></li>
                <li role="presentation" class="divider"></li>
                <li><a href="<?=$url?>CodeMenu/id/<?=$menu['id']?>"><i class="fa fa-code"></i> <?=Lang::get('Site.codeMenu');?></a></li>

            </ul>
        </div>

        <div id="collapse<?=$s?>" class="panel-collapse collapse <?php if($s==0) print "in"?>">
            <div class="panel-body custom-scrollbar" style="max-height:200px;overflow:auto; ">
                <ul id="mcms-list-page-<?=$menu['id']?>" class="ztree mcms-list-page"></ul>

            </div>
        </div>
    </div>
    <?php $s++;?>
<?php endforeach;?>
</div>