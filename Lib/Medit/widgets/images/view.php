<?
//print $conf['widgets']['images']['dbTable'];



$table = $conf['widgets']['images']['dbTable'];
$dbId_image = $conf['widgets']['images']['dbId_group'];
$img_path =  $conf['widgets']['images']['path'];

$dbRow    =  $conf['widgets']['images']['dbRow'];
$theme_dir    =  $conf['theme_dir'];

$theme =  $conf['theme'];

$html = '';
$sql = 'SELECT * FROM `' . $table . '` WHERE `'. $dbId_image .'` = '.$id.' ORDER BY pos';
$result = $db->query($sql);

$count = $db->numRows($result);


if($count == 1)
{
    $img = $db->fetchArray($result);
    $html .= '<img src="/'.$img_path.'/'. $img[$dbRow] .'">';
}
elseif($count > 1)
{
    $html .= '<div id="owl_div_'.$id.'" class="owl-carousel owl-theme">';

    while($img =$db->fetchArray($result))
        $html .= '<div class="item"><img class="lazyOwl" src="/'.$img_path.'/'. $img[$dbRow] .'" ></div>';

    $html .= '</div>';

    $html .= '<script>';
    $html .= '
    $(function(){
    $("#owl_div_'.$id.'").owlCarousel({
        items: 1,
        lazyLoad: true,
        navigation: true,
        slideSpeed: 500,
        paginationSpeed: 2500,
        singleItem: true,
        autoPlay: false,
        navigationText: ["<", ">"],
        pagination: true
      });});';
    $html .= '</script>';


}
elseif($count == 0)
{
    $html .= '<div style="text-align: center"><img src="'.$theme_dir.'/'.$theme.'/empty_image.gif"></div>';
}
print $html;
?>