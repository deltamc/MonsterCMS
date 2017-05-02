<?php defined('MCMS_ACCESS') or die('No direct script access.');?>

<a href="<?=$ACTION?>" class="but <?php if($TARGET == "dialog"){?> win <?php }?><?php if($ALIGN  == "right") {?> right<?php }?>"
    <?php if($TARGET != "_top" && $TARGET != "dialog") {?> target="<?=$TARGET?>" <?php }?>
    <?php if($TARGET == "dialog"){?> data-size="<?=$WINDOW_SIZE?>" <?php }?>
    >
<?php if(!empty($ICO)) {?> <i class="fa <?=$ICO?>"></i> <?php }?>
<?=$TEXT?>
</a>