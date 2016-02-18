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
$latitude = 48.7941;
$longitude = 2.2745;

$SC = new SunCalc(new DateTime(), $latitude, $longitude);
$SCyesterday = new SunCalc(
    (new DateTime())->sub(new DateInterval('P1D')),
    $latitude, 
    $longitude
);

$sun = $SC->getSunTimes();
$suny = $SCyesterday->getSunTimes();
$moon = $SC->getMoonTimes();

$phases = ". -------------- D -------------- O -------------- C -------------- .";
// $phases = "🌑🌓🌕🌗🌑";
$ph = round($SC->getMoonIllumination()['phase'], 2);
$tick = str_pad('^', $ph*strlen($phases)+1, ' ', STR_PAD_LEFT);

function fmtEphemLine($data, $key, $diff=null) {
    $suffix = ' '.$data[$key]->format('H:i:s');
    if ($diff != null) {
        $todaytime = $data[$key];
        $difftime = clone $diff[$key];
        $difftime->add(new DateInterval('P1D'));
        //echo $todaytime->format(DateTime::ISO8601)."\n";
        //echo $difftime->format(DateTime::ISO8601)."\n";
        $suffix .= ' '.$difftime->diff($todaytime)->format('%R%i\'%S"').'';
        //$suffix .= ' ('.$difftime->format('H.i.s').')';
    }
    return str_pad(ucfirst($key).' ', 80-strlen($suffix), '.').$suffix."\n";
}
function fmtDaytimeLine() {
    global $sun, $suny;
    $todayduration = $sun['sunrise']->diff($sun['sunset']);
    $suffix = ' '.$todayduration->format('%h.%I\'%S"');

    $yesterdayduration = $suny['sunrise']->diff($suny['sunset']);
    $deltasecs = $todayduration->h *60*60 + $todayduration->i *60 + $todayduration->s
        - ($yesterdayduration->h *60*60 + $yesterdayduration->i *60 + $yesterdayduration->s);

    $d1 = new DateTime('@0');
    $d2 = clone $d1;
    $i = new DateInterval('PT'.abs($deltasecs).'S');
    if ($deltasecs > 0) {
        $d2->sub($i);
    } else {
        $d2->add($i);
    }
    
    $suffix .= ' '.$d2->diff($d1)->format('%R%i\'%S"').'';

    return str_pad('Daytime ', 80-strlen($suffix), '.').$suffix."\n";
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


    * <a href="https://giediprime.oomu.xyz:5001">Giediprime</a> | <a href="https://box.oomu.xyz:12345">Box</a>

    * Pictures: <a href="https://oomu.xyz/gallery/t1">theme 1</a> - <a href="https://oomu.xyz/gallery/t2">theme 2</a>
    * Memos: <a href="https://memo.gregseth.net">dev</a> - <a href="https://memo.gregseth.net/vi.shortcuts">vi</a> - <a href="https://memo.gregseth.net/git">git</a>
    * <a href="https://gregseth.net/gmscripts">GreaseMonkey scripts</a>
    * <a href="https://gregseth.net/signature.html">Signatures</a>

  Tools

    * <a href="https://regex101.com">RegEx 101</a>
    * <a href="http://www.shellcheck.net/">Shell Check</a>
    * <a href="http://www.ostera.io/tldr.jsx">TL;DR manpages</a>

  Networking

    * <a href="https://tls.imirhil.fr">Imirhil</a>
    * <a href="https://www.ssllabs.com/ssltest/index.html">SSL Labs</a>
    * <a href="http://www.ipv6-test.com/">IPv6</a>

==========================================================================================


  Ephemeris // <?=date('Y-m-d');?> / <?=fmtLat($latitude)?> <?=fmtLon($longitude)?> 

    * <?=fmtEphemLine($sun, 'dawn', $suny)?>
    * <?=fmtEphemLine($sun, 'sunrise', $suny)?>
    * <?=fmtEphemLine($sun, 'sunset', $suny)?>
    * <?=fmtEphemLine($sun, 'dusk', $suny)?>
    * <?=fmtDaytimeLine()?>
    
    * <?=($moon['moonrise'] != null) ? fmtEphemLine($moon, 'moonrise') : '' ?>
    * <?=($moon['moonset'] != null) ? fmtEphemLine($moon, 'moonset') : '' ?>

    * Moon phase <?=$phases?> 
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
