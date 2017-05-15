<?php
if(isset($_REQUEST[session_name()])){
        session_start();
}
session_start();
$image=imageCreate(100,30);
$color[0]=imagecolorallocate($image,255,255,255);
$color[1]=imagecolorallocate($image,255,0,0);
$color[2]=imagecolorallocate($image,0,255,0);
$color[3]=imagecolorallocate($image,255,80,0);
$color[4]=imagecolorallocate($image,60,200,55);
$color[5]=imagecolorallocate($image,100,0,60);
$color[6]=imagecolorallocate($image,200,50,80);
$color[7]=imagecolorallocate($image,0,0,0);


$font1[0]="/fonts/calvin.ttf";
$font1[1]="/fonts/arial.ttf";

$text=rand(10000,99999);

$_SESSION['keystring'] = $text;

if(isset($_REQUEST[session_name()])){
   $_SESSION['keystring'] = $text;
}

//$fh=fopen("key.txt","r+");
// fwrite($fh,md5(md5($text)));
//fclose($fh);
$x=10;

for($i=0;$i<strlen($text);$i++){
  $x+=12;
  $c=round(rand(1,sizeof($color)-1));
  $size=round(rand(20,22));
  $f=round(rand(0,sizeof($font1)-1));
  $r=imagettftext($image,$size,round(rand(20,30)),$x,25,$color[$c],getcwd().$font1[$f],substr($text,$i,1));
}
Header("Content-type: image/png");
imagePng($image);