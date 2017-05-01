<!DOCTYPE html>
<html lang="ru">
<head>
    <base href="<?=$BASE;?>" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">

    <?=$HEAD;?>

    <?=$CSS?>

</head>
<body>
<?if(!empty($DIALOG_HEAD)):?>
    <h1><?=$DIALOG_HEAD?></h1>
<?endif?>
<?=$BODY?>

<?=$JS;?>

<script src="/javascript/bootstrap.min.js"></script>
<script>
    $(function (){
        $('.tip').tooltip({html:false});
    })
</script>
</body>
</html>
