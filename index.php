<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

    <title>oomu.xyz</title>

<style>
body {
    background: #181818;
    color: #960;
    text-align: center;
    font-size: 11px;
}
pre {
    width: 121ex;
    margin: 10em auto;
    text-align: left;
}
a {
    color: #FC6;
}
a:hover {
    text-decoration: none;
}
.wide {
    letter-spacing: 1ex;
}
</style>
</head>

<body>
<?
require('suncalc.php');

// Paris
$latitude = 48.85;
$longitude = 2.35;

$SC = new SunCalc(new DateTime(), $latitude, $longitude);
$SCyesterday = new SunCalc(new DateTime('yesterday'), $latitude, $longitude);

$sun = $SC->getSunTimes();
$suny = $SCyesterday->getSunTimes();
$moon = $SC->getMoonTimes();

$phases = ". ------------ D ------------ O ------------ C ------------ .";
// $phases = "🌑🌓🌕🌗🌑";
$ph = round($SC->getMoonIllumination()['phase'], 2);
$tick = str_pad('^', $ph*strlen($phases)+1, ' ', STR_PAD_LEFT);

function fmtEphemLine($data, $key, $diff=null) {
    $suffix = ' '.$data[$key]->format('H:i');
    if ($diff != null) {
        $suffix .= ' '.$diff[$key]->diff($data[$key])->format('%R%i\'%S"').'';
        // $suffix .= ' ('.$diff[$key]->format('H.i').')';
    }
    return str_pad(ucfirst($key).' ', 80-strlen($suffix), '.').$suffix."\n";
}
function fmtLat($lat) { return abs($lat).($lat>0 ? 'N' : 'S'); }
function fmtLon($lon) { return abs($lon).($lon>0 ? 'E' : 'W'); }
?>
<pre>
    ...         ...     .        :    ...    :::         .,::      .:.-:.     ::-.:::::::::
 .;;;;;;;.   .;;;;;;;.  ;;,.    ;;;   ;;     ;;;         `;;;,  .,;;  ';;.   ;;;;''`````;;;
,[[     \[[,,[[     \[[,[[[[, ,[[[[, [['     [[[           '[[,,[['     '[[,[[['      .n[['
$$$,     $$$$$$,     $$$$$$$$$$$"$$$ $$      $$$            Y$$$P         c$$"      ,$$P"
"888,_ _,88P"888,_ _,88P888 Y88" 888o88    .d888  d8b     oP"``"Yo,     ,8P"`     ,888bo,_
  "YMMMMMP"   "YMMMMMP" MMM  M'  "MMM "YmmMMMM""  YMP  ,m"       "Mm,  mM"         `""*UMM


==========================================================================================


  Links

    * Pictures: <a href="gallery/t1">theme 1</a> - <a href="gallery/t2">theme 2</a>
    * <a href="https://giediprime.oomu.xyz:5001">Web access</a>
    * Memos: <a href="https://memo.gregseth.net">dev</a> - <a href="https://memo.gregseth.net/vi.shortcuts">vi</a> - <a href="https://memo.gregseth.net/git">git</a>


==========================================================================================


  Ephemeris // <?=date('Y-m-d');?> / <?=fmtLat($latitude)?> <?=fmtLon($longitude)?> 

    * <?=fmtEphemLine($sun, 'dawn', $suny)?>
    * <?=fmtEphemLine($sun, 'sunrise', $suny)?>
    * <?=fmtEphemLine($sun, 'sunset', $suny)?>
    * <?=fmtEphemLine($sun, 'dusk', $suny)?>
    
    * <?=fmtEphemLine($moon, 'moonrise')?>
    * <?=fmtEphemLine($moon, 'moonset')?>

    * Moon phase  <?=$phases?> 
                  <?=$tick?> 
<? if ($ph == 0): ?>
                                    _..._
                                  .:::::::.
                                 :::::::::::
                                 :::::::::::   NEW  MOON
                                 `:::::::::'
                                   `':::''
<? elseif ($ph > 0 && $ph <.25): ?>
                                    _..._
                                  .::::. `.
                                 :::::::.  :
                                 ::::::::  :    WAXING CRESCENT
                                 `::::::' .'
                                   `'::'-'
<? elseif ($ph == .25): ?>
                                    _..._
                                  .::::  `.
                                 ::::::    :
                                 ::::::    :    FIRST QUARTER
                                 `:::::   .'
                                   `'::.-'
<? elseif ($ph > .25 && $ph <.5): ?>
                                    _..._
                                  .::'   `.
                                 :::       :
                                 :::       :    WAXING GIBBOUS
                                 `::.     .'
                                   `':..-'
<? elseif ($ph == .5): ?>
                                    _..._
                                  .'     `.
                                 :         :
                                 :         :    FULL MOON
                                 `.       .'
                                   `-...-'
<? elseif ($ph > .5 && $ph <.75): ?>
                                    _..._
                                  .'   `::.
                                 :       :::
                                 :       :::    WANING GIBBOUS
                                 `.     .::'
                                   `-..:''
<? elseif ($ph == .75): ?>
                                    _..._
                                  .'  ::::.
                                 :    ::::::
                                 :    ::::::    LAST QUARTER
                                 `.   :::::'
                                   `-.::''
<? elseif ($ph > .75 && $ph <1): ?>
                                    _..._
                                  .' .::::.
                                 :  ::::::::
                                 :  ::::::::    WANING CRESCENT
                                 `. '::::::'
                                   `-.::''
<? endif; ?>

</pre>

</body>
</html> 
