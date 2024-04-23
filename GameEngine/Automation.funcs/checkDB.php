<?php
include_once(dirname(__FILE__) . "/../Database.php");
function checkDB()
{
    $t = $_SERVER['REQUEST_TIME'];
    $result = mysql_query('SELECT `last_checkall`, `checkall_time` FROM config');
    if (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        $last_checkall = $row['last_checkall'];
        $checkall_time = $row['checkall_time'];
    }
    $TimerM = $checkall_time + $last_checkall;
    if ($t > $TimerM) {
        $time = $t - 43200;
        mysql_query("DELETE FROM ndata WHERE viewed = 1 AND time < $time");
        mysql_query("DELETE FROM movement WHERE endtime < $time AND sort_type!=9");
        // mysql_query("DELETE FROM mdata WHERE archived = 0 AND time < $time");
        mysql_query("DELETE FROM adventure WHERE end = 1");
        mysql_query("DELETE FROM auction WHERE finish = 1");
        mysql_query("DELETE FROM chat WHERE date < $time");
        $time = $t - 86400;
        mysql_query("DELETE FROM stats WHERE time < $time");

        /* global $database;
         $q = "SELECT * FROM movement, attacks where (movement.ref = attacks.id and movement.proc = 1) and ( movement.sort_type = 4) ORDER BY endtime DESC LIMIT 300";
         $query = mysql_query($q);

         while($row = mysql_fetch_assoc($query)){
             $database->deleteAttacksFrom($row['from']);
         }
        */

        mysql_query("UPDATE config SET `last_checkall` = '$t' ");
        $alltables = mysql_query("SHOW TABLES");
        // $i = 0;
        while ($table = mysql_fetch_assoc($alltables)) {
            foreach ($table as $db => $tablename) {
                mysql_query('OPTIMIZE TABLE ' . $tablename) or die(mysql_error());
                mysql_query('REPAIR TABLE ' . $tablename) or die(mysql_error());
                // mysql_query('ALTER TABLE ' . $tablename . ' ENGINE=myisam' ) or die(mysql_error());
                // mysql_query('ALTER TABLE ' . $tablename . ' ROW_FORMAT=FIXED' ) or die(mysql_error());
            }
        }
    }
}
