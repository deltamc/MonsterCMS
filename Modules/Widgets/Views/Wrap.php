<?php defined('MCMS_ACCESS') or die('No direct script access.');?>
<section class="mcms-widget <?php if($edit):?>edit <?php endif?> <?=$widgetName?> <?=$class?>" data-id="<?=$id?>" data-window-size='<?=$windowSize;?>' data-pos="<?=$pos?>" data-widget="<?=$widgetName?>">
    <?=$html?>
</section>
