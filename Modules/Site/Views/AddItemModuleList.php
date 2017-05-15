<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core\Lang;
?>

<h3><?=Lang::get('Site.pageTypeSelect')?></h3>

<div class="modules_list">
    <?php foreach($modules as $name=>$item_info):?>
        <?php foreach($item_info as $type=>$info):?>
            <a href="/Site/MenuAddItemStep2/item_type/<?=$type?>/item_module/<?=$name?>" class="module">
                <div class="ico">
                    <?php if(!empty($info[1])):?>
                        <?php if(preg_match('/(.gif|.jpg|.png)$/', $info[1])):?>
                            <img src="<?=$info[1]?>" />
                        <?php else:?>
                            <i class="<?=$info[1]?>" aria-hidden="true"></i>
                        <?php endif?>
                    <?php endif?>
                </div>
                <div class="name"><?=$info[0]?></div>
            </a>
        <?php endforeach?>
    <?php endforeach?>
</div>
