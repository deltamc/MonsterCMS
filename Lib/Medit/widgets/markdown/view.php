<?
    use \Monstercms\Lib as Lib;
?>
<div ><?
print $text;
    if(Lib\medit::validBase64($text)) $text = base64_decode($text);
    Lib\MarkdownExtra::defaultTransform($text)
    ?></div>