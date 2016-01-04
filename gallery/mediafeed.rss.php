<?
	session_start(); 
	echo '<'.'?xml version="1.0" encoding="utf-8" standalone="yes"?'.'>'; 
?>

<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" 
	xmlns:atom="http://www.w3.org/2005/Atom">
<channel>

<?
$images = $_SESSION['images'];

echo "<!-- image count: ".sizeof($images)." -->";

for ($i=0; $i<100/*sizeof($images)*/; $i++) {	
  $img=$images[$i];

?>

	<item>
<!--	    <title><?=str_replace('.jpg', '', str_replace('/', '', strrchr($img, '/')))?></title>-->
<!--		<media:description><?=$img?></media:description>-->
	        <link><?=urlencode($img)?></link>
		<media:thumbnail url="thumb.php?size=300&amp;img=<?=urlencode($img)?>" />
		<media:content url="<?=urlencode($img)?>" />
	</item>

<?

}
?>


</channel>
</rss>
