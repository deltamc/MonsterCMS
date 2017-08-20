<?
$idr = 'code' . rand(0,10000);
?>
<div <?php if(!empty($id)):?>id="<?=$id?>" <?php endif?><?php if(!empty($css_class)):?>class="<?=$css_class?>"<?php endif?>>
    <pre><code id="<?=$idr?>"><?=htmlspecialchars($code)?></code></pre>
    <script>
        $(function(){
            $(document).ready(function() {
                $('#<?=$idr?>').each(function(i, block) {
                    console.log('block');
                    console.dir(block);
                    hljs.highlightBlock(block);
                });
            });
        });
    </script>
</div>
