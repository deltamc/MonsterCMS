<?php defined('MCMS_ACCESS') or die('No direct script access.');?>

<a href="<?=$ACTION?>" title="<?=$TEXT?>" class="but <?if($ALIGN  == "right") {?> right<?}?>"   <?if($TARGET != "_top") {?> target="<?=$TARGET?>" <?}?>>
    <?if(!empty($ICO)) {?> <i class="fa <?=$ICO?>"></i> <?}?>

</a>