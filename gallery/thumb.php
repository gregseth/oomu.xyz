<?

/** Requires the following GET vars:
 *   img:   The url of the picture
 *   size:  The longest side of the picture
 */
 
/**** DEBUG only *************
ini_set('display_errors',1); 
error_reporting(E_ALL);
/*****************************/

$img = urldecode($_GET['img']);
$size = empty($_GET['size']) ? 200 : $_GET['size'];

$type = substr(strrchr($img,'.'), 1);
$ttype = str_replace('jpg','jpeg',strtolower($type));
$imgcr = 'imagecreatefrom'.$ttype;
$imgecho = 'image'.$ttype;

$cached_thumb = 'cache/'.md5($img).'.'.$type;
if (!file_exists($cached_thumb)) {
//dbg:// echo "<pre>[$img]\n[".stripslashes($img)."]\n[".(file_exists(stripslashes($img))?"yes":"no")."]</pre>";exit;
    
    $src = null;
    $dest = null;
    if (file_exists(stripslashes($img)) && filesize(stripslashes($img))<8*1024*1024) {
        $src = $imgcr(stripslashes($img));
    }
    if ($src) {
        $sx = imagesx($src);
        $sy = imagesy($src);
        
        if (max($sx, $sy) <= $size) {
            $dx = $sx;
            $dy = $sy;
        } else {
            $r=$sx/$sy;
            $dx = round($size * (($sx > $sy) ? 1 : $r));
            $dy = round($size * (($sx > $sy) ? 1/$r : 1));
        }
        $dest=imagecreatetruecolor($dx, $dy);
    }
    if ($dest) {
        imagecopyresampled($dest, $src, 0, 0, 0, 0, $dx, $dy, $sx, $sy);
        
        if ($ttype == 'jpeg') {
            $imgecho($dest, $cached_thumb, 100);
        } else {
            $imgecho($dest, $cached_thumb);
        }
        
        imagedestroy($dest);
    } else {
        $type = 'jpg';
        $cached_thumb = '404_thumb.jpg';
    } 
    
}
// else
{
    header("Content-type: image/".$type);
    header("Location: ".$cached_thumb);
}

exit;
?>
