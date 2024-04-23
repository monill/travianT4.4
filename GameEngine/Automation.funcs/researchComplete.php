<?php

include_once(dirname(__FILE__) . "/../Database.php");

function researchComplete()
{
    global $database;
    if (!$database->checkConnection()) {
        throw new Exception(__FILE__ . ' cant connect to database.');
    }
    $time = time();
    $q = "SELECT `tech`,`id`,`vref` FROM research where timestamp < $time";
    $dataarray = $database->query_return($q);
    foreach ($dataarray as $data) {
        $sort_type = substr($data['tech'], 0, 1);
        switch ($sort_type) {
            case "t":
                $q = "UPDATE tdata set " . $data['tech'] . " = 1 where vref = " . $data['vref'];
                break;
            case "a":
            case "b":
                $q = "UPDATE abdata set " . $data['tech'] . " = " . $data['tech'] . " + 1 where vref = " . $data['vref'];
                break;
        }
        $database->query($q);
        $q = "DELETE FROM research where id = " . $data['id'];
        $database->query($q);
    }
}
