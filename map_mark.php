<?php

include('GameEngine/Session.php');

if (

    (isset($_GET['tx0']) && $_GET['tx0'] <= WORLD_MAX && $_GET['tx0'] >= -WORLD_MAX)
    &&
    (isset($_GET['tx1']) && $_GET['tx1'] <= WORLD_MAX && $_GET['tx1'] >= -WORLD_MAX)
    &&
    (isset($_GET['ty0']) && $_GET['ty0'] <= WORLD_MAX && $_GET['ty0'] >= -WORLD_MAX)
    &&
    (isset($_GET['ty1']) && $_GET['ty1'] <= WORLD_MAX && $_GET['ty1'] >= -WORLD_MAX)

) {

    $srcImagePaths = array();
    $x0 = $_GET['tx0'];
    $y0 = $_GET['ty0'];
    $x1 = $_GET['tx1'];
    $y1 = $_GET['ty1'];
    $i = 0;
    $x2 = $x0;

    $til = $x1 - $x0;

    if ($x0 > 0 && $x1 < 0) {
        $x0 = -$x0;
        $til = $x1 - $x0;
        $x2 = -$x2;
        $x0--;
        $x1++;
        $til++;
    }
    if ($y0 > 0 && $y1 < 0) {
        $y0 = -$y0;
        $y0--;
    }

    while ($y1 >= $y0 && $x0 <= $x1) {
        $query = "SELECT `id` FROM wdata WHERE x='" . $x0 . "' AND y='" . $y1 . "' LIMIT 1";
        $result = mysql_query($query) or die(mysql_error());
        $row2 = mysql_fetch_assoc($result);
        $row = $database->getMInfo($row2['id']);
        $targetalliance = -1;
        $tribe = -1;
        $username = '';
        $oasisowner = '';
        if ($row['occupied'] > 0 && $row['fieldtype'] >= 0) {
            $targetalliance = $database->getUserField($row['owner'], "alliance", 0);
            $tribe = $database->getUserField($row['owner'], "tribe", 0);
            $username = $database->getUserField($row['owner'], "username", 0);
            $oasisowner = $database->getUserField($row['owner'], "username", 0);
            $confedarray = array();
            $atwararray = array();
            $naparray = array();
        }
        if ($tribe == 0) $tribe = 1;
        $t = null;

        if ($row['owner'] == $_SESSION['uid'] || $oasisowner == $_SESSION['uid']) {
            $t = "/assets/images/map/borderown.gif";
        } elseif ($targetalliance != 0 && $session->alliance == $targetalliance) {
            $t = "/assets/images/map/borderconfed.gif";
        } elseif ($targetalliance != 0 && in_array($targetalliance, $confedarray)) {
            $t = "/assets/images/map/borderconfed.gif";
        } elseif ($targetalliance != 0 && in_array($targetalliance, $naparray)) {
            $t = "/assets/images/map/bordernap.gif";
        } elseif ($targetalliance != 0 && in_array($targetalliance, $atwararray)) {
            $t = "/assets/images/map/borderatwar.gif";
        } else {
            $t = "/assets/images/map/border.gif";
        }

        $war = mysql_fetch_assoc(mysql_query("SELECT `sort_type`,`proc` FROM s1_movement WHERE `from` = '" . $_SESSION['wid'] . "' AND `to` = '" . $row['id'] . "' AND proc=0"));
        if ($war['proc'] == 1) {
            $t = "/assets/images/map/borderatwar.gif";
        }
        $pflagsQ = mysql_query("SELECT `id`,`x`,`y`,`index`,`text`,`dataId` FROM map_marks WHERE x='" . $x0 . "' AND y='" . $y1 . "' AND type = 'player' ORDER BY id ASC LIMIT 1") or die(mysql_error());
        if ($row2 = mysql_fetch_assoc($pflagsQ)) {
            switch ($row2['index']) {
                case 0:
                    $t = "/assets/images/map/border-1.gif";
                    break;
                case 1:
                    $t = "/assets/images/map/border-2.gif";
                    break;
                case 2:
                    $t = "/assets/images/map/border-3.gif";
                    break;
                case 3:
                    $t = "/assets/images/map/border-4.gif";
                    break;
                case 4:
                    $t = "/assets/images/map/border-5.gif";
                    break;
                case 5:
                    $t = "/assets/images/map/border-6.gif";
                    break;
                case 6:
                    $t = "/assets/images/map/border-7.gif";
                    break;
                case 7:
                    $t = "/assets/images/map/border-8.gif";
                    break;
                case 8:
                    $t = "/assets/images/map/border-9.gif";
                    break;
                case 9:
                    $t = "/assets/images/map/border-10.gif";
                    break;
            }
        }

        $srcImagePaths[$i] = $t;
        $i++;
        $x0++;
        // if($i ==2)break;
        if ($x0 > $x1) {
            $y1--;
            $x0 = $x2;
        }
    }

    $tileWidth = $tileHeight = 60;
    $numberOfTiles = $til == 9 ? 10 : 20;

    $pxBetweenTiles = 0;
    $mapWidth = $mapHeight = ($tileWidth + $pxBetweenTiles) * $numberOfTiles;
    $mapImage = imagecreatetruecolor($mapWidth, $mapHeight);
    $bgColor = imagecolorallocate($mapImage, 50, 40, 0);
    imagefill($mapImage, 0, 0, $bgColor);

    function indexToCoords($index)
    {
        global $tileWidth, $pxBetweenTiles, $leftOffSet, $topOffSet, $numberOfTiles;
        $x = ($index % $numberOfTiles) * ($tileWidth + $pxBetweenTiles) + $leftOffSet;
        $y = floor($index / $numberOfTiles) * ($tileWidth + $pxBetweenTiles) + $topOffSet;

        return array($x, $y);
    }

    foreach ($srcImagePaths as $index => $srcImagePath) {
        if ($srcImagePath) {
            list($x, $y) = indexToCoords($index);
            $tileImg = imagecreatefromgif($srcImagePath);
            imagecopy($mapImage, $tileImg, $x, $y, 0, 0, $tileWidth, $tileHeight);
            imagedestroy($tileImg);
        }
    }

    $thumbSize = 600;
    $thumbImage = imagecreatetruecolor($thumbSize, $thumbSize);

    //make transparent
    imagesavealpha($thumbImage, TRUE);
    imagealphablending($thumbImage, FALSE);
    $transparent = imagecolorallocatealpha($mapImage, 0, 0, 0, 127);
    imagefill($mapImage, 0, 0, $transparent);
    imagecolortransparent($mapImage, imagecolorallocatealpha($mapImage, 0, 0, 0, 127));
    imagecopyresampled($thumbImage, $mapImage, 0, 0, 0, 0, $thumbSize, $thumbSize, $mapWidth, $mapWidth);
    header('Content-type: image/png');
    imagepng($thumbImage, NULL, 9);
    imagedestroy($thumbImage);
    imagedestroy($mapImage);
} else {

    $im = imagecreatetruecolor(1, 1);
    header("Content-Type: image/gif");
    imagegif($im);
    imagedestroy($im);
}
