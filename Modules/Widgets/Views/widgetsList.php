<div id="mcms-widgets" data-page-id="<?=$pageId?>">
    <?php foreach($widgets as $widget):?>
        <div class="mcms-widget <?=$widget['widget']?>" data-pos="<?=$widget['pos']?>" data-id="<?=$widget['id']?>" data-widget="<?=$widget['widget']?>">
            <?=$widget['cache']?>
        </div>
    <?php endforeach?>
</div>