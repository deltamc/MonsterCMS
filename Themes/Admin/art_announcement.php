<?$row['description'] = str_replace("](../files", "](/files", $row['description']); ?>
<div class="page_short">
    <h4 class="page-header"><a href="/article.html?id=<?=$row['article_id']?>">
            <?=$row['title']?></a></h4>
    <div class="page_status_line">

        <ul class="page_status_line__list">

            <li><?if(!empty($row['date'])) print date("d.m.Y", $row['date']);?></li>

          <!--  <li class="fa fa-camera"></li>-->


            <?if($admin){?>
<div class="edit_art_button">
                <a href="/?module=articles&action=edit&article_id=<?=$row['article_id']?>"  class="fa fa-cog" title="Настройки"></a>
                <a href="/?module=articles&action=delete&article_id=<?=$row['article_id']?>"  class="fa fa-times" title="Удалить"></a>
</div>
            <?}?>


        </ul>
    </div>
    <?if(!empty($row['image'])){?>

    <p class="pull-left image_fat">
        <img src="/images/<?=$row['article_id']?>/<?=$row['image']?>" alt="image"><span></span>
    </p>
    <?}?>

    <div class="shortdesc<?if(empty($row['image'])) print " full";?>">
        <?=lib\Markdown::defaultTransform($row['description'])?>
    </div>
    <a class="btn detail" href="/article.html?id=<?=$row['article_id']?>"><div class="detail__msg">подробнее</div><div class="detail__icon fa fa-chevron-down"></div></a>
</div>
