<?php
defined('MCMS_ACCESS') or die('No direct script access.');

?>

<li><a href='<?=$url?>' <?=$css?> <?php if(!empty($target)):?>target="<?=$target?>"<?php endif?> ><?=$name?></a><?php if(!empty($sub_menu)):?><ul><?=$sub_menu?></ul><?php endif?></li>

