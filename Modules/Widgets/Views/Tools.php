<div id="medit-tools" data-page-id="<?=$pageId?>" class="tools-bar left top">
    <?php foreach ($widgets as $widget):?>
    <img src="<?=$widget['ico']?>" id="medit-widget-but-<?=$widget['widget'];?>"
         title="<?=$widget['name'];?>" data-widget='<?=$widget['widget']?>'
         data-window-size='<?=$widget['window_size'];?>'  />

    <?php endforeach?>
</div>
