<?php defined('MCMS_ACCESS') or die('No direct script access.'); ?>
<div  class="tools-bar left bottom">
    <a href="<?=$edit?>" class="fa fa-cog win" data-size="800x600" title="<?=$editTitle?>"></a>
    <a onclick="$.mcmsDelete({ajaxUrl:'<?=$delete?>', lacationUrl:'/', deleteText:'<?=$deleteConfirm?>'})" class="fa fa-times" title="<?=$deleteTitle?>"></a>
</div>