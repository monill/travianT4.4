<?php

include_once(dirname(__FILE__) . "/../Database.php");

function culturePoints()
{
    global $database;
    if (!$database->checkConnection()) {
        throw new Exception(__FILE__ . ' cant connect to database.');
    }
    $time = $_SERVER['REQUEST_TIME'] - (86400 / SPEED);
    $array = array();
    $q = "SELECT users.id, users.lastupdate, cpproduction, itemcpproduction FROM users, hero where users.lastupdate < $time AND hero.uid=users.id LIMIT 100";
    $array = $database->query_return($q);
    foreach ($array as $indi) {
        if ($indi['lastupdate'] < $time) {
            $uhero = $database->getHero($indi['id']);
            $sumcp = $database->getVSumField($indi['id'], 'cp');
            $heroproduction = round(($uhero['cpproduction'] * HEROATTRSPEED + $uhero['itemcpproduction'] * ITEMATTRSPEED) * $sumcp / 100);
            $cp = $heroproduction + $sumcp;
            $cp = ($cp / 24) / 2;
            $newupdate = $_SERVER['REQUEST_TIME'];
            $q = "UPDATE users set cp = cp + $cp, lastupdate = $newupdate where id = '" . $indi['id'] . "'";
            $database->query($q);
        }
    }
}
