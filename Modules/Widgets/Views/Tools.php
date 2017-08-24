<div id="medit-tools" data-page-id="<?=$pageId?>">
    <?php foreach ($widgets as $widget):?>
    <img src="<?=$widget['ico']?>" id="medit-widget-but-<?=$widget['widget'];?>"
         title="<?=$widget['name'];?>" data-widget='<?=$widget['widget']?>'
         data-window-size='<?=$widget['window_size'];?>'  />

    <?php endforeach?>

    <!--<iframe name="mcms-widget-add-frame" id="mcms-widget-add-frame" frameborder="0" width="0" height="0" ></iframe>-->
</div>
