<? session_start(); ?>
<?

$list = 'files.lst.txt';

if (empty($_SESSION['lastread']) || $_SESSION['lastread'] < filemtime($list) || !empty($_GET['forcereload'])) {
    
    $_SESSION['images'] = file($list, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $_SESSION['lastread'] = time();
    
}

$images	= $_SESSION['images'];
$id = empty($_GET['id']) ? 0 : $_GET['id'];
$img = dirname($images[$id]).'/'.basename($images[$id]);
$realimgsize = getimagesize($img);

function roundid($val) {
    global $images;
    $newid = $val % count($images);
    if ($newid < 0) {
        $newid += count($images);
    }
    return $newid;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    
    <title>Gallery // <?=pathinfo($images[$id])['filename']?></title>
    
<base href="/gallery/" />
<style>
#filter {
    background: #000 url('<?=$img ?>') no-repeat center center fixed;
}
</style>
<link rel="stylesheet" href="theme2.css" />

<script>
function computeSize() {
    var imageRealW = <?=$realimgsize[0];?>;
    var imageRealH = <?=$realimgsize[1];?>;
    
    var img = document.querySelector('#big');
    var imageW = img.clientWidth;
    var imageH = img.clientHeight;
    
    var ratio = Math.round(imageW/imageRealW*100);
    
    var sizeStr = imageRealW+' x '+imageRealH+' @ '+ratio+'%';
    
    console.log(sizeStr);
    console.log("W>>> "+imageW+" / "+imageRealW+" = "+Math.round(imageW/imageRealW*100)+"%");
    console.log("H>>> "+imageH+" / "+imageRealH+" = "+Math.round(imageH/imageRealH*100)+"%");
    
    var tag = document.querySelector('#sizeTag');
    tag.textContent = sizeStr;
    tag.style.left = (img.getBoundingClientRect().right - tag.clientWidth) +'px';
    console.log("pos>>> ["+tag.style.left+","+tag.style.top+"]");
}

</script>
</head>

<body onresize="computeSize()">
    <div id="filter" ></div>
    <!--<h1><?=basename($images[$id])?></h1>-->
    <img id="big" src="<?=$img?>" onload="computeSize()" />
    <div id="sizeTag"></div>
    <nav>
        <a href="t2/<?=roundid($id-5)?>" class="link">&lt;&lt;</a>
        <a href="t2/<?=roundid($id-1)?>" class="link">&lt;</a>
        
        <?
        for ($i=0; $i<5; $i++):
            $thumbid = roundid($id+$i-2);
            $img = dirname($images[$thumbid]).'/'.rawurlencode(basename($images[$thumbid]));
        ?>
            <a href="t2/<?= $thumbid ?>" class="thumb" rel="page">
                <img src="thumb.php?img=<?=urlencode($img)?>&amp;size=200" alt="<?=basename($img, '.jpg')?>" />
            </a>
        <? endfor; ?>
    
        <a href="t2/<?=roundid($id+1)?>" class="link">&gt;</a>
        <a href="t2/<?=roundid($id+5)?>" class="link">&gt;&gt;</a>
    </nav>

</body>
</html>