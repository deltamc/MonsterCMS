<div id="mcms-widgets" data-page-id="<?=$pageId?>">
    <?php foreach($widgets as $widget):?>
        <div class="mcms-widget <?=$widget['widget']?>" data-id="<?=$widget['id']?>">
            <?=$widget['cache']?>
        </div>
    <?php endforeach?>
</div>