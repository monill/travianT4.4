<?php

if (file_exists(dirname(__FILE__) . "/constant.php")) {
    include(dirname(__FILE__) . "/constant.php");
} else {
    die('Cant find constant.php in /install/include/database.php');
}

class MYSQL_DB
{
    var $connection;

    function MYSQL_DB()
    {
        $this->connection = mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS) or die(mysql_error());
        mysql_select_db(SQL_DB, $this->connection) or die(mysql_error());
    }

    function mysql_exec_batch($p_query, $p_transaction_safe = true)
    {
        if ($p_transaction_safe) {
            $p_query = 'START TRANSACTION;' . $p_query . '; COMMIT;';
        };
        $query_split = preg_split("/[;]+/", $p_query);

        foreach ($query_split as $command_line) {
            $command_line = trim($command_line);
            if ($command_line != '') {
                $query_result = mysql_query($command_line);
                if ($query_result == 0) {
                    break;
                };
            };
        };
        return $query_result;
    }

    function query($query)
    {
        return mysql_query($query, $this->connection);
    }
}

$database = new MYSQL_DB;
