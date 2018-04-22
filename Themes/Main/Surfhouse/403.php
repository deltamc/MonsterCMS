<?php defined('MCMS_ACCESS') or die('No direct script access.');?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <base href="<?=$BASE;?>" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" />


    <title><?=$TITLE?></title>

    <meta name="description" content="<?=$DISCRIPTION?>" />
    <meta name="keywords" content="<?=$KEYWORDS?>" />
    <?php if($NOINDEX):?>
        <meta name="robots" content="noindex, nofollow" />
    <?php endif?>
    <?php if(!empty($CANONICAL)):?>
        <link rel="canonical" href="<?=$CANONICAL?>" />
    <?php endif?>
    <?=$HEAD;?>
    <?=$CSS?>
    <?=$JS;?>


</head>
<body>
<?=$ADMIN_TOOLS?>
<h1 style="text-align: center">Error 403</h1>
</body>
</html>