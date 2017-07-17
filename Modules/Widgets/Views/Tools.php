<div id="medit-tools">
    <?php foreach ($widgets as $widget):?>
    <img src="<?=$widget['ico']?>" id="medit-widget-but-<?=$widget['widget'];?>"
         title="<?=$widget['name'];?>" date-widget='<?=$widget['widget']?>'
         date-window-add='<?=(empty($widget['window_size']))? 'false': 'true';?>'
         date-window-size='<?=$widget['window_size'];?>' />

    <?php endforeach?>

    <!--<iframe name="medit_frame" id="medit_frame" frameborder="0" width="0" height="0" ></iframe>-->
</div>
