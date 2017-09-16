<!DOCTYPE html>
<html lang="ru">
<head>
    <base href="<?=$BASE;?>" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/window.css" rel="stylesheet">
    <script src="<?=$SITE_URL?>/JavaScript/jquery.min.js"></script>
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
<?php if(!empty($DIALOG_HEAD)):?>
    <h1><?=$DIALOG_HEAD?></h1>
<?php endif?>
<?=$BODY?>


</body>
</html>
