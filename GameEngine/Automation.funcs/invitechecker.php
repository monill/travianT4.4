<?php

include_once(dirname(__FILE__) . "/../Database.php");

function invitechecker()
{
    global $database;
    $q = 'SELECT `player_id`,`player_name` FROM refrence';
    $array = $database->query_return($q);
    foreach ($array as $indi) {
        $q = 'SELECT `pop` FROM vdata where owner = ' . $indi['player_id'];
        $query = mysql_fetch_assoc(mysql_query($q));
        // echo $query['pop'];
        if ($query['pop'] > INVITEPOP) {
            $q = 'SELECT `id` FROM users WHERE username = "' . $indi['player_name'] . '"';
            $q = mysql_query($q) or die(mysql_error());
            $query2 = mysql_fetch_assoc($q);
            $database->modifyGold($query2['id'], INVITEGOLD, 1);
            $q = 'DELETE FROM refrence WHERE player_id = ' . $indi['player_id'];
            $q = mysql_query($q) or die(mysql_error());
        }
    }
}
