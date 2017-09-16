<!DOCTYPE html>
<html>
<head>
    <base href="<?=$BASE;?>" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/window.css" rel="stylesheet">
    <link href="css/entry.css" rel="stylesheet">

    <?=$JS;?>

    <script src="<?=$SITE_URL?>/JavaScript/bootstrap.min.js"></script>
    <script>
        $(function (){
            $('.tip').tooltip({html:false});
        })
    </script>

    <?=$HEAD;?>

    <?=$CSS?>

</head>
<body>
<div class="entry">
<?php if(!empty($TITLE)):?>
    <h1><?=$TITLE?></h1>
<?php endif?>
    <div class="body">
        <?=$BODY?>
    </div>
</div>

</body>
</html>
