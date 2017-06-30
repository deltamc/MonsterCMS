<!DOCTYPE html>
<html lang="ru">
  <head>
      <base href="<?=$BASE;?>" />
    <?php include $THEME_PATH."head_part.php" ?>

	<?=$HEAD;?>
    <?=$CSS?>
  </head>
  <body>

    <?=$ADMIN_TOOLS?>



    <?php include $THEME_PATH."modal_info.html" ?>

    <?php include $THEME_PATH."top_block_part.html" ?>

    <?php include $THEME_PATH."slides_part.html" ?>

    <div class="navbar navbar-custom">
      <div class="container">
        <div class="nav menu-block">
          <?=Monstercms\Core\Module::get('Site')->menu(1);?>

        </div>
      </div>
    </div>
    <div class="main_block">



      <?php include $THEME_PATH."main_block_left_part.html" ?>

      <?php include $THEME_PATH."main_block_center_part.html" ?>
	 

      <?php include $THEME_PATH."main_block_right_part.php" ?>
    </div>

    <?php include $THEME_PATH."bottom_part.html" ?>
    <?=$JS;?>
    <?php include $THEME_PATH."js_part.html" ?>

	
  </body>
</html>
