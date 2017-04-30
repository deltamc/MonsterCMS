<?php defined('MCMS_ACCESS') or die('No direct script access.');?>

<a href="<?=$ACTION?>" class="but <?if($TARGET == "dialog"){?> win <?}?><?if($ALIGN  == "right") {?> right<?}?>"
    <?if($TARGET != "_top" && $TARGET != "dialog") {?> target="<?=$TARGET?>" <?}?>
    <?if($TARGET == "dialog"){?> data-size="<?=$WINDOW_SIZE?>" <?}?>
    >
<?if(!empty($ICO)) {?> <i class="fa <?=$ICO?>"></i> <?}?>
<?=$TEXT?>
</a>

