<?php
defined('MCMS_ACCESS') or die('No direct script access.');
use \Monstercms\Core\User;
use \Monstercms\Core\Lang;

$tools = false;
if ($add || $edit || $delete) {
    $tools = true;
}
?>
<?php if ($add):?>
    <a href="/Articles/Add/MenuItem/<?=$itemMenuId?>" data-size="800x600" class="mcms-article-add-but win"><i class="fa fa-plus" aria-hidden="true"></i> <?=Lang::get('Articles.articleAdd')?></a>
<?php endif?>



<h1><?=$catalogName?></h1>
<?=$textTop?>

<?php foreach($items as $item):?>
    <section class="article-item  <?php if ($tools):?>edit mcms-widget id-<?=$item['article_id']?><?php endif?>">
        <h2><a href="/<?=$item['url']?>.html"><?=$item['name']?></a></h2>
        <?php if (!empty($item['preview'])):?>
           <div class="preview"> <?=$item['preview']?></div>
        <?php endif?>
        <?php if ($tools):?>
        <div class="mcms-widget-tools">
            <?php if ($edit):?><a href="/Articles/Edit/Id/<?=$item['article_id']?>" data-size="800x600"  class="win edit fa fa-pencil" title="Редактировать"></a><?php endif?>
            <?php if ($delete):?><a
                                    onclick="mcms_article_delete(<?=$item['article_id']?>, $(this).parents('.article-item'))"
                                    class="del fa fa-times"  title="<?=Lang::get('Articles.articleDelete')?>"></a>
            <?php endif?>
        </div>
        <?php endif?>
    </section>

<?php endforeach?>
<?php if ($delete):?>
<script>
    function mcms_article_delete(id, $remove){

        $.mcmsDelete({
            ajaxUrl:'/Articles/Delete/Id/' + id,
            remove:$remove,
            deleteText:'<?=Lang::get('Articles.articleDeleteConfirm')?>'
        });
    }
</script>
<?php endif?>
    <?=$pagination?>
<?=$textBottom?>
