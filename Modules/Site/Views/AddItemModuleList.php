<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core\Lang;
?>

<h3><?=Lang::get('Site.pageTypeSelect')?></h3>

<div class="modules_list">
    <?php foreach($modules as $info):?>

            <a href="/Site/MenuAddItemStep2/item_type/<?=$info['itemType']?>/item_module/<?=$info['module']?>" class="module">
                <div class="ico">
                    <?php if(!empty($info['menuItemIcon'])):?>
                        <?php if(preg_match('/(.gif|.jpg|.png)$/', $info['menuItemIcon'])):?>
                            <img src="<?=$info['menuItemIcon']?>" />
                        <?php else:?>
                            <i class="<?=$info['menuItemIcon']?>" aria-hidden="true"></i>
                        <?php endif?>
                    <?php endif?>
                </div>
                <div class="name"><?=$info['menuItemName']?></div>
            </a>

    <?php endforeach?>
</div>
