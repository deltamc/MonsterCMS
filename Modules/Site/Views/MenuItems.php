<?php
defined('MCMS_ACCESS') or die('No direct script access.');

?>

<li >
       <a href='<?=$url?>' <?=$css?> <?if(!empty($target)):?>target="<?=$target?>"<?endif?> ><?=$name?></a>

        <?if(!empty($sub_menu)):?>
            <ul><?=$sub_menu?></ul>
        <?endif?>
    </li>

