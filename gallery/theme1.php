<? session_start(); ?>
<!DOCTYPE html>
<htmls>
<head>
<meta charset="UTF-8" />
<title>Gallery</title>

<base href="/gallery/" />
<link rel="stylesheet" href="theme1.css" />

<link rel="alternate" href="mediafeed.rss.php" type="application/rss+xml" title="" id="gallery" />

<!-- jQuery fancyBox plugin -->
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="fancybox/jquery.easing.js"></script>

<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox.css" media="screen" />
<!-- / jQuery fancyBox plugin -->

<script type="text/javascript">

$(function() {

	
	$("a.thumb").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200,
		'overlayColor'	: 	'#000'
	});
	
});
</script>

</head>

<body>
  <p>/ oomu.xyz / gallery /</p>

<?

$list = 'files.lst.txt';

if (empty($_SESSION['lastread']) || $_SESSION['lastread'] < filemtime($list) || !empty($_GET['forcereload'])) {
    
    $_SESSION['images'] = file($list, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $_SESSION['lastread'] = time();
    
}

$images	= $_SESSION['images'];

$offset = empty($_GET['offset']) ? 0 : max(0, $_GET['offset']);
$limit 	= empty($_GET['limit']) ? 50 : max(1, $_GET['limit']);
$size 	= empty($_GET['size']) ? 200 : $_GET['size'];

?>


<?
for ($i=0; $i<$limit && $i+$offset<sizeof($images); $i++):
  $img = $images[$i+$offset];
?>
    <a href="<?=dirname($img).'/'.rawurlencode(basename($img))?>" class="thumb" rel="page">
        <img src="thumb.php?img=<?=urlencode($img)?>&amp;size=<?=$size?>" alt="<?=basename($img, '.jpg')?>" />
    </a>
<? endfor; ?>


<p class="links"><a href="t1/<?=max($offset-$limit, 0)?>/<?=$limit?>"><span>◀</span>&nbsp;Prev</a>&nbsp;/

<?
$nbPages = ceil(sizeof($images)/$limit);
for ($p=0; $p<$nbPages ;$p++) :
  $class = ($p*$limit == $offset) ? 'class="current"' : 'class="page"';
  $logout = ($p*$limit == $offset) ? '&amp;logout=true' : '';
?>
  <a href="t1/<?=($p*$limit)?>/<?=$limit?>" <?=$class?>><?=($p+1)?></a>
<? endfor; ?>

/&nbsp;<a href="t1/<?=($offset+$limit>sizeof($images))?0:$offset+$limit?>/<?=$limit?>">Next&nbsp;<span>▶</span></a></p>
</body>
</html>