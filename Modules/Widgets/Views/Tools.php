<div id="medit-tools" data-page-id="<?=$pageId?>">
    <?php foreach ($widgets as $widget):?>
    <img src="<?=$widget['ico']?>" id="medit-widget-but-<?=$widget['widget'];?>"
         title="<?=$widget['name'];?>" data-widget='<?=$widget['widget']?>'
         data-window-add='<?=(empty($widget['window_size']))? 'false': 'true';?>'
         data-window-size='<?=$widget['window_size'];?>'  />

    <?php endforeach?>

    <!--<iframe name="medit_frame" id="medit_frame" frameborder="0" width="0" height="0" ></iframe>-->
</div>
