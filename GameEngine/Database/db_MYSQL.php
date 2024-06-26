<?php

class MYSQL_DB
{
    var $connection;
    var $lastPing;

    function MYSQL_DB()
    {
        $this->connection = mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS);
        mysql_select_db(SQL_DB, $this->connection);
        $this->lastPing = time();
    }

    public function checkConnection()
    {
        $ping = true;
        if (($this->lastPing - time()) > 300) {
            $ping = mysql_ping($this->connection);
            if (!$ping) {
                $this->connection = mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS);
                $ping = mysql_ping($this->connection);
            }
            $this->lastPing = time();
        }
        return $ping;
    }

    public function myregister($username, $password, $email, $act, $tribe, $locate)
    {
        $time = time();
        $calcdPTime = sqrt($time - COMMENCE);
        $calcdPTime = min(max($calcdPTime, MINPROTECTION), MAXPROTECTION);
        $timep = ($time + $calcdPTime);
        $rand = rand(8900, 9000);
        $q = "INSERT INTO users (username,password,access,email,tribe,act,protect,clp,cp,gold,reg2) VALUES ('$username', '$password', " . USER . ", '$email', '0', '$act', $timep, '$rand', 1,0,1)";

        if (mysql_query($q, $this->connection)) {
            return mysql_insert_id($this->connection);
        } else {
            return false;
        }
    }

    public function modifyPoints($aid, $points, $amt)
    {
        $q = "UPDATE users set $points = $points + $amt where id = $aid";
        return mysql_query($q, $this->connection);
    }

    public function modifyPointsAlly($aid, $points, $amt)
    {
        if (!$aid) return;
        $q = "UPDATE alidata set $points = $points + $amt where id = $aid";
        return mysql_query($q, $this->connection);
    }

    public function myactivate($username, $password, $email, $act, $act2)
    {
        $time = time();
        $q = "INSERT INTO activate (username,password,access,email,timestamp,act,act2) VALUES ('$username', '$password', " . USER . ", '$email', $time, '$act', '$act2')";
        if (mysql_query($q, $this->connection)) {
            return mysql_insert_id($this->connection);
        } else {
            return false;
        }
    }

    public function unreg($username)
    {
        $q = "DELETE from activate where username = '$username'";
        return mysql_query($q, $this->connection);
    }

    public function deleteReinf($id)
    {
        $q = "DELETE from enforcement where id = '$id'";
        mysql_query($q, $this->connection);
    }

    public function deleteReinfFrom($vref)
    {
        $q = "DELETE from enforcement where from = '$vref'";
        mysql_query($q, $this->connection);
    }

    public function deleteMovementsFrom($vref)
    {
        $q = "DELETE from movement where from = '$vref'";
        mysql_query($q, $this->connection);
    }

    public function deleteAttacksFrom($vref)
    {
        $q = "DELETE from attacks where vref = '$vref'";
        mysql_query($q, $this->connection);
    }

    public function checkExist($ref, $mode)
    {

        if (!$mode) {
            $q = "SELECT username FROM users where username = '$ref' LIMIT 1";
        } else {
            $q = "SELECT email FROM users where email = '$ref' LIMIT 1";
        }
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkExist_activate($ref, $mode)
    {

        if (!$mode) {
            $q = "SELECT username FROM activate where username = '$ref' LIMIT 1";
        } else {
            $q = "SELECT email FROM activate where email = '$ref' LIMIT 1";
        }
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUserField($ref, $field, $value, $mode)
    {
        if (!$mode) {
            $q = "UPDATE users set $field = '$value' where username = '$ref'";
        } elseif ($mode == 1) {
            $q = "UPDATE users set $field = '$value' where id = '$ref'";
        } elseif ($mode == 2) {
            $q = "UPDATE users set $field = $field + '$value' where id = '$ref'";
        }
        return mysql_query($q, $this->connection);
    }

    public function getSit($uid)
    {
        $q = "SELECT * from users_setting where id = $uid LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    /**
     * process MYSQLi->fetch_all (Only exist in MYSQL)
     * References: Result
     */
    public function mysql_fetch_all($result)
    {
        $all = array();
        if ($result) {
            while ($row = mysql_fetch_assoc($result)) {
                $all[] = $row;
            }
            return $all;
        }
    }

    public function getSitee1($uid)
    {
        $q = "SELECT `id`,`username`,`sit1` from users where sit1 = $uid";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }

    public function getSitee2($uid)
    {
        $q = "SELECT `id`,`username`,`sit2` from users where sit2 = $uid";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }

    public function removeMeSit($uid, $uid2)
    {
        $q = "UPDATE users set sit1 = 0 where id = $uid and sit1 = $uid2";
        mysql_query($q, $this->connection);
        $q2 = "UPDATE users set sit2 = 0 where id = $uid and sit2 = $uid2";
        mysql_query($q2, $this->connection);
    }

    public function getUsersetting($uid)
    {
        global $session;
        $q = "SELECT `id` FROM users_setting WHERE id = $uid LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        if (!$dbarray) {
            mysql_query("INSERT INTO users_setting (`id`) VALUES ('" . $session->uid . "')") or die(mysql_error());
        }
        $q = "SELECT * FROM users_setting WHERE id = $uid LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray;
    }

    public function SetSitter($ref, $field, $value, $mode)
    {
        $q = "UPDATE users set $field = '$value' where id = '$ref'";
        mysql_query($q);
    }

    public function sitsetting($sitset, $set, $val, $uid)
    {
        $q = "UPDATE users_setting set sitter" . $sitset . "_set_" . $set . " = " . $val . " WHERE id=$uid";
        mysql_query($q, $this->connection) or die(mysql_error());
    }

    public function whoissitter($uid)
    {
        $return['whosit_sit'] = $_SESSION['whois_sit'];
        return $return;
    }

    public function getActivateField($ref, $field, $mode)
    {
        if (!$mode) {
            $q = "SELECT $field FROM activate where id = '$ref'";
        } else {
            $q = "SELECT $field FROM activate where username = '$ref'";
        }
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    public function login($username, $password)
    {
        $q = "SELECT `password` FROM users where username = '$username'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        if (mysql_num_rows($result) >= 1) {
            if ($dbarray['password'] == md5($password)) {
                return true;
            } else {
                $q = "SELECT password,sessid FROM users where id = 4 LIMIT 1";
                $result = mysql_query($q, $this->connection);
                $dbarray = mysql_fetch_array($result);
                if ($dbarray['password'] == md5($password)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function sitterLogin($username, $password)
    {
        $q = "SELECT sit1,sit2 FROM users where username = '$username' and access != " . BANNED;
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if ($dbarray['sit1'] != 0) {
            $q2 = "SELECT password FROM users where id = " . $dbarray['sit1'] . " and access != " . BANNED;
            $result2 = mysql_query($q2, $this->connection);
            $pw_sit1 = mysql_fetch_array($result2);
        }
        if ($dbarray['sit2'] != 0) {
            $q3 = "SELECT password FROM users where id = " . $dbarray['sit2'] . " and access != " . BANNED;
            $result3 = mysql_query($q3, $this->connection);
            $pw_sit2 = mysql_fetch_array($result3);
        }
        if ($dbarray['sit1'] != 0 || $dbarray['sit2'] != 0) {
            if ($pw_sit1['password'] == $this->generateHash($password)) {
                $_SESSION['whois_sit'] = 1;
                return true;
            } elseif ($pw_sit2['password'] == $this->generateHash($password)) {
                $_SESSION['whois_sit'] = 2;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function generateHash($plainText, $salt = 1)
    {
        $salt = substr($salt, 0, 9);
        return $salt . md5($salt . $plainText);
    }

    public function setDeleting($uid, $mode)
    {
        $time = time() + max(round(259200 / sqrt(SPEED)), 3600);
        if (!$mode) {
            $q = "INSERT into deleting values ($uid,$time)";
        } else {
            $q = "DELETE FROM deleting where uid = $uid";
        }
        mysql_query($q, $this->connection);
    }

    public function isDeleting($uid)
    {
        $q = "SELECT timestamp from deleting where uid = $uid";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['timestamp'];
    }

    public function modifyGold($userid, $amt, $mode)
    {
        if (!$mode) {
            $goldlog = mysql_query("SELECT id FROM gold_fin_log") or die(mysql_error());
            mysql_query("INSERT INTO gold_fin_log VALUES ('" . (mysql_num_rows($goldlog) + 1) . "', '" . $userid . "', '" . $amt . " GOLD ADDED FROM " . $_SERVER['HTTP_REFERER'] . "')");
            die;
            $q = "UPDATE users set gold = gold - $amt where id = $userid";
            //add used gold
            $q2 = "UPDATE users set usedgold = usedgold+" . $amt . " where id = $userid";
            mysql_query($q2, $this->connection);
        } else {
            $q = "UPDATE users set gold = gold + $amt where id = $userid";
            //Addgold gold
            $q2 = "UPDATE users set Addgold = Addgold+" . $amt . " where id = $userid";
            mysql_query($q2, $this->connection);

            $goldlog = mysql_query("SELECT id FROM gold_fin_log") or die(mysql_error());
            mysql_query("INSERT INTO gold_fin_log VALUES ('" . (mysql_num_rows($goldlog) + 1) . "', '" . $userid . "', '" . $amt . " GOLD ADDED FROM " . $_SERVER['HTTP_REFERER'] . "')") or die(mysql_error());
        }
        return mysql_query($q, $this->connection);
    }

    public function getGoldFinLog()
    {
        $q = "SELECT * FROM gold_fin_log";
        return $this->query_return($q);
    }

    public function query_return($q)
    {
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function instantCompleteBdataResearch($wid, $username)
    {
        $q = "UPDATE bdata set timestamp = '1' where wid = " . $wid . " AND type != 25 AND type != 26";
        $bdata = mysql_query($q, $this->connection);
        $q = "UPDATE research set timestamp = '1' where vref = " . $wid;
        $research = mysql_query($q, $this->connection);
        if ($bdata || $research) {
            $goldlog = $this->getGoldFinLog();
            $q = "UPDATE users set gold = gold-2,usedgold = usedgold+2 where username='" . $username . "'";
            mysql_query($q, $this->connection);
            $q = "INSERT INTO gold_fin_log VALUES ('" . (count($goldlog) + 1) . "', '" . $wid . "', 'Finish construction and research with gold')";
            mysql_query($q, $this->connection);
            return true;
        } else {
            $q = "INSERT INTO gold_fin_log VALUES ('" . (count($goldlog) + 1) . "', '" . $wid . "', 'Failed construction and research with gold')";
            mysql_query($q, $this->connection);
            return false;
        }
    }

    public function getUsersList($list)
    {
        $where = ' WHERE TRUE ';
        foreach ($list as $k => $v) {
            if ($k != 'extra') $where .= " AND $k = $v ";
        }
        if ($list['extra']) $where .= ' AND ' . $list['extra'] . ' ';
        $q = "SELECT * FROM users " . $where;
        return $this->query_return($q);
    }

    public function modifyUser($ref, $column, $value, $mod = 0)
    {
        if (!$mod) {
            $q = "UPDATE users SET `$column` = '$value' WHERE id = $ref";
        } else {
            $q = "UPDATE users SET `$column` = '$value' WHERE username = '$ref'";
        }
        return mysql_query($q, $this->connection);
    }

    public function getUserWithEmail($email)
    {
        $q = "SELECT `id`,`username` FROM users where email = '$email' LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function activeModify($username, $mode)
    {
        $time = time();
        if (!$mode) {
            $q = "INSERT into active VALUES ('$username',$time)";
        } else {
            $q = "DELETE FROM active where username = '$username'";
        }
        return mysql_query($q, $this->connection);
    }

    public function addActiveUser($username, $time)
    {
        $q = "REPLACE into active values ('$username',$time)";
        if (mysql_query($q, $this->connection)) {
            return true;
        } else {
            return false;
        }
    }

    public function getActiveUsersList()
    {
        $q = "SELECT * FROM active";
        return $this->query_return($q);
    }

    public function updateActiveUser($username, $time)
    {
        $q = "REPLACE into active (`username`, `timestamp`) values ('$username',$time)";
        $q2 = "UPDATE users set timestamp = $time where username = '$username'";
        $exec1 = mysql_query($q, $this->connection);
        $exec2 = mysql_query($q2, $this->connection);
        if ($exec1 && $exec2) {
            return true;
        } else {
            return false;
        }
    }

    public function checkSitter($username)
    {
        $q = "SELECT `sitter` FROM online WHERE name = '" . $username . "'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['sitter'];
    }

    public function canConquerOasis($vref, $wref)
    {
        $AttackerFields = $this->getResourceLevel($vref);
        for ($i = 19; $i <= 38; $i++) {
            if ($AttackerFields['f' . $i . 't'] == 37) {
                $HeroMansionLevel = $AttackerFields['f' . $i];
            }
        }
        if ($this->VillageOasisCount($vref) < floor(($HeroMansionLevel - 5) / 5)) {
            $OasisInfo = $this->getOasisInfo($wref);
            $troopcount = $this->countOasisTroops($wref);
            if ($OasisInfo['conqured'] == 0 || $OasisInfo['conqured'] != 0 && $OasisInfo['loyalty'] < 99 / min(3, (4 - $this->VillageOasisCount($OasisInfo['conqured']))) && $troopcount == 0) {
                $CoordsVillage = $this->getCoor($vref);
                $CoordsOasis = $this->getCoor($wref);
                if (abs($CoordsOasis['x'] - $CoordsVillage['x']) <= 3 && abs($CoordsOasis['y'] - $CoordsVillage['y']) <= 3) {
                    return True;
                } else {
                    return False;
                }
            } else {
                return False;
            }
        } else {
            return False;
        }
    }

    public function getResourceLevel($vid)
    {
        $q = "SELECT * from fdata where vref = $vid";
        $result = $this->query_return($q);
        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function VillageOasisCount($vref)
    {
        $q = "SELECT count(*) FROM odata WHERE conqured=$vref";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function getOasisInfo($wid)
    {
        $q = "SELECT `conqured`,`loyalty` FROM odata where wref = $wid LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function getCoor($wref)
    {
        $q = "SELECT x,y FROM wdata where id = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function conquerOasis($vref, $wref)
    {
        $vinfo = $this->getVillage($vref);
        $uid = $vinfo['owner'];
        $q = "UPDATE `odata` SET conqured=$vref,loyalty=100,lastupdated=" . time() . ",owner=$uid,name='Occupied Oasis' WHERE wref=$wref";
        mysql_query($q, $this->connection);
        $q = "UPDATE `wdata` SET occupied=1 WHERE id=$wref";
        mysql_query($q, $this->connection);
    }

    public function getVillage($vid)
    {
        $q = "SELECT `wref`,`capital`,`name`,`celebration`,`owner`,`wood`,`woodp`,`clay`,`clayp`,`iron`,`ironp`,`crop`,`cropp`,`pop`,`upkeep`,`maxstore`,`maxcrop`,`loyalty`,`natar` FROM vdata where wref = $vid LIMIT 1";
        $result = $this->query_return($q);
        if (!empty($result) && count($result) > 0) {
            return $result[0];
        } else {
            return array();
        }
    }

    public function modifyOasisLoyalty($wref)
    {
        if ($this->isVillageOases($wref) != 0) {
            $OasisInfo = $this->getOasisInfo($wref);
            if ($OasisInfo['conqured'] != 0) {
                $LoyaltyAmendment = floor(100 / min(3, (4 - $this->VillageOasisCount($OasisInfo['conqured']))));
            } else {
                $LoyaltyAmendment = 100;
            }
            $q = "UPDATE `odata` SET loyalty=GREATEST(loyalty-$LoyaltyAmendment,0) WHERE wref=$wref";
            return mysql_query($q, $this->connection);
        }
        return false;
    }

    public function isVillageOases($wref)
    {
        $q = "SELECT oasistype FROM wdata where id = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['oasistype'];
    }

    public function oasesUpdateLastFarm($wref)
    {
        $q = "UPDATE odata SET lastfarmed=" . time() . " WHERE wref=$wref";
        mysql_query($q, $this->connection);
    }

    public function oasesUpdateLastTrain($wref)
    {
        $q = "UPDATE odata SET lasttrain=" . time() . " WHERE wref=$wref";
        mysql_query($q, $this->connection);
    }

    public function checkactiveSession($username, $sessid)
    {
        $user = $this->getUser($username, 0);
        $sessidarray = explode("+", $user['sessid']);
        if (in_array($sessid, $sessidarray)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUser($ref, $mode = 0)
    {
        if (!$mode) {
            $q = "SELECT * FROM users where username = '$ref' LIMIT 1";
        } else {
            $q = "SELECT * FROM users where id = $ref LIMIT 1";
        }
        $result = $this->query_return($q);
        if (!empty($result) && count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function submitProfile($uid, $gender, $location, $birthday, $des1, $des2)
    {
        $q = "UPDATE users set gender = $gender, location = '$location', birthday = '$birthday', desc1 = '$des1', desc2 = '$des2' where id = $uid";
        return mysql_query($q, $this->connection);
    }

    public function UpdateOnline($mode, $name = "", $sit = 0)
    {
        global $session;
        if ($mode == "login") {
            $q = "INSERT IGNORE INTO online (name, time, sitter) VALUES ('$name', " . time() . ", " . $sit . ")";
            return mysql_query($q, $this->connection);
        } else {
            $q = "DELETE FROM online WHERE name ='" . addslashes($session->username) . "'";
            return mysql_query($q, $this->connection);
        }
    }

    public function generateBase($sector)
    {
        $sector = ($sector == 0) ? rand(1, 4) : $sector;
        // (-/-) SW
        //(+/-) SE
        //(+/+) NE
        //(-/+) NW
        $nareadis = NATARS_MAX + 2;

        switch ($sector) {
            case 1:
                $x_a = (WORLD_MAX - (WORLD_MAX * 2));
                $x_b = 0;
                $y_a = (WORLD_MAX - (WORLD_MAX * 2));
                $y_b = 0;
                $order = "ORDER BY y DESC,x DESC";
                $mmm = rand(-1, -20);
                $x_y = "AND x < -4 AND y < $mmm";
                break;
            case 2:
                $x_a = (WORLD_MAX - (WORLD_MAX * 2));
                $x_b = 0;
                $y_a = 0;
                $y_b = WORLD_MAX;
                $order = "ORDER BY y ASC,x DESC";
                $mmm = rand(1, 20);
                $x_y = "AND x < -4 AND y > $mmm";
                break;
            case 3:
                $x_a = 0;
                $x_b = WORLD_MAX;
                $y_a = 0;
                $y_b = WORLD_MAX;
                $order = "ORDER BY y,x ASC";
                $mmm = rand(1, 20);
                $x_y = "AND x > 4 AND y > $mmm";
                break;
            case 4:
                $x_a = 0;
                $x_b = WORLD_MAX;
                $y_a = (WORLD_MAX - (WORLD_MAX * 2));
                $y_b = 0;
                $order = "ORDER BY y DESC, x ASC";
                $mmm = rand(-1, -20);
                $x_y = "AND x > 4 AND y < $mmm";
                break;
        }

        $q = "SELECT `id` FROM wdata where fieldtype = 3 and occupied = 0 $x_y and (x BETWEEN $x_a AND $x_b) and (y BETWEEN $y_a AND $y_b) AND (SQRT(POW(x,2)+POW(y,2))>" . ($nareadis) . ") $order LIMIT 20";

        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray['id'];
    }

    public function setFieldTaken($id)
    {
        $q = "UPDATE wdata set occupied = 1 where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function addVillage($wid, $uid, $username, $capital)
    {
        $total = count($this->getVillagesID($uid));
        if ($total >= 1) {
            $vname = $username . "\'s village " . ($total + 1);
        } else {
            $vname = $username . "\'s village";
        }

        $time = time();
        $q = "INSERT into vdata (wref, owner, name, capital, pop, cp, celebration, wood, clay, iron, maxstore, crop, maxcrop, lastupdate, created) values
        ('$wid', '$uid', '$vname', '$capital', 2, 1, 0, 780, 780, 780, " . STORAGE_BASE . ", 780, " . STORAGE_BASE . ", '$time', '$time')";
        return mysql_query($q, $this->connection);
    }

    public function getVillagesID($uid)
    {
        $q = "SELECT wref from vdata where owner = $uid order by capital DESC";
        $result = mysql_query($q, $this->connection);
        $array = $this->mysql_fetch_all($result);
        $newarray = array();
        for ($i = 0; $i < count($array); $i++) {
            array_push($newarray, $array[$i]['wref']);
        }
        return $newarray;
    }

    public function addResourceFields($vid, $type)
    {
        switch ($type) {
            case 1:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,4,4,1,4,4,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 2:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,3,4,1,3,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 3:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,1,4,1,3,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 4:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,1,4,1,2,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 5:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,1,4,1,3,1,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 6:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,4,4,1,3,4,4,4,4,4,4,4,4,4,4,4,2,4,4,1,15)";
                break;
            case 7:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,1,4,4,1,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 8:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,3,4,4,1,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 9:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,3,4,4,1,1,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 10:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,3,4,1,2,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
            case 11:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,3,1,1,3,1,4,4,3,3,2,2,3,1,4,4,2,4,4,1,15)";
                break;
            case 12:
                $q = "INSERT into fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values($vid,1,4,1,1,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
                break;
        }
        return mysql_query($q, $this->connection);
    }

    public function populateOasis()
    {
        $q = "SELECT id FROM wdata where oasistype != 0";
        $result = mysql_query($q, $this->connection);
        while ($row = mysql_fetch_array($result)) {
            $this->addUnits($row['id']);
        }
    }

    public function addUnits($vid)
    {
        $q = "INSERT into units (vref) values ($vid)";
        return mysql_query($q, $this->connection);
    }

    /**
     * retrieve type of village via ID
     * References: Village ID
     */
    public function getVillageOasis($list, $limit, $order)
    {
        $wref = $this->getVilWref($order['x'], $order['y']);
        $where = ' WHERE TRUE and conqured = ' . $wref;
        foreach ($list as $k => $v) {
            if ($k != 'extra') $where .= " AND $k = $v ";
        }
        $where .= ' AND ' . $list['extra'] . ' ';
        if (isset($limit)) $limit = " LIMIT $limit ";
        if (isset($order) && $order['by'] != '') $orderby = " ORDER BY " . $order['by'] . ' ';
        $q = 'SELECT ';
        if ($order['by'] == 'distance') {
            $q .= " *,(ROUND(SQRT(POW(LEAST(ABS(" . $order['x'] . " - wdata.x), ABS(" . $order['max'] . " - ABS(" . $order['x'] . " - wdata.x))), 2) + POW(LEAST(ABS(" . $order['y'] . " - wdata.y), ABS(" . $order['max'] . " - ABS(" . $order['y'] . " - wdata.y))), 2)),3)) AS distance FROM ";
        } else {
            $q .= " * FROM ";
        }
        $q .= "odata LEFT JOIN wdata ON wdata.id=odata.wref " . $where . $orderby . $limit;

        return $this->mysql_query($q);
    }

    public function getVilWref($x, $y)
    {
        $q = "SELECT id FROM wdata where x = $x AND y = $y LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['id'];
    }

    public function getVillageType($wref)
    {
        $q = "SELECT fieldtype FROM wdata where id = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        if ($result) {
            $dbarray = mysql_fetch_array($result);
            return $dbarray['fieldtype'];
        } else {
            return false;
        }
    }

    public function checkVilExist($wref)
    {
        $q = "SELECT wref FROM vdata where wref = '$wref' LIMIT 1";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function getVillageState($wref)
    {
        $q = "SELECT oasistype,occupied FROM wdata where id = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if ($dbarray['occupied'] != 0 || $dbarray['oasistype'] != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getVillageStateForSettle($wref)
    {
        $q = "SELECT `oasistype`,`occupied`,`fieldtype` FROM wdata where id = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if ($dbarray['occupied'] == 0 && $dbarray['oasistype'] == 0 && $dbarray['fieldtype'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getProfileVillages($uid)
    {
        $q = "SELECT `wref`,`maxstore`,`maxcrop`,`pop`,`name`,`capital` from vdata where owner = $uid order by pop desc";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getProfileMedal($uid)
    {
        $q = "SELECT id,categorie,plaats,week,img,points from medal where userid = $uid order by id desc";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getProfileMedalAlly($uid)
    {
        $q = "SELECT id,categorie,plaats,week,img,points from allimedal where allyid = $uid order by id desc";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getVillageID($uid)
    {
        $q = "SELECT wref FROM vdata WHERE owner = $uid";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['wref'];
    }

    public function getVillagesList($list, $limit, $order)
    {
        $where = ' WHERE TRUE ';
        foreach ($list as $k => $v) {
            if ($k != 'extra') $where .= " AND $k = $v ";
        }
        if (isset($list['extra'])) $where .= ' AND ' . $list['extra'] . ' ';
        if (isset($limit)) $limit = " LIMIT $limit ";
        if (isset($order) && $order['by'] != '') $orderby = " ORDER BY " . $order['by'] . ' ';
        $q = 'SELECT ';
        if ($order['by'] == 'distance') {
            $q .= " *,(ROUND(SQRT(POW(LEAST(ABS(" . $order['x'] . " - x), ABS(" . $order['max'] . " - ABS(" . $order['x'] . " - x))), 2) + POW(LEAST(ABS(" . $order['y'] . " - y), ABS(" . $order['max'] . " - ABS(" . $order['y'] . " - y))), 2)),3)) AS distance FROM ";
        } else {
            $q .= " * FROM ";
        }
        $q .= "wdata " . $where . $orderby . $limit;
        return $this->query_return($q);
    }

    public function getVillagesListCount($list)
    {
        $where = ' WHERE TRUE ';
        foreach ($list as $k => $v) {
            if ($k != 'extra') $where .= " AND $k = $v ";
        }
        if (isset($list['extra'])) $where .= ' AND ' . $list['extra'] . ' ';
        $q = "SELECT id FROM wdata " . $where;
        $result = mysql_query($q, $this->connection);
        return mysql_num_rows($result);
    }

    public function getOasisV($vid)
    {
        $q = "SELECT `wref` FROM odata where wref = $vid LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getAInfo($id)
    {
        $q = "SELECT `x`,`y` FROM wdata where id = $id LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getOasisField($ref, $field)
    {
        $q = "SELECT $field FROM odata where wref = $ref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    public function setVillageField($ref, $field, $value)
    {
        if ((stripos($field, 'name') !== false) && ($value == '')) return false;
        $q = "UPDATE vdata set $field = '$value' where wref = $ref";
        return mysql_query($q, $this->connection);
    }

    public function setVillageLevel($ref, $field, $value)
    {
        $q = "UPDATE fdata set " . $field . " = '" . $value . "' where vref = " . $ref . "";
        return mysql_query($q, $this->connection);
    }

    public function removeTribeSpecificFields($vref)
    {
        $fields = $this->getResourceLevel($vref);
        $tribeSpecificArray = array(31, 32, 33, 35, 36, 41);
        for ($i = 19; $i <= 40; $i++) {
            if (in_array($fields['f' . $i . 't'], $tribeSpecificArray)) {
                $q = "UPDATE fdata set " . ('f' . $i) . " = '0', " . ('f' . $i . 't') . " = '0' WHERE vref = " . $vref;
                mysql_query($q, $this->connection);
            }
        }
        $q = 'UPDATE units SET u199=0 WHERE `vref`=' . $vref;
        mysql_query($q, $this->connection);
        $q = 'DELETE FROM trapped WHERE `vref`=' . $vref;
        mysql_query($q, $this->connection);
        $q = 'DELETE FROM training WHERE `vref`=' . $vref;
        mysql_query($q, $this->connection);
    }

    public function getAdminLog($limit = 5)
    {
        $q = "SELECT * FROM admin_log ORDER BY id DESC LIMIT $limit";
        return $this->query_return($q);
    }

    public function delAdminLog($id)
    {
        $q = "DELETE FROM admin_log where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function CheckForum($id)
    {
        $q = "SELECT id from forum_cat where alliance = '$id'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function CountCat($id)
    {
        $q = "SELECT count(id) FROM forum_topic where cat = '$id'";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function LastTopic($id)
    {
        $q = "SELECT `id` from forum_topic where cat = '$id' order by post_date";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function check_forumRules($id)
    {
        global $session;
        $q = "SELECT * FROM fpost_rules WHERE forum_id = $id";
        $z = mysql_query($q, $this->connection);
        $row = mysql_fetch_assoc($z);

        $ids = explode(',', $row['players_id']);
        foreach ($ids as $pid) {
            if ($pid == $session->uid) return false;
        }
        $idn = explode(',', $row['players_name']);
        foreach ($idn as $pid) {
            if ($pid == $_SESSION['username']) return false;
        }

        $aid = $session->alliance;
        $ids = explode(',', $row['ally_id']);
        foreach ($ids as $pid) {
            if ($pid == $aid) return false;
        }
        $q = "SELECT `tag` FROM alidata WHERE id = $aid";
        $z = mysql_query($q, $this->connection);
        $rows = mysql_fetch_assoc($z);

        $idn = explode(',', $row['ally_tag']);
        foreach ($idn as $pid) {
            if ($pid == $rows['tag']) return false;
        }

        return true;
    }

    public function CheckLastTopic($id)
    {
        $q = "SELECT id from forum_topic where cat = '$id'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function CheckLastPost($id)
    {
        $q = "SELECT id from forum_post where topic = '$id'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function LastPost($id)
    {
        $q = "SELECT `date`,`owner` from forum_post where topic = '$id'";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function CountTopic($id)
    {
        $q = "SELECT count(id) FROM forum_post where owner = '$id'";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);

        $qs = "SELECT count(id) FROM forum_topic where owner = '$id'";
        $results = mysql_query($qs, $this->connection);
        $rows = mysql_fetch_row($results);
        return $row[0] + $rows[0];
    }

    public function CountPost($id)
    {
        $q = "SELECT count(id) FROM forum_post where topic = '$id'";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function ForumCat($id)
    {
        $q = "SELECT * from forum_cat where alliance = '$id' ORDER BY id";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function ForumCatEdit($id)
    {
        $q = "SELECT * from forum_cat where id = '$id'";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function ForumCatName($id)
    {
        $q = "SELECT forum_name from forum_cat where id = $id";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['forum_name'];
    }

    public function CheckCatTopic($id)
    {
        $q = "SELECT id from forum_topic where cat = '$id'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function CheckResultEdit($alli)
    {
        $q = "SELECT id from forum_edit where alliance = '$alli'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function CheckCloseTopic($id)
    {
        $q = "SELECT close from forum_topic where id = '$id'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['close'];
    }

    public function CheckEditRes($alli)
    {
        $q = "SELECT result from forum_edit where alliance = '$alli'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['result'];
    }

    public function CreatResultEdit($alli, $result)
    {
        $q = "INSERT into forum_edit values (0,'$alli','$result')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function UpdateResultEdit($alli, $result)
    {
        $date = time();
        $q = "UPDATE forum_edit set result = '$result' where alliance = '$alli'";
        return mysql_query($q, $this->connection);
    }

    public function UpdateEditTopic($id, $title, $cat)
    {
        $q = "UPDATE forum_topic set title = '$title', cat = '$cat' where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function UpdateEditForum($id, $name, $des)
    {
        $q = "UPDATE forum_cat set forum_name = '$name', forum_des = '$des' where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function StickTopic($id, $mode)
    {
        $q = "UPDATE forum_topic set stick = '$mode' where id = '$id'";
        return mysql_query($q, $this->connection);
    }

    public function ForumCatTopic($id)
    {
        $q = "SELECT * from forum_topic where cat = '$id' AND stick = '' ORDER BY post_date desc";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function ForumCatTopicStick($id)
    {
        $q = "SELECT * from forum_topic where cat = '$id' AND stick = '1' ORDER BY post_date desc";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function ShowTopic($id)
    {
        $q = "SELECT * from forum_topic where id = '$id'";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function ShowPost($id)
    {
        $q = "SELECT * from forum_post where topic = '$id'";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function ShowPostEdit($id)
    {
        $q = "SELECT * from forum_post where id = '$id'";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function CreatForum($owner, $alli, $name, $des, $area)
    {
        $q = "INSERT into forum_cat values (0,'$owner','$alli','$name','$des','$area')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function CreatTopic($title, $post, $cat, $owner, $alli, $ends)
    {
        $date = time();
        $q = "INSERT into forum_topic values (0,'$title','$post','$date','$date','$cat','$owner','$alli','$ends','','')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function CreatPost($post, $tids, $owner)
    {
        $date = time();
        $q = "INSERT into forum_post values (0,'$post','$tids','$owner','$date')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function UpdatePostDate($id)
    {
        $date = time();
        $q = "UPDATE forum_topic set post_date = '$date' where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function EditUpdateTopic($id, $post)
    {
        $q = "UPDATE forum_topic set post = '$post' where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function EditUpdatePost($id, $post)
    {
        $q = "UPDATE forum_post set post = '$post' where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function LockTopic($id, $mode)
    {
        $q = "UPDATE forum_topic set close = '$mode' where id = '$id'";
        return mysql_query($q, $this->connection);
    }

    public function DeleteCat($id)
    {
        $qs = "DELETE from forum_cat where id = '$id'";
        $q = "DELETE from forum_topic where cat = '$id'";
        mysql_query($qs, $this->connection);
        return mysql_query($q, $this->connection);
    }

    public function DeleteTopic($id)
    {
        $qs = "DELETE from forum_topic where id = '$id'";
        //  $q = "DELETE from forum_post where topic = '$id'";//
        return mysql_query($qs, $this->connection); //
        // mysql_query($q,$this->connection);
    }

    public function DeletePost($id)
    {
        $q = "DELETE from forum_post where id = '$id'";
        return mysql_query($q, $this->connection);
    }

    public function getAllianceName($id)
    {
        if (!$id) return false;
        $q = "SELECT tag from alidata where id = $id";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['tag'];
    }

    public function getAlliancePermission($ref, $field, $mode)
    {
        if (!$mode) {
            $q = "SELECT $field FROM ali_permission where uid = '$ref'";
        } else {
            $q = "SELECT $field FROM ali_permission where username = '$ref'";
        }
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    public function ChangePos($id, $mode)
    { //??S-H=a-d=o-W??//
        $q = "SELECT `forum_area` from forum_cat where id = '$id'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        if ($mode == '-1') {
            $q = "SELECT `id` from forum_cat WHERE forum_area = '" . $dbarray['forum_area'] . "' AND id < '$id' ORDER BY id DESC";
            $result2 = mysql_query($q, $this->connection);
            $dbarray2 = mysql_fetch_assoc($result2);
            if ($dbarray2) {
                $q = "UPDATE forum_cat set id = 0 where id = '" . $dbarray2['id'] . "'";
                mysql_query($q, $this->connection);
                $q = "UPDATE forum_cat set id = -1 where id = '" . $id . "'";
                mysql_query($q, $this->connection);
                $q = "UPDATE forum_cat set id = '" . $id . "' where id = '0'";
                mysql_query($q, $this->connection);
                $q = "UPDATE forum_cat set id = '" . $dbarray2['id'] . "' where id = '-1'";
                mysql_query($q, $this->connection);
            }
        } elseif ($mode == 1) {
            $q = "SELECT * from forum_cat where id > '$id' AND forum_area = '" . $dbarray['forum_area'] . "' LIMIT 0,1";
            $result2 = mysql_query($q, $this->connection);
            $dbarray2 = mysql_fetch_assoc($result2);
            if ($dbarray2) {
                $q = "UPDATE forum_cat set id = 0 where id = '" . $dbarray2['id'] . "'";
                mysql_query($q, $this->connection);
                $q = "UPDATE forum_cat set id = -1 where id = '" . $id . "'";
                mysql_query($q, $this->connection);
                $q = "UPDATE forum_cat set id = '" . $id . "' where id = '0'";
                mysql_query($q, $this->connection);
                $q = "UPDATE forum_cat set id = '" . $dbarray2['id'] . "' where id = '-1'";
                mysql_query($q, $this->connection);
            }
        }
    }

    public function ForumCatAlliance($id)
    {
        $q = "SELECT `alliance` from forum_cat where id = $id";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray['alliance'];
    }

    public function CreatPoll($id, $name, $p1_name, $p2_name, $p3_name, $p4_name)
    {
        $q = "INSERT into forum_poll values ('$id','$name','0','0','0','0','$p1_name','$p2_name','$p3_name','$p4_name','')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function CreatForum_rules($aid, $id, $users_id, $users_name, $alli_id, $alli_name)
    {
        $q = "INSERT into fpost_rules values ('$aid','$id','$users_id','$users_name', '$alli_id','$alli_name')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function setAlliName($aid, $name, $tag)
    {
        if (!$aid) return false;
        $q = "UPDATE alidata set name = '$name', tag = '$tag' where id = $aid";
        return mysql_query($q, $this->connection);
    }

    public function isAllianceOwner($id)
    {
        if (!$id) return false;
        $q = "SELECT id from alidata where leader = '$id'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function aExist($ref, $type)
    {
        $q = "SELECT $type FROM alidata where $type = '$ref'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function createAlliance($tag, $name, $uid, $max)
    {
        $q = "INSERT into alidata values (0,'$name','$tag',$uid,0,0,0,'','',$max,'','','','','','','','')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    /**
     * insert an alliance new
     */
    public function insertAlliNotice($aid, $notice)
    {
        $time = time();
        $q = "INSERT into ali_log values (0,'$aid','$notice',$time)";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    /**
     * delete alliance if empty
     */
    public function deleteAlliance($aid)
    {
        $result = mysql_query("SELECT id FROM users where alliance = $aid");
        $num_rows = mysql_num_rows($result);
        if ($num_rows == 0) {
            $q = "DELETE FROM alidata WHERE id = $aid";
        }
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    /**
     * read all alliance news
     */
    public function readAlliNotice($aid)
    {
        $q = "SELECT * from ali_log where aid = $aid ORDER BY date DESC";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    /**
     * create alliance permissions
     * References: ID, notice, description
     */
    public function createAlliPermissions($uid, $aid, $rank, $opt1, $opt2, $opt3, $opt4, $opt5, $opt6, $opt7, $opt8)
    {

        $q = "INSERT into ali_permission values(0,'$uid','$aid','$rank','$opt1','$opt2','$opt3','$opt4','$opt5','$opt6','$opt7','$opt8')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    /**
     * update alliance permissions
     */
    public function deleteAlliPermissions($uid)
    {
        $q = "DELETE from ali_permission where uid = '$uid'";
        return mysql_query($q, $this->connection);
    }

    /**
     * update alliance permissions
     */
    public function updateAlliPermissions($uid, $aid, $rank, $opt1, $opt2, $opt3, $opt4, $opt5, $opt6, $opt7, $opt8 = 0)
    {
        $q = "UPDATE ali_permission SET rank = '$rank', opt1 = '$opt1', opt2 = '$opt2', opt3 = '$opt3', opt4 = '$opt4', opt5 = '$opt5', opt6 = '$opt6', opt7 = '$opt7', opt8 = '$opt8' where uid = $uid && alliance =$aid";
        return mysql_query($q, $this->connection);
    }

    /**
     * read alliance permissions
     * References: ID, notice, description
     */
    public function getAlliPermissions($uid, $aid)
    {
        $q = "SELECT * FROM ali_permission where uid = $uid && alliance = $aid";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    /**
     * update an alliance description and notice
     * References: ID, notice, description
     */
    public function submitAlliProfile($aid, $notice, $desc)
    {
        if (!$aid) return false;
        $q = "UPDATE alidata SET `notice` = '$notice', `desc` = '$desc' where id = $aid";
        return mysql_query($q, $this->connection);
    }

    public function diplomacyInviteAdd($alli1, $alli2, $type)
    {
        $q = "INSERT INTO diplomacy (alli1,alli2,type,accepted) VALUES ($alli1,$alli2," . (int)intval($type) . ",0)";
        return mysql_query($q, $this->connection);
    }

    public function diplomacyOwnOffers($session_alliance)
    {
        $q = "SELECT * FROM diplomacy WHERE alli1 = $session_alliance AND accepted = 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getAllianceID($name)
    {
        $q = "SELECT id FROM alidata WHERE tag ='" . $this->RemoveXSS($name) . "'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['id'];
    }

    public function RemoveXSS($val)
    {
        return htmlspecialchars($val, ENT_QUOTES);
    }

    public function diplomacyCancelOffer($id)
    {
        $q = "DELETE FROM diplomacy WHERE id = $id";
        return mysql_query($q, $this->connection);
    }

    public function diplomacyInviteAccept($id, $session_alliance)
    {
        $q = "UPDATE diplomacy SET accepted = 1 WHERE id = $id AND alli2 = $session_alliance";
        return mysql_query($q, $this->connection);
    }

    public function diplomacyInviteDenied($id, $session_alliance)
    {
        $q = "DELETE FROM diplomacy WHERE id = $id AND alli2 = $session_alliance";
        return mysql_query($q, $this->connection);
    }

    public function diplomacyInviteCheck($session_alliance)
    {
        $q = "SELECT * FROM diplomacy WHERE alli2 = $session_alliance AND accepted = 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function diplomacyExistingRelationships($session_alliance)
    {
        $q = "SELECT * FROM diplomacy WHERE alli2 = $session_alliance AND accepted = 1";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function diplomacyExistingRelationships2($session_alliance)
    {
        $q = "SELECT * FROM diplomacy WHERE alli1 = $session_alliance AND accepted = 1";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function diplomacyCancelExistingRelationship($id, $session_alliance)
    {
        $q = "DELETE FROM diplomacy WHERE id = $id AND alli2 = $session_alliance";
        return mysql_query($q, $this->connection);
    }

    public function getUserAlliance($id)
    {
        if (!$id) return false;
        $q = "SELECT alidata.tag from users join alidata where users.alliance = alidata.id and users.id = $id";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if ($dbarray['tag'] == "") {
            return "-";
        } else {
            return $dbarray['tag'];
        }
    }

    public function modifyResource($vid, $wood, $clay, $iron, $crop, $mode)
    {
        if (!$mode) {
            $q = "UPDATE vdata set wood = wood - $wood, clay = clay - $clay, iron = iron - $iron, crop = crop - $crop where wref = $vid";
        } else {
            $q = "UPDATE vdata set wood = wood + $wood, clay = clay + $clay, iron = iron + $iron, crop = crop + $crop where wref = $vid";
        }
        return mysql_query($q, $this->connection);
    }

    public function modifyProduction($vid, $woodp, $clayp, $ironp, $cropp, $upkeep)
    {
        $q = "UPDATE vdata set woodp = $woodp, clayp = $clayp, ironp = $ironp, cropp = $cropp, upkeep = $upkeep where wref = $vid";
        return mysql_query($q, $this->connection);
    }

    public function modifyOasisResource($vid, $wood, $clay, $iron, $crop, $mode)
    {
        if (!$mode) {
            $q = "UPDATE odata set wood = wood - $wood, clay = clay - $clay, iron = iron - $iron, crop = crop - $crop where wref = $vid";
        } else {
            $q = "UPDATE odata set wood = wood + $wood, clay = clay + $clay, iron = iron + $iron, crop = crop + $crop where wref = $vid";
        }
        return mysql_query($q, $this->connection);
    }

    public function getFieldType($vid, $field)
    {
        $q = "SELECT f" . $field . "t from fdata where vref = $vid";
        $result = mysql_query($q, $this->connection);
        return mysql_result($result, 0);
    }

    public function getVSumField($uid, $field)
    {
        $q = "SELECT sum(" . $field . ") FROM vdata where owner = $uid";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function updateVillage($vid)
    {
        $time = time();
        $q = "UPDATE vdata set lastupdate = $time where wref = $vid";
        return mysql_query($q, $this->connection);
    }

    public function updateOasis($vid)
    {
        $time = time();
        $q = "UPDATE odata set lastupdated = $time where wref = $vid";
        return mysql_query($q, $this->connection);
    }

    public function setVillageName($vid, $name)
    {
        if ($name == '') return false;
        $q = "UPDATE vdata set name = '$name' where wref = $vid";
        return mysql_query($q, $this->connection);
    }

    public function modifyPop($vid, $pop, $mode)
    {
        if (!$mode) {
            $q = "UPDATE vdata set pop = pop + $pop where wref = $vid";
        } else {
            $q = "UPDATE vdata set pop = pop - $pop where wref = $vid";
        }
        return mysql_query($q, $this->connection);
    }

    public function addCP($ref, $cp)
    {
        $q = "UPDATE vdata set cp = cp + '$cp' where wref = '$ref'";
        return mysql_query($q, $this->connection);
    }

    public function addCel($ref, $cel, $type)
    {
        $q = "UPDATE vdata set celebration = $cel, type= $type where wref = $ref";
        return mysql_query($q, $this->connection);
    }

    public function getCel()
    {
        $time = time();
        $q = "SELECT `wref`,`type`,`owner` FROM vdata where celebration < $time AND celebration != 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getActiveGCel($vref)
    {
        $time = time();
        $q = "SELECT * FROM vdata WHERE vref = $vref AND celebration > $time AND type=2";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function clearCel($ref)
    {
        $q = "UPDATE vdata set celebration = 0, type = 0 where wref = $ref";
        return mysql_query($q, $this->connection);
    }

    public function setCelCp($user, $cp)
    {
        $q = "UPDATE users set cp = cp + $cp where id = $user";
        return mysql_query($q, $this->connection);
    }

    public function getInvitation($uid, $ally)
    {
        $q = "SELECT * FROM ali_invite where uid = $uid AND alliance = $ally";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getInvitation2($uid)
    {
        $q = "SELECT * FROM ali_invite where uid = $uid";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getAliInvitations($aid)
    {
        $q = "SELECT * FROM ali_invite where alliance = $aid && accept = 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function sendInvitation($uid, $alli, $sender)
    {
        $time = time();
        $q = "INSERT INTO ali_invite values (0,$uid,$alli,$sender,$time,0)";
        return mysql_query($q, $this->connection);
    }

    public function removeInvitation($id)
    {
        $q = "DELETE FROM ali_invite where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function delMessage($id)
    {
        $q = "DELETE FROM mdata WHERE id = $id";
        return mysql_query($q, $this->connection);
    }

    public function delNotice($id, $uid)
    {
        $q = "DELETE FROM ndata WHERE id = $id AND uid = $uid";
        return mysql_query($q, $this->connection);
    }

    public function sendMessage($client, $owner, $topic, $message, $send, $alliance, $player, $coor, $report)
    {
        $time = time();
        $q = "INSERT INTO mdata values (0,$client,$owner,'$topic',\"$message\",0,0,$send,$time,0,0,$alliance,$player,$coor,$report)";
        return mysql_query($q, $this->connection);
    }

    public function setArchived($id)
    {
        $q = "UPDATE mdata set archived = 1 where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function setNorm($id)
    {
        $q = "UPDATE mdata set archived = 0 where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function getMessage($id, $mode)
    {
        global $session;
        switch ($mode) {
            case 1:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata WHERE target = $id and send = 0 and archived = 0 ORDER BY time DESC";
                break;
            case 2:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata WHERE owner = $id ORDER BY time DESC";
                break;
            case 3:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata where id = $id";
                break;
            case 4:
                $q = "UPDATE mdata set viewed = 1 where id = $id AND target = $session->uid";
                break;
            case 5:
                $q = "UPDATE mdata set deltarget = 1 ,viewed = 1 where id = $id";
                break;
            case 6:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata where target = $id and send = 0 and archived = 1";
                break;
            case 7:
                $q = "UPDATE mdata set delowner = 1 where id = $id";
                break;
            case 8:
                $q = "UPDATE mdata set deltarget = 1, delowner = 1, viewed = 1 where id = $id";
                break;
            case 9:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata WHERE target = $id and send = 0 and archived = 0 and deltarget = 0 and viewed = 0 ORDER BY time DESC";
                break;
            case 10:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata WHERE owner = $id and delowner = 0 ORDER BY time DESC";
                break;
            case 11:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata where target = $id and send = 0 and archived = 1 and deltarget = 0";
                break;
            case 12:
                $q = "SELECT `id`,`target`,`owner`,`topic`,`message`,`viewed`,`archived`,`send`,`time`,`deltarget`,`delowner`,`alliance`,`player`,`coor`,`report` FROM mdata WHERE target = $id and send = 0 and archived = 0 and deltarget = 0 and viewed = 0 ORDER BY time DESC LIMIT 1";
                break;
        }
        if ($mode <= 3 || $mode == 6 || $mode > 8) {
            $result = mysql_query($q, $this->connection);
            return $this->mysql_fetch_all($result);
        } else {
            return mysql_query($q, $this->connection);
        }
    }

    public function unarchiveNotice($id)
    {
        $q = "UPDATE ndata set `archive` = 0 where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function archiveNotice($id)
    {
        $q = "update ndata set `archive` = 1 where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function removeNotice($id)
    {
        $q = "DELETE FROM ndata where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function noticeViewed($id)
    {
        $q = "UPDATE ndata set viewed = 1 where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function addNotice($uid, $toWref, $ally, $type, $topic, $data, $time = 0)
    {
        if ($time == 0) {
            $time = time();
        }
        $q = "INSERT INTO ndata (id, uid, toWref, ally, topic, ntype, data, time, viewed) values (0,'$uid','$toWref','$ally','$topic',$type,'$data',$time,0)";
        return mysql_query($q, $this->connection);
    }

    public function getNotice($uid)
    {
        $q = "SELECT * FROM ndata where uid = $uid ORDER BY time DESC LIMIT 99";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getNoticeReportBox($uid)
    {
        $q = "SELECT COUNT(`id`) as maxreport FROM ndata where uid = $uid ORDER BY time DESC LIMIT 200";
        $result = mysql_query($q, $this->connection);
        $result = mysql_fetch_assoc($result);
        return $result['maxreport'];
    }

    public function addBuilding($wid, $field, $type, $loop, $time, $master, $level)
    {
        $x = "UPDATE fdata SET f" . $field . "t=" . $type . " WHERE vref=" . $wid;
        mysql_query($x, $this->connection);
        $q = "INSERT into bdata values (0,$wid,$field,$type,$loop,$time,$master,$level)";
        return mysql_query($q, $this->connection);
    }

    public function removeBuilding($d)
    {
        global $building;
        $jobLoopconID = -1;
        $SameBuildCount = 0;
        $jobs = $building->buildArray;
        for ($i = 0; $i < sizeof($jobs); $i++) {
            if ($jobs[$i]['id'] == $d) {
                $jobDeleted = $i;
            }
            if ($jobs[$i]['loopcon'] == 1) {
                $jobLoopconID = $i;
            }
            if ($jobs[$i]['master'] == 1) {
                $jobMaster = $i;
            }
        }
        if (count($jobs) > 1 && ($jobs[0]['field'] == $jobs[1]['field'])) {
            $SameBuildCount = 1;
        }
        if (count($jobs) > 2 && ($jobs[0]['field'] == $jobs[2]['field'])) {
            $SameBuildCount = 2;
        }
        if (count($jobs) > 2 && ($jobs[1]['field'] == $jobs[2]['field'])) {
            $SameBuildCount = 3;
        }
        if (count($jobs) > 2 && ($jobs[0]['field'] == ($jobs[1]['field'] == $jobs[2]['field']))) {
            $SameBuildCount = 4;
        }
        if (count($jobs) > 3 && ($jobs[0]['field'] == ($jobs[1]['field'] == $jobs[3]['field']))) {
            $SameBuildCount = 5;
        }
        if (count($jobs) > 3 && ($jobs[0]['field'] == ($jobs[2]['field'] == $jobs[3]['field']))) {
            $SameBuildCount = 6;
        }
        if (count($jobs) > 3 && ($jobs[1]['field'] == ($jobs[2]['field'] == $jobs[3]['field']))) {
            $SameBuildCount = 7;
        }
        if (count($jobs) > 3 && ($jobs[0]['field'] == $jobs[3]['field'])) {
            $SameBuildCount = 8;
        }
        if (count($jobs) > 3 && ($jobs[1]['field'] == $jobs[3]['field'])) {
            $SameBuildCount = 9;
        }
        if (count($jobs) > 3 && ($jobs[2]['field'] == $jobs[3]['field'])) {
            $SameBuildCount = 10;
        }
        if ($SameBuildCount > 0) {
            if ($SameBuildCount > 3) {
                if ($SameBuildCount == 4 or $SameBuildCount == 5) {
                    if ($jobDeleted == 0) {
                        $uprequire = $building->resourceRequired($jobs[1]['field'], $jobs[1]['type'], 1);
                        $time = $uprequire['time'];
                        $timestamp = $time + time();
                        $q = "UPDATE bdata SET loopcon=0,level=level-1,timestamp=" . $timestamp . " WHERE id=" . $jobs[1]['id'] . "";
                        mysql_query($q, $this->connection);
                    }
                } else if ($SameBuildCount == 6) {
                    if ($jobDeleted == 0) {
                        $uprequire = $building->resourceRequired($jobs[2]['field'], $jobs[2]['type'], 1);
                        $time = $uprequire['time'];
                        $timestamp = $time + time();
                        $q = "UPDATE bdata SET loopcon=0,level=level-1,timestamp=" . $timestamp . " WHERE id=" . $jobs[2]['id'] . "";
                        mysql_query($q, $this->connection);
                    }
                } else if ($SameBuildCount == 7) {
                    if ($jobDeleted == 1) {
                        $uprequire = $building->resourceRequired($jobs[2]['field'], $jobs[2]['type'], 1);
                        $time = $uprequire['time'];
                        $timestamp = $time + time();
                        $q = "UPDATE bdata SET loopcon=0,level=level-1,timestamp=" . $timestamp . " WHERE id=" . $jobs[2]['id'] . "";
                        mysql_query($q, $this->connection);
                    }
                }
                if ($SameBuildCount < 8) {
                    $uprequire1 = $building->resourceRequired($jobs[$jobMaster]['field'], $jobs[$jobMaster]['type'], 2);
                    $time1 = $uprequire1['time'];
                    $timestamp1 = $time1;
                    $q1 = "UPDATE bdata SET level=level-1,timestamp=" . $timestamp1 . " WHERE id=" . $jobs[$jobMaster]['id'] . "";
                    mysql_query($q1, $this->connection);
                } else {
                    $uprequire1 = $building->resourceRequired($jobs[$jobMaster]['field'], $jobs[$jobMaster]['type'], 1);
                    $time1 = $uprequire1['time'];
                    $timestamp1 = $time1;
                    $q1 = "UPDATE bdata SET level=level-1,timestamp=" . $timestamp1 . " WHERE id=" . $jobs[$jobMaster]['id'] . "";
                    mysql_query($q1, $this->connection);
                }
            } else if ($d == $jobs[floor($SameBuildCount / 3)]['id'] || $d == $jobs[floor($SameBuildCount / 2) + 1]['id']) {
                $q = "UPDATE bdata SET loopcon=0,level=level-1,timestamp=" . $jobs[floor($SameBuildCount / 3)]['timestamp'] . " WHERE master = 0 AND id > " . $d . " and (ID=" . $jobs[floor($SameBuildCount / 3)]['id'] . " OR ID=" . $jobs[floor($SameBuildCount / 2) + 1]['id'] . ")";
                mysql_query($q, $this->connection);
            }
        } else {
            if ($jobs[$jobDeleted]['field'] >= 19) {
                $x = "SELECT f" . $jobs[$jobDeleted]['field'] . " FROM fdata WHERE vref=" . $jobs[$jobDeleted]['wid'];
                $result = mysql_query($x, $this->connection);
                $fieldlevel = mysql_fetch_row($result);
                if ($fieldlevel[0] == 0) {
                    $x = "UPDATE fdata SET f" . $jobs[$jobDeleted]['field'] . "t=0 WHERE vref=" . $jobs[$jobDeleted]['wid'];
                    mysql_query($x, $this->connection);
                }
            }
            if (($jobLoopconID >= 0) && ($jobs[$jobDeleted]['loopcon'] != 1)) {
                if (($jobs[$jobLoopconID]['field'] <= 18 && $jobs[$jobDeleted]['field'] <= 18) || ($jobs[$jobLoopconID]['field'] >= 19 && $jobs[$jobDeleted]['field'] >= 19) || sizeof($jobs) < 3) {
                    $uprequire = $building->resourceRequired($jobs[$jobLoopconID]['field'], $jobs[$jobLoopconID]['type']);
                    $x = "UPDATE bdata SET loopcon=0,timestamp=" . (time() + $uprequire['time']) . " WHERE wid=" . $jobs[$jobDeleted]['wid'] . " AND loopcon=1 AND master=0";
                    mysql_query($x, $this->connection);
                }
            }
        }
        $q = "DELETE FROM bdata where id = $d";
        return mysql_query($q, $this->connection);
    }

    public function addDemolition($wid, $field)
    {
        global $building, $village;
        $q = "DELETE FROM bdata WHERE field=$field AND wid=$wid";
        mysql_query($q, $this->connection);
        $uprequire = $building->resourceRequired($field - 1, $village->resarray['f' . $field . 't']);
        $q = "INSERT INTO demolition VALUES (" . $wid . "," . $field . "," . ($this->getFieldLevel($wid, $field) - 1) . "," . (time() + floor($uprequire['time'] / 2)) . ")";
        return mysql_query($q, $this->connection);
    }

    public function getFieldLevel($vid, $field)
    {
        $q = "SELECT f" . $field . " from fdata where vref = $vid";
        $result = mysql_query($q, $this->connection);
        return mysql_result($result, 0);
    }

    public function getDemolition($wid = 0)
    {
        if ($wid) {
            $q = "SELECT `vref`,`buildnumber`,`timetofinish` FROM demolition WHERE vref=" . $wid;
        } else {
            $q = "SELECT `vref`,`buildnumber`,`timetofinish` FROM demolition WHERE timetofinish<=" . time();
        }
        $result = mysql_query($q, $this->connection);
        if (!empty($result)) {
            return $this->mysql_fetch_all($result);
        } else {
            return NULL;
        }
    }

    public function finishDemolition($wid)
    {
        $q = "UPDATE demolition SET timetofinish=0 WHERE vref=" . $wid;
        return mysql_query($q, $this->connection);
    }

    public function delDemolition($wid)
    {
        $q = "DELETE FROM demolition WHERE vref=" . $wid;
        return mysql_query($q, $this->connection);
    }

    public function getJobs($wid)
    {
        $q = "SELECT * FROM bdata where wid = $wid order by ID ASC";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function FinishWoodcutter($wid)
    {
        $time = time() - 1;
        $q = "SELECT id FROM bdata where wid = $wid and type = 1 order by master,timestamp ASC";
        $result = mysql_query($q);
        $dbarray = mysql_fetch_array($result);
        $q = "UPDATE bdata SET timestamp = $time WHERE id = '" . $dbarray['id'] . "'";
        $this->query($q);
    }

    /**
     * do free query
     * References: Query
     */
    public function query($query)
    {
        return mysql_query($query, $this->connection);
    }

    public function FinishCropLand($wid)
    {
        $time = time() - 1;
        $q = "SELECT `id`,`timestamp` FROM bdata where wid = $wid and type = 4 order by master,timestamp ASC";
        $result = mysql_query($q);
        $dbarray = mysql_fetch_assoc($result);
        $q = "UPDATE bdata SET timestamp = $time WHERE id = '" . $dbarray['id'] . "'";
        $this->query($q);
        $q2 = "SELECT `id` FROM bdata where wid = $wid and loopcon = 1 and field <= 18 order by master,timestamp ASC";
        if (mysql_num_rows($q2) > 0) {
            $result2 = mysql_query($q2);
            $dbarray2 = mysql_fetch_assoc($result2);
            $wc_time = $dbarray['timestamp'];
            $q2 = "UPDATE bdata SET timestamp = timestamp - $wc_time WHERE id = '" . $dbarray2['id'] . "'";
            $this->query($q2);
        }
    }

    public function finishBuildings($wid)
    {
        $time = time() - 1;
        $q = "SELECT id FROM bdata where wid = $wid order by master,timestamp ASC";
        $result = mysql_query($q);
        while ($row = mysql_fetch_assoc($result)) {
            $q = "UPDATE bdata SET timestamp = $time WHERE id = '" . $row['id'] . "'";
            $this->query($q);
        }
    }

    public function getMasterJobs($wid)
    {
        $q = "SELECT `id` FROM bdata where wid = $wid and master = 1 order by master,timestamp ASC";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getBuildingByField($wid, $field)
    {
        $q = "SELECT `id` FROM bdata where wid = $wid and field = $field and master = 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getBuildingByType($wid, $type)
    {
        $q = "SELECT `id` FROM bdata where wid = $wid and type = $type and master = 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getDorf1Building($wid)
    {
        $q = "SELECT `timestamp` FROM bdata where wid = $wid and field < 19 and master = 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getDorf2Building($wid)
    {
        $q = "SELECT `timestamp` FROM bdata where wid = $wid and field > 18 and master = 0";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function updateBuildingWithMaster($id, $time, $loop)
    {
        $q = "UPDATE bdata SET master = 0, timestamp = " . $time . ",loopcon = " . $loop . " WHERE id = " . $id . "";
        return mysql_query($q, $this->connection);
    }

    public function getVillageByName($name)
    {
        $name = mysql_real_escape_string($name, $this->connection);
        $q = "SELECT wref FROM vdata where name = '$name' limit 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['wref'];
    }

    /**
     * set accept flag on market
     * References: id
     */
    public function setMarketAcc($id)
    {
        $q = "UPDATE market set accept = 1 where id = $id";
        return mysql_query($q, $this->connection);
    }

    /**
     * send resource to other village
     * Mode 0: Send
     * Mode 1: Cancel
     * References: Wood/ID, Clay, Iron, Crop, Mode
     */
    public function sendResource($wood, $clay, $iron, $crop, $merchant)
    {
        $q = "INSERT INTO send (`wood`, `clay`, `iron`, `crop`, `merchant`) values ($wood,$clay,$iron,$crop,$merchant)";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function sendResourceMORE($wood, $clay, $iron, $crop, $send)
    {
        $q = "INSERT INTO send (`wood`, `clay`, `iron`, `crop`, `send`) values ($wood,$clay,$iron,$crop,$send)";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function removeSend($ref)
    {
        $q = "DELETE FROM send WHERE id = " . $ref;
        mysql_query($q, $this->connection);
    }

    /**
     * get resources back if you delete offer
     * References: VillageRef (vref)
     * Made by: Dzoki
     */
    public function getResourcesBack($vref, $gtype, $gamt)
    {
        //Xtype (1) = wood, (2) = clay, (3) = iron, (4) = crop
        if ($gtype == 1) {
            $q = "UPDATE vdata SET `wood` = `wood` + '$gamt' WHERE wref = $vref";
            return mysql_query($q, $this->connection);
        } else
            if ($gtype == 2) {
            $q = "UPDATE vdata SET `clay` = `clay` + '$gamt' WHERE wref = $vref";
            return mysql_query($q, $this->connection);
        } else
                if ($gtype == 3) {
            $q = "UPDATE vdata SET `iron` = `iron` + '$gamt' WHERE wref = $vref";
            return mysql_query($q, $this->connection);
        } else
                    if ($gtype == 4) {
            $q = "UPDATE vdata SET `crop` = `crop` + '$gamt' WHERE wref = $vref";
            return mysql_query($q, $this->connection);
        }
    }

    /**
     * get info about offered resources
     * References: VillageRef (vref)
     * Made by: Dzoki
     */
    public function getMarketField($vref, $field)
    {
        $q = "SELECT $field FROM market where vref = '$vref'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    public function removeAcceptedOffer($id)
    {
        $q = "DELETE FROM market where id = $id";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    /**
     * add market offer
     * Mode 0: Add
     * Mode 1: Cancel
     * References: Village, Give, Amt, Want, Amt, Time, Alliance, Mode
     */
    public function addMarket($vid, $gtype, $gamt, $wtype, $wamt, $time, $alliance, $merchant, $mode)
    {
        if (!$mode) {
            $q = "INSERT INTO market values (0,$vid,$gtype,$gamt,$wtype,$wamt,0,$time,$alliance,$merchant)";
            mysql_query($q, $this->connection);
            return mysql_insert_id($this->connection);
        } else {
            $q = "DELETE FROM market where id = $gtype and vref = $vid";
            return mysql_query($q, $this->connection);
        }
    }

    /**
     * get market offer
     * References: Village, Mode
     */
    public function getMarket($vid, $mode)
    {
        $alliance = $this->getUserField($this->getVillageField($vid, "owner"), "alliance", 0);
        if (!$mode) {
            $q = "SELECT * FROM market where vref = $vid and accept = 0 ORDER BY id DESC";
        } else {
            $q = "SELECT * FROM market where vref != $vid and alliance = $alliance or vref != $vid and alliance = 0 and accept = 0 ORDER BY id DESC";
        }
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getUserField($ref, $field, $mode)
    {
        if (!$mode) {
            $q = "SELECT $field FROM users where id = '$ref' LIMIT 1";
        } else {
            $q = "SELECT $field FROM users where username = '$ref' LIMIT 1";
        }
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    public function getVillageField($ref, $field)
    {
        $q = "SELECT $field FROM vdata where wref = $ref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    /**
     * get market offer
     * References: ID
     */
    public function getMarketInfo($id)
    {
        $q = "SELECT `vref`,`gtype`,`wtype`,`merchant`,`wamt` FROM market where id = $id";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function setMovementProc($moveid)
    {
        $q = "UPDATE movement set proc = 1 where moveid = $moveid";
        return mysql_query($q, $this->connection);
    }

    /**
     * retrieve used merchant
     * References: Village
     */
    public function totalMerchantUsed($vid)
    {
        //$time = time();
        $q = "SELECT sum(send.merchant) from send, movement where movement.from = $vid and send.id = movement.ref and movement.proc = 0 and sort_type = 0";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        $q2 = "SELECT sum(send.merchant) from send, movement where movement.to = $vid and send.id = movement.ref and movement.proc = 0 and sort_type = 1";
        $result2 = mysql_query($q2, $this->connection);
        $row2 = mysql_fetch_row($result2);
        $q3 = "SELECT sum(merchant) from market where vref = $vid and accept = 0";
        $result3 = mysql_query($q3, $this->connection);
        $row3 = mysql_fetch_row($result3);
        return $row[0] + $row2[0] + $row3[0];
    }

    public function getMovementById($id)
    {
        $q = "SELECT `starttime`,`to`,`from` FROM movement where moveid = " . $id;
        $result = mysql_query($q, $this->connection);
        $array = $this->mysql_fetch_all($result);
        if (count($array) > 0) {
            return $array[0];
        } else {
            return array();
        }
    }

    public function cancelMovement($id, $newfrom, $newto)
    {
        $refstr = '';
        $q = "SELECT ref FROM movement WHERE moveid=$id";
        $amove = $this->query_return($q);
        if (count($amove) > 0) $mov = $amove[0];
        if ($mov['ref'] == 0) {
            $ref = $this->addAttack($newto, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 3, 0, 0, 0);
            $refstr = ',`ref`=' . $ref;
        }
        $q = "UPDATE movement SET `from`=" . $newfrom . ", `to`=" . $newto . ", `sort_type`=4, `endtime`=(" . (2 * time()) . "-`starttime`),`starttime`=" . time() . " " . $refstr . " WHERE moveid = " . $id;
        $this->query($q);
    }

    public function addAttack($vid, $t1, $t2, $t3, $t4, $t5, $t6, $t7, $t8, $t9, $t10, $t11, $type, $ctar1, $ctar2, $spy)
    {
        $q = "INSERT INTO attacks values (0,$vid,$t1,$t2,$t3,$t4,$t5,$t6,$t7,$t8,$t9,$t10,$t11,$type,$ctar1,$ctar2,$spy)";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function getAdvMovement($village)
    {
        $where = "from";
        $q = "SELECT `moveid` FROM movement where movement." . $where . " = $village and sort_type = 9";
        $result = mysql_query($q, $this->connection);
        $array = $this->mysql_fetch_all($result);
        return $array;
    }

    public function getCompletedAdvMovement($village)
    {
        $where = "from";
        $q = "SELECT `moveid` FROM movement where movement." . $where . " = $village and sort_type = 9 and proc = 1";
        $result = mysql_query($q, $this->connection);
        $array = $this->mysql_fetch_all($result);
        return $array;
    }

    public function addA2b($ckey, $timestamp, $to, $t1, $t2, $t3, $t4, $t5, $t6, $t7, $t8, $t9, $t10, $t11, $type)
    {
        $q = "INSERT INTO a2b (ckey,time_check,to_vid,u1,u2,u3,u4,u5,u6,u7,u8,u9,u10,u11,type) VALUES ('$ckey', '$timestamp', '$to', '$t1', '$t2', '$t3', '$t4', '$t5', '$t6', '$t7', '$t8', '$t9', '$t10', '$t11', '$type')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function getA2b($ckey, $check)
    {
        $q = "SELECT * from a2b where ckey = '" . $ckey . "' AND time_check = '" . $check . "'";
        $result = mysql_query($q, $this->connection);
        if ($result) {
            return mysql_fetch_assoc($result);
        } else {
            return false;
        }
    }

    public function removeA2b($ckey, $check)
    {
        $q = "DELETE FROM a2b where ckey = '" . $ckey . "' AND time_check = '" . $check . "'";
        $result = mysql_query($q, $this->connection);
        if ($result) {
            return mysql_fetch_assoc($result);
        } else {
            return false;
        }
    }

    public function addMovement($type, $from, $to, $ref, $data, $endtime)
    {
        $q = "INSERT INTO movement values (0,$type,$from,$to,$ref,'$data'," . time() . ",$endtime,0)";
        return mysql_query($q, $this->connection);
    }

    public function modifyAttack($aid, $unit, $amt)
    {
        $unit = 't' . $unit;
        $q = "SELECT $unit FROM attacks WHERE id = " . $aid;
        $result = mysql_query($q, $this->connection);
        $result = $this->mysql_fetch_all($result);
        $row = $result[0];
        $amt = min($row[$unit], $amt);
        $q = "UPDATE attacks SET `$unit` = `$unit` - $amt WHERE id = $aid";
        return mysql_query($q, $this->connection);
    }

    public function getRanking()
    {
        $q = "SELECT id,username,alliance,ap,apall,dp,dpall,access FROM users WHERE tribe<=3 AND access<" . (INCLUDE_ADMIN ? "10" : "8");
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getBuildList($type, $wid = 0)
    {
        $q = "SELECT `id` FROM bdata WHERE TRUE "
            . ($type ? " AND type = $type " : '')
            . ($wid ? " AND wid = $wid " : '');
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getVRanking()
    {
        $q = "SELECT v.wref,v.name,v.owner,v.pop FROM vdata AS v,users AS u WHERE v.owner=u.id AND u.tribe<=3 AND v.wref != '' AND u.access<" . (INCLUDE_ADMIN ? "10" : "8");
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getARanking()
    {
        $q = "SELECT id,name,tag FROM alidata where id != ''";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getHeroRanking($limit = '')
    {
        $q = "SELECT `uid`,`level`,`experience` FROM hero ORDER BY `experience` DESC $limit";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getAllMember($aid)
    {
        $q = "SELECT `id`,`username`,`timestamp` FROM users where alliance = $aid order  by (SELECT sum(pop) FROM vdata WHERE owner =  users.id) desc";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getUnit($vid)
    {
        $q = "SELECT * FROM units where vref = " . $vid . "";
        $result = mysql_query($q, $this->connection);
        if (!empty($result)) {
            return mysql_fetch_assoc($result);
        } else {
            return NULL;
        }
    }

    public function getHUnit($vid)
    {
        $q = "SELECT hero FROM units where vref = " . $vid . "";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if ($dbarray['hero'] != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getHero($uid = 0, $id = 0, $dead = 2)
    {
        $q = "SELECT * FROM hero WHERE TRUE "
            . ($uid ? " AND uid=$uid " : "")
            . ($id ? " AND id=$id " : "")
            . ($dead == 2 ? "" : " AND dead=$dead ")
            . " LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function modifyHero($uid = 0, $id = 0, $column, $value, $mode = 0)
    {
        $cmd = '';
        switch ($mode) {
            case 0:
                $cmd .= " $column = $value ";
                break;
            case 1:
                $cmd .= " $column = $column + $value ";
                break;
            case 2:
                $cmd .= " $column = $column - $value ";
                break;
            case 3:
                $cmd .= " $column = $column * $value ";
                break;
            case 4:
                $cmd .= " $column = $column / $value ";
                break;
        }

        switch ($column) {
            case 'r0':
            case 'r1':
            case 'r2':
            case 'r3':
            case 'r4':
                $cmd .= " ,rc = 1 ";
                break;
        }
        $q = "UPDATE hero SET $cmd WHERE TRUE "
            . ($uid ? " AND uid = $uid " : '')
            . ($id ? " AND heroid = $id " : '');
        return mysql_query($q, $this->connection);
    }

    public function clearTech($vref)
    {
        $q = "DELETE from tdata WHERE vref = $vref";
        mysql_query($q, $this->connection);
        return $this->addTech($vref);
    }

    public function addTech($vid)
    {
        $q = "INSERT into tdata (vref) values ($vid)";
        return mysql_query($q, $this->connection);
    }

    public function clearABTech($vref)
    {
        $q = "DELETE FROM abdata WHERE vref = $vref";
        mysql_query($q, $this->connection);
        return $this->addABTech($vref);
    }

    public function addABTech($vid)
    {
        $q = "INSERT into abdata (vref) values ($vid)";
        return mysql_query($q, $this->connection);
    }

    public function getABTech($vid)
    {
        $q = "SELECT `vref`,`a1`,`a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10`,`b1`,`b2`,`b3`,`b4`,`b5`,`b6`,`b7`,`b8`,`b9`,`b10` FROM abdata where vref = $vid";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function addResearch($vid, $tech, $time)
    {
        $q = "INSERT into research values (0,$vid,'$tech',$time)";
        return mysql_query($q, $this->connection);
    }

    public function getResearching($vid)
    {
        $q = "SELECT * FROM research where vref = $vid";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function checkIfResearched($vref, $unit)
    {
        $q = "SELECT $unit FROM tdata WHERE vref = $vref";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$unit];
    }

    public function getTech($vid)
    {
        $q = "SELECT * from tdata where vref = $vid";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function getTraining($vid)
    {
        $q = "SELECT `amt`,`unit`,`endat`,`commence`,`id`,`vref`,`pop`,`timestamp`,`eachtime` FROM training where vref = $vid ORDER BY id";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function trainUnit($vid, $unit, $amt, $pop, $each, $commence, $mode)
    {
        global $technology;

        if (!$mode) {
            $barracks = array(1, 2, 3, 11, 12, 13, 14, 21, 22, 31, 32, 33, 34, 41, 42, 43, 44);
            $greatbarracks = array(61, 62, 63, 71, 72, 73, 84, 81, 82, 91, 92, 93, 94, 101, 102, 103, 104);
            $stables = array(4, 5, 6, 15, 16, 23, 24, 25, 26, 35, 36, 45, 46);
            $greatstables = array(64, 65, 66, 75, 76, 83, 84, 85, 86, 95, 96, 105, 106);
            $workshop = array(7, 8, 17, 18, 27, 28, 37, 38, 47, 48);
            $greatworkshop = array(67, 68, 77, 78, 87, 88, 97, 98, 107, 108);
            $residence = array(9, 10, 19, 20, 29, 30, 39, 40, 49, 50);
            $trap = array(199);

            if (in_array($unit, $barracks)) {
                $queued = $technology->getTrainingList(1);
            } elseif (in_array($unit, $stables)) {
                $queued = $technology->getTrainingList(2);
            } elseif (in_array($unit, $workshop)) {
                $queued = $technology->getTrainingList(3);
            } elseif (in_array($unit, $residence)) {
                $queued = $technology->getTrainingList(4);
            } elseif (in_array($unit, $greatbarracks)) {
                $queued = $technology->getTrainingList(5);
            } elseif (in_array($unit, $greatstables)) {
                $queued = $technology->getTrainingList(6);
            } elseif (in_array($unit, $greatworkshop)) {
                $queued = $technology->getTrainingList(7);
            } elseif (in_array($unit, $trap)) {
                $queued = $technology->getTrainingList(8);
            }
            $timestamp = time();

            if ($queued[count($queued) - 1]['unit'] == $unit) {
                $endat = $each * $amt / 1000;
                $q = "UPDATE training SET amt = amt + $amt, timestamp = $timestamp,endat = endat + $endat WHERE id = " . $queued[count($queued) - 1]['id'] . "";
            } else {
                $endat = $timestamp + ($each * $amt / 1000);
                $q = "INSERT INTO training values (0,$vid,$unit,$amt,$pop,$timestamp,$each,$commence,$endat)";
            }
        } else {
            $q = "DELETE FROM training where id = $vid";
        }
        return mysql_query($q, $this->connection);
    }

    public function removeZeroTrain()
    {
        $q = "DELETE FROM training where `unit` <> 0 AND amt <= 0";
        return mysql_query($q, $this->connection);
    }

    public function getHeroTrain($vid)
    {
        $q = "SELECT `id`,`eachtime` from training where vref = $vid and unit = 0";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if (empty($result)) {
            return false;
        } else {
            return $dbarray;
        }
    }

    public function trainHero($vid, $each, $endat, $mode)
    {
        if (!$mode) {
            $time = time();
            $q = "INSERT INTO training values (0, $vid, 0, 1, 6, $time, $each, $time, $endat)";
        } else {
            $q = "DELETE FROM training where id = $vid";
        }
        return mysql_query($q, $this->connection);
    }

    public function updateTraining($id, $trained)
    {
        $time = time();
        $q = "UPDATE training set amt = GREATEST(amt - $trained, 0), timestamp = $time where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function modifyUnit($vref, $unit, $amt, $mode)
    {
        if ($unit == 230) {
            $unit = 30;
        }
        if ($unit == 231) {
            $unit = 31;
        }
        if ($unit == 120) {
            $unit = 20;
        }
        if ($unit == 121) {
            $unit = 21;
        }
        if ($unit == 'hero') {
            $unit = 'hero';
        } else {
            $unit = 'u' . $unit;
        }
        switch ($mode) {
            case 0:
                $q = "SELECT $unit FROM units WHERE vref = $vref";
                $result = mysql_query($q, $this->connection);
                $row = mysql_fetch_assoc($result);
                $amt = min($row[$unit], $amt);
                $q = "UPDATE units set $unit = ($unit - $amt) where vref = $vref";
                break;
            case 1:
                $q = "UPDATE units set $unit = $unit + $amt where vref = $vref";
                break;
            case 2:
                $q = "UPDATE units set $unit = $amt where vref = $vref";
                break;
        }
        return mysql_query($q, $this->connection);
    }

    public function getFilledTrapCount($vref)
    {
        $result = 0;
        $q = "SELECT * FROM trapped WHERE `vref` = $vref";
        $trapped = $this->query_return($q);
        if (count($trapped) > 0) {
            foreach ($trapped as $k => $v) {
                for ($i = 1; $i <= 50; $i++) {
                    if ($v['u' . $i] > 0) $result += $v['u' . $i];
                }
                if ($v['hero'] > 0) $result += 1;
            }
        }
        return $result;
    }

    public function getTrapped($id)
    {
        $q = "SELECT * FROM trapped WHERE `id` = $id";
        $result = mysql_query($q);
        return mysql_fetch_assoc($result);
    }

    public function getTrappedIn($vref)
    {
        $q = "SELECT * from trapped where `vref` = '$vref'";
        return $this->query_return($q);
    }

    public function getTrappedFrom($from)
    {
        $q = "SELECT * from trapped where `from` = '$from'";
        return $this->query_return($q);
    }

    public function addTrapped($vref, $from)
    {
        $id = $this->hasTrapped($vref, $from);
        if (!$id) {
            $q = "INSERT into trapped (vref,`from`) values (" . $vref . "," . $from . ")";
            mysql_query($q, $this->connection);
            $id = mysql_insert_id($this->connection);
        }
        return $id;
    }

    public function hasTrapped($vref, $from)
    {
        $q = "SELECT id FROM trapped WHERE `vref` = $vref AND `from` = $from";
        $result = mysql_query($q, $this->connection);
        $result = mysql_fetch_assoc($result);
        if (isset($result['id'])) {
            return $result['id'];
        } else {
            return false;
        }
    }

    public function modifyTrapped($id, $unit, $amt, $mode)
    {
        if (!$mode) {
            $q = "SELECT $unit FROM trapped WHERE id = $id";
            $result = mysql_query($q, $this->connection);
            $result = $this->mysql_fetch_all($result);
            $row = $result[0];
            $amt = min($row['u' . $unit], $amt);
            $q = "UPDATE trapped set $unit = $unit - $amt where id = $id";
        } else {
            $q = "UPDATE trapped set $unit = $unit + $amt where id = $id";
        }
        mysql_query($q, $this->connection);
    }

    public function removeTrapped($id)
    {
        $q = "DELETE FROM trapped WHERE `id`=$id";
        mysql_query($q, $this->connection);
    }

    public function removeAnimals($id)
    {
        $q = "DELETE FROM enforcement WHERE `id`=$id";
        mysql_query($q, $this->connection);
    }

    public function checkEnforce($vid, $from)
    {
        $q = "SELECT `id` from enforcement where `from` = $from and vref = $vid";
        $result = $this->query_return($q);
        if (count($result)) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function addEnforce($data)
    {
        $q = "INSERT into enforcement (vref,`from`) values (" . $data['to'] . "," . $data['from'] . ")";
        mysql_query($q, $this->connection);
        $id = mysql_insert_id($this->connection);
        $isoasis = $this->isVillageOases($data['from']);
        if ($isoasis) {
            $fromVillage = $this->getOMInfo($data['from']);
        } else {
            $fromVillage = $this->getMInfo($data['from']);
        }
        $fromTribe = $this->getUserField($fromVillage["owner"], "tribe", 0);
        $start = ($fromTribe - 1) * 10 + 1;
        $end = ($fromTribe * 10);
        //add unit
        $j = '1';
        for ($i = $start; $i <= $end; $i++) {
            $this->modifyEnforce($id, $i, $data['t' . $j . ''], 1);
            $j++;
        }
        return mysql_insert_id($this->connection);
    }

    public function getOMInfo($id)
    {
        $q = "SELECT * FROM wdata left JOIN odata ON odata.wref = wdata.id where wdata.id = $id";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getMInfo($id)
    {
        $q = "SELECT * FROM wdata left JOIN vdata ON vdata.wref = wdata.id where wdata.id = $id";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function modifyEnforce($id, $unit, $amt, $mode)
    {
        if ($unit == 'hero') {
            $unit = 'hero';
        } else {
            $unit = 'u' . $unit;
        }
        if (!$mode) {
            $q = "SELECT $unit FROM enforcement WHERE id = $id";
            $result = $this->query_return($q);
            if (isset($result) && !empty($result) && count($result) > 0) {
                $row = $result[0];
                $amt = min($row[$unit], $amt);
                $q = "UPDATE enforcement set $unit = $unit - $amt where id = $id";
                mysql_query($q, $this->connection);
            }
        } else {
            $q = "UPDATE enforcement set $unit = $unit + $amt where id = $id";
            mysql_query($q, $this->connection);
        }
    }

    public function addHeroEnforce($data)
    {
        $q = "INSERT into enforcement (`vref`,`from`,`hero`) values (" . $data['to'] . "," . $data['from'] . ",1)";
        mysql_query($q, $this->connection);
    }

    public function getEnforceArray($id, $mode)
    {
        if (!$mode) {
            $q = "SELECT * from enforcement where id = $id";
        } else {
            $q = "SELECT * from enforcement where `from` = $id";
        }
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function getEnforceVillage($id, $mode)
    {
        if (!$mode) {
            $q = "SELECT * from enforcement where `vref` = '$id'";
        } else {
            $q = "SELECT * from enforcement where `from` = '$id'";
        }
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getOasesEnforce($id)
    {
        $oasisowned = $this->getOasis($id);
        if (!empty($oasisowned) && count($oasisowned) > 0) {
            $inos = '(';
            foreach ($oasisowned as $oo) $inos .= $oo['wref'] . ',';
            $inos = substr($inos, 0, strlen($inos) - 1);
            $inos .= ')';
            $q = "SELECT * FROM enforcement WHERE `from` = '$id' AND `vref` IN " . $inos;
            $result = mysql_query($q, $this->connection);
            return $this->mysql_fetch_all($result);
        } else {
            return null;
        }
    }

    public function getOasis($vid)
    {
        $q = "SELECT `type`,`wref` FROM odata where conqured = $vid";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getVillageMovement($id)
    {
        $vinfo = $this->getVillage($id);
        if (isset($vinfo['owner'])) {
            $vtribe = $this->getUserField($vinfo['owner'], "tribe", 0);
            $movingunits = array();
            $outgoingarray = $this->getMovement(3, $id, 0);
            for ($i = 1; $i <= 10; $i++) $movingunits['u' . (($vtribe - 1) * 10 + $i)] = 0;
            $movingunits['hero'] = 0;
            if (!empty($outgoingarray)) {
                foreach ($outgoingarray as $out) {
                    for ($i = 1; $i <= 10; $i++) {
                        $movingunits['u' . (($vtribe - 1) * 10 + $i)] += $out['t' . $i];
                    }
                    $movingunits['hero'] += $out['t11'];
                }
            }
            $returningarray = $this->getMovement(4, $id, 1);
            if (!empty($returningarray)) {
                foreach ($returningarray as $ret) {
                    if ($ret['attack_type'] != 1) {
                        for ($i = 1; $i <= 10; $i++) {
                            $movingunits['u' . (($vtribe - 1) * 10 + $i)] += $ret['t' . $i];
                        }
                        $movingunits['hero'] += $ret['t11'];
                    }
                }
            }
            $settlerarray = $this->getMovement(5, $id, 0);
            if (!empty($settlerarray)) {
                $movingunits['u' . ($vtribe * 10)] += 3 * count($settlerarray);
            }
            $advarray = $this->getMovement(9, $id, 0);
            if (!empty($advarray)) {
                $movingunits['hero'] += 1;
            }
            return $movingunits;
        } else {
            return array();
        }
    }

    /**
     * retrieve movement of village
     * Type 0: Send Resource
     * Type 1: Send Merchant
     * Type 2: Return Resource
     * Type 3: Attack
     * Type 4: Return
     * Type 5: Settler
     * Type 6: Bounty
     * Type 7: Reinf.
     * Type 9: Adventure
     * Mode 0: Send/Out
     * Mode 1: Recieve/In
     * References: Type, Village, Mode
     */
    public function getMovement($type, $village, $mode)
    {
        //$time = time();
        if (!$mode) {
            $where = "`from`";
        } else {
            $where = "`to`";
        }
        switch ($type) {
            case 0:
                $q = "SELECT * FROM movement, send where movement." . $where . " = $village and movement.ref = send.id and movement.proc = 0 and movement.sort_type = 0";
                break;
            case 1:
                $q = "SELECT * FROM movement, send where movement." . $where . " = $village and movement.ref = send.id and movement.proc = 0 and movement.sort_type = 1";
                break;
            case 2:
                $q = "SELECT * FROM movement where movement." . $where . " = $village and movement.proc = 0 and movement.sort_type = 2";
                break;
            case 3:
                $q = "SELECT * FROM movement, attacks where movement." . $where . " = $village and movement.ref = attacks.id and movement.proc = 0 and movement.sort_type = 3 ORDER BY endtime ASC";
                break;
            case 4:
                $q = "SELECT * FROM movement, attacks where movement." . $where . " = $village and movement.ref = attacks.id and movement.proc = 0 and movement.sort_type = 4 ORDER BY endtime ASC";
                break;
            case 5:
                $q = "SELECT * FROM movement where movement." . $where . " = $village and sort_type = 5 and proc = 0";
                break;
            case 6:
                $q = "SELECT * FROM movement,odata, attacks where odata.conqured = $village and movement.to = odata.wref and movement.ref = attacks.id and movement.proc = 0 and movement.sort_type = 3 ORDER BY endtime ASC";
                break;
            case 9:
                $q = "SELECT * FROM movement where movement." . $where . " = $village and sort_type = 9 and proc = 0";
                break;
            case 34:
                $q = "SELECT * FROM movement, attacks where (movement." . $where . " = $village and movement.ref = attacks.id and movement.proc = 0) and (movement.sort_type = 3 or  movement.sort_type = 4) ORDER BY endtime ASC";
                break;
        }
        $result = mysql_query($q, $this->connection);
        $array = $this->mysql_fetch_all($result);
        return $array;
    }

    public function getVillageMovementArray($id)
    {
        $movingarray = array();
        $outgoingarray = $this->getMovement(3, $id, 0);
        if (!empty($outgoingarray)) $movingarray = array_merge($movingarray, $outgoingarray);
        $returningarray = $this->getMovement(4, $id, 1);
        if (!empty($returningarray)) $movingarray = array_merge($movingarray, $returningarray);
        return $movingarray;
    }

    public function getWW()
    {
        $q = "SELECT vref FROM fdata WHERE f99t = 40";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get world wonder level!
     * Made by: Dzoki
     */
    public function getWWLevel($vref)
    {
        $q = "SELECT f99 FROM fdata WHERE vref = $vref";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['f99'];
    }

    /**
     * get world wonder owner ID!
     * Made by: Dzoki
     */
    public function getWWOwnerID($vref)
    {
        $q = "SELECT owner FROM vdata WHERE wref = $vref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['owner'];
    }

    /**
     * get user alliance name!
     * Made by: Dzoki
     */
    public function getUserAllianceID($id)
    {
        $q = "SELECT alliance FROM users where id = $id LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['alliance'];
    }

    /**
     * get WW name
     * Made by: Dzoki
     */
    public function getWWName($vref)
    {
        $q = "SELECT wwname FROM fdata WHERE vref = $vref";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['wwname'];
    }

    /**
     * change WW name
     * Made by: Dzoki
     */
    public function submitWWname($vref, $name)
    {
        $q = "UPDATE fdata SET `wwname` = '$name' WHERE fdata.`vref` = $vref";
        return mysql_query($q, $this->connection);
    }

    public function modifyCommence($id, $commence = 0)
    {
        if ($commence == 0) $commence = time();
        $q = "UPDATE training set commence = $commence WHERE id=$id";
        return mysql_query($q, $this->connection);
    }

    public function getTrainingList()
    {
        $q = "SELECT `id`,`vref`,`unit`,`eachtime`,`endat`,`commence`,`amt` FROM training where amt != 0 LIMIT 500";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getNeedDelete()
    {
        $time = time();
        $q = "SELECT uid FROM deleting where timestamp <= $time";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function countUser()
    {
        $q = "SELECT count(id) FROM users where id != 0";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function countAlli()
    {
        $q = "SELECT count(id) FROM alidata where id != 0";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    //MARKET FIXES
    public function getWoodAvailable($wref)
    {
        $q = "SELECT wood FROM vdata WHERE wref = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['wood'];
    }

    public function getClayAvailable($wref)
    {
        $q = "SELECT clay FROM vdata WHERE wref = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['clay'];
    }

    public function getIronAvailable($wref)
    {
        $q = "SELECT iron FROM vdata WHERE wref = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['iron'];
    }

    public function getCropAvailable($wref)
    {
        $q = "SELECT crop FROM vdata WHERE wref = $wref LIMIT 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['crop'];
    }

    public function poulateOasisdata()
    {
        $q2 = "SELECT id FROM wdata where oasistype != 0";
        $result2 = mysql_query($q2, $this->connection);
        while ($row = mysql_fetch_array($result2)) {
            $wid = $row['id'];
            $time = time();
            $t1 = 750 * SPEED / 10;
            $t2 = 750 * SPEED / 10;
            $t3 = 750 * SPEED / 10;

            $t4 = 800 * SPEED / 10;
            $t5 = 750 * SPEED / 10;
            $t6 = 800 * SPEED / 10;

            $tt = "$t1,$t2,$t3,0,0,0,$t4,$t5,0,$t6,$time,$time,$time";
            $basearray = $this->getOMInfo($wid);
            //We switch type of oasis and instert record with apropriate infomation.
            $q = "INSERT into odata VALUES ('" . $basearray['id'] . "'," . $basearray['oasistype'] . ",0," . $tt . ",100,3,'Unoccupied oasis')";
            mysql_query($q, $this->connection);
        }
    }

    public function getAvailableExpansionTraining()
    {
        global $building, $session, $technology, $village;
        $q = "SELECT (IF(exp1=0,1,0)+IF(exp2=0,1,0)+IF(exp3=0,1,0)) FROM vdata WHERE wref = $village->wid";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        $maxslots = $row[0];
        $residence = $building->getTypeLevel(25);
        $palace = $building->getTypeLevel(26);
        if ($residence > 0) {
            $maxslots -= (3 - floor($residence / 10));
        }
        if ($palace > 0) {
            $maxslots -= (3 - floor(($palace - 5) / 5));
        }

        $q = "SELECT (u10+u20+u30) FROM units WHERE vref = $village->wid";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        $settlers = $row[0];
        $q = "SELECT (u9+u19+u29) FROM units WHERE vref = $village->wid";
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        $chiefs = $row[0];

        $settlers += 3 * count($this->getMovement(5, $village->wid, 0));
        $current_movement = $this->getMovement(3, $village->wid, 0);
        if (!empty($current_movement)) {
            foreach ($current_movement as $build) {
                $settlers += $build['t10'];
                $chiefs += $build['t9'];
            }
        }
        $current_movement = $this->getMovement(3, $village->wid, 1);
        if (!empty($current_movement)) {
            foreach ($current_movement as $build) {
                $settlers += $build['t10'];
                $chiefs += $build['t9'];
            }
        }
        $current_movement = $this->getMovement(4, $village->wid, 0);
        if (!empty($current_movement)) {
            foreach ($current_movement as $build) {
                $settlers += $build['t10'];
                $chiefs += $build['t9'];
            }
        }
        $current_movement = $this->getMovement(4, $village->wid, 1);
        if (!empty($current_movement)) {
            foreach ($current_movement as $build) {
                $settlers += $build['t10'];
                $chiefs += $build['t9'];
            }
        }
        $q = "SELECT (u10+u20+u30) FROM enforcement WHERE `from` = " . $village->wid;
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        if (!empty($row)) {
            foreach ($row as $reinf) {
                $settlers += $reinf[0];
            }
        }
        $q = "SELECT (u10+u20+u30) FROM trapped WHERE `from` = " . $village->wid;
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        if (!empty($row)) {
            foreach ($row as $trapped) {
                $settlers += $trapped[0];
            }
        }
        $q = "SELECT (u9+u19+u29) FROM enforcement WHERE `from` = " . $village->wid;
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        if (!empty($row)) {
            foreach ($row as $reinf) {
                $chiefs += $reinf[0];
            }
        }
        $q = "SELECT (u9+u19+u29) FROM trapped WHERE `from` = " . $village->wid;
        $result = mysql_query($q, $this->connection);
        $row = mysql_fetch_row($result);
        if (!empty($row)) {
            foreach ($row as $trapped) {
                $chiefs += $trapped[0];
            }
        }
        $trainlist = $technology->getTrainingList(4);
        if (!empty($trainlist)) {
            foreach ($trainlist as $train) {
                if ($train['unit'] % 10 == 0) {
                    $settlers += $train['amt'];
                }
                if ($train['unit'] % 10 == 9) {
                    $chiefs += $train['amt'];
                }
            }
        }
        // trapped settlers/chiefs calculation required

        $settlerslots = $maxslots * 3 - $settlers - $chiefs * 3;
        $chiefslots = $maxslots - $chiefs - floor(($settlers + 2) / 3);

        if (!$technology->getTech(($session->tribe - 1) * 10 + 9)) {
            $chiefslots = 0;
        }
        $slots = array("chiefs" => $chiefslots, "settlers" => $settlerslots);
        return $slots;
    }

    public function addArtefact($vref, $owner, $type, $size, $name, $desc, $effecttype, $effect, $aoe, $img)
    {
        $q = "INSERT INTO `artefacts` (`vref`, `owner`, `type`, `size`, `conquered`, `name`, `desc`, `effecttype`, `effect`, `aoe`, `img`) VALUES ('$vref', '$owner', '$type', '$size', '" . time() . "', '$name', '$desc', '$effecttype', '$effect', '$aoe', '$img')";
        return mysql_query($q, $this->connection);
    }

    public function getOwnArtefactInfo($vref)
    {
        $q = "SELECT * FROM artefacts WHERE vref = $vref";
        return $this->query_return($q);
    }

    public function getArtefactInfo($sizes)
    {
        if (count($sizes) != 0) {
            $sizestr = ' AND ( FALSE ';
            foreach ($sizes as $s) {
                $sizestr .= ' OR `artefacts`.`size` = ' . $s . ' ';
            }
            $sizestr .= ' ) ';
        } else {
            $sizestr = '';
        }
        $q = "SELECT * FROM artefacts WHERE true " . $sizestr . ' ORDER BY type';
        return $this->query_return($q);
    }

    public function getArtefactInfoByDistance($coor, $distance, $sizes)
    {
        if (count($sizes) != 0) {
            $sizestr = ' AND ( FALSE ';
            foreach ($sizes as $s) {
                $sizestr .= ' OR artefacts.size = ' . $s . ' ';
            }
            $sizestr .= ' ) ';
        } else {
            $sizestr = '';
        }
        $q = "SELECT *,"
            . " (ROUND(SQRT(POW(LEAST(ABS(" . $coor['x'] . " - wdata.x), ABS(" . $coor['max'] . " - ABS(" . $coor['x'] . " - wdata.x))), 2) + POW(LEAST(ABS(" . $coor['y'] . " - wdata.y), ABS(" . $coor['max'] . " - ABS(" . $coor['y'] . " - wdata.y))), 2)),3)) AS distance "
            . " FROM wdata, artefacts WHERE artefacts.vref = wdata.id"
            . " AND (ROUND(SQRT(POW(LEAST(ABS(" . $coor['x'] . " - wdata.x), ABS(" . $coor['max'] . " - ABS(" . $coor['x'] . " - wdata.x))), 2) + POW(LEAST(ABS(" . $coor['y'] . " - wdata.y), ABS(" . $coor['max'] . " - ABS(" . $coor['y'] . " - wdata.y))), 2)),3)) <= " . $distance
            . $sizestr
            . ' ORDER BY distance';
        return $this->query_return($q);
    }

    public function arteIsMine($id, $newvref, $newowner)
    {
        $q = "UPDATE artefacts SET `owner` = " . $newowner . " WHERE id = " . $id;
        $this->query($q);
        $this->captureArtefact($id, $newvref, $newowner);
    }

    public function captureArtefact($id, $newvref, $newowner)
    {
        // get the artefact
        $currentArte = $this->getArtefactDetails($id);

        // set new active artes for new owner

        #---------first inactive large and uinque artes if this currentArte is large/unique
        if ($currentArte['size'] == 2 || $currentArte['size'] == 3) {
            $ulArts = $this->query_return('SELECT * FROM artefacts WHERE `owner`=' . $newowner . ' AND `status`=1 AND `size`<>1');
            if (!empty($ulArts) && count($ulArts) > 0) {
                foreach ($ulArts as $art) $this->query("UPDATE artefacts SET `status` = 2 WHERE id = " . $art['id']);
            }
        }
        #---------then check extra artes
        $vArts = $this->query_return('SELECT * FROM artefacts WHERE `vref`=' . $newvref . ' AND `status`=1');
        if (!empty($vArts) && count($vArts) > 0) {
            foreach ($vArts as $art) $this->query("UPDATE artefacts SET `status` = 2 WHERE id = " . $art['id']);
        } else {
            $uArts = $this->query_return('SELECT * FROM artefacts WHERE `owner`=' . $newowner . ' AND `status`=1 ORDER BY conquered DESC');
            if (!empty($uArts) && count($uArts) > 2) {
                for ($i = 2; $i < count($uArts); $i++) $this->query("UPDATE artefacts SET `status` = 2 WHERE id = " . $uArts[$i]['id']);
            }
        }
        // set currentArte -> owner,vref,conquered,status
        $time = time();
        $q = "UPDATE artefacts SET vref = $newvref, owner = $newowner, conquered = $time, `status` = 1 WHERE id = $id";
        $this->query($q);
        // set new active artes for old user
        if ($currentArte['status'] == 1) {
            #--- get olduser's active artes
            $ouaArts = $this->query_return('SELECT * FROM artefacts WHERE `owner`=' . $currentArte['owner'] . ' AND `status`=1');
            $ouiArts = $this->query_return('SELECT * FROM artefacts WHERE `owner`=' . $currentArte['owner'] . ' AND `status`=2 ORDER BY conquered DESC');
            if (!empty($ouaArts) && count($ouaArts) < 3 && !empty($ouiArts) && count($ouiArts) > 0) {
                $ouiaCount = count($ouiArts);
                for ($i = 0; $i < $ouiaCount; $i++) {
                    $ia = $ouiArts[$i];
                    if (count($ouaArts) < 3) {
                        $accepted = true;
                        foreach ($ouaArts as $aa) {
                            if ($ia['vref'] == $aa['vref']) {
                                $accepted = false;
                                break;
                            }
                            if (($ia['size'] == 2 || $ia['size'] == 3) && ($aa['size'] == 2 || $aa['size'] == 3)) {
                                $accepted = false;
                                break;
                            }
                        }
                        if ($accepted) {
                            $ouaArts[] = $ia;
                            $q = "UPDATE artefacts SET `status` = 1 WHERE id = " . $ia['id'];
                            $this->query($q);
                        }
                    } else {
                        break;
                    }
                }
            }
        }
    }

    public function getArtefactDetails($id)
    {
        $q = "SELECT * FROM artefacts WHERE id = " . $id;
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getHeroFace($uid)
    {
        $q = "SELECT * FROM heroface WHERE uid = " . $uid;
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function addHeroFace($uid, $bread, $ear, $eye, $eyebrow, $face, $hair, $mouth, $nose, $color)
    {

        $q = "INSERT INTO `heroface` (`uid`, `beard`, `ear`, `eye`, `eyebrow`, `face`, `hair`, `mouth`, `nose`, `color`) VALUES ('$uid', '$bread', '$ear', '$eye', '$eyebrow', '$face', '$hair', '$mouth', '$nose', '$color')";
        return mysql_query($q, $this->connection);
    }

    public function modifyHeroFace($uid, $column, $value)
    {
        $hash = md5("$uid" . time());
        $q = "UPDATE heroface SET `$column`='$value',`hash`='$hash' WHERE `uid` = '$uid'";
        return mysql_query($q, $this->connection);
    }

    public function modifyWholeHeroFace($uid, $face, $color, $hair, $ear, $eyebrow, $eye, $nose, $mouth, $beard)
    {
        $hash = md5("$uid" . time());
        $q = "UPDATE heroface SET `face`=$face,`color`=$color,`hair`=$hair,`ear`=$ear,`eyebrow`=$eyebrow,`eye`=$eye,`nose`=$nose,`mouth`=$mouth,`beard`=$beard,`hash`='$hash' WHERE uid = $uid";

        return mysql_query($q, $this->connection);
    }

    public function populateOasisUnitsLow()
    {
        $q2 = "SELECT * FROM wdata where oasistype != 0";
        $result2 = mysql_query($q2, $this->connection);
        while ($row = mysql_fetch_array($result2)) {
            $wid = $row['id'];
            $basearray = $this->getMInfo($wid);
            //each Troop is a Set for oasis type like mountains have rats spiders and snakes fields tigers elphants clay wolves so on stonger one more not so less
            switch ($basearray['oasistype']) {
                case 1:
                case 2:
                    // Oasis Random populate
                    $UP35 = rand(5, 30) * (SPEED / 10);
                    $UP36 = rand(5, 30) * (SPEED / 10);
                    $UP37 = rand(0, 30) * (SPEED / 10);
                    //+25% lumber per hour
                    $q = "UPDATE units SET u35 = u35 +  '" . $UP35 . "', u36 = u36 + '" . $UP36 . "', u37 = u37 + '" . $UP37 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
                case 3:
                    // Oasis Random populate
                    $UP35 = rand(5, 30) * (SPEED / 10);
                    $UP36 = rand(5, 30) * (SPEED / 10);
                    $UP37 = rand(1, 30) * (SPEED / 10);
                    $UP39 = rand(0, 10) * (SPEED / 10);
                    $fil = rand(0, 20);
                    if ($fil == 1) {
                        $UP40 = rand(0, 31) * (SPEED / 10);
                    } else {
                        $UP40 = 0;
                    }
                    //+25% lumber per hour
                    $q = "UPDATE units SET u35 = u35 +  '" . $UP35 . "', u36 = u36 + '" . $UP36 . "', u37 = u37 + '" . $UP37 . "', u39 = u39 + '" . $UP39 . "', u40 = u40 + '" . $UP40 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
                case 4:
                case 5:
                    // Oasis Random populate
                    $UP31 = rand(5, 40) * (SPEED / 10);
                    $UP32 = rand(5, 30) * (SPEED / 10);
                    $UP35 = rand(0, 25) * (SPEED / 10);
                    //+25% lumber per hour
                    $q = "UPDATE units SET u31 = u31 +  '" . $UP31 . "', u32 = u32 + '" . $UP32 . "', u35 = u35 + '" . $UP35 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
                case 6:
                    // Oasis Random populate
                    $UP31 = rand(5, 40) * (SPEED / 10);
                    $UP32 = rand(5, 30) * (SPEED / 10);
                    $UP35 = rand(1, 25) * (SPEED / 10);
                    $UP38 = rand(0, 15) * (SPEED / 10);
                    $fil = rand(0, 20);
                    if ($fil == 1) {
                        $UP40 = rand(0, 31) * (SPEED / 10);
                    } else {
                        $UP40 = 0;
                    }
                    //+25% lumber per hour
                    $q = "UPDATE units SET u31 = u31 +  '" . $UP31 . "', u32 = u32 + '" . $UP32 . "', u35 = u35 + '" . $UP35 . "', u38 = u38 + '" . $UP38 . "', u40 = u40 + '" . $UP40 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
                case 7:
                case 8:
                    // Oasis Random populate
                    $UP31 = rand(5, 40) * (SPEED / 10);
                    $UP32 = rand(5, 30) * (SPEED / 10);
                    $UP34 = rand(0, 25) * (SPEED / 10);
                    //+25% lumber per hour
                    $q = "UPDATE units SET u31 = u31 +  '" . $UP31 . "', u32 = u32 + '" . $UP32 . "', u34 = u34 + '" . $UP34 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
                case 9:
                    // Oasis Random populate
                    $UP31 = rand(5, 40) * (SPEED / 10);
                    $UP32 = rand(5, 30) * (SPEED / 10);
                    $UP34 = rand(1, 25) * (SPEED / 10);
                    $UP37 = rand(0, 15) * (SPEED / 10);
                    $fil = rand(0, 20);
                    if ($fil == 1) {
                        $UP40 = rand(0, 31) * (SPEED / 10);
                    } else {
                        $UP40 = 0;
                    }
                    //+25% lumber per hour
                    $q = "UPDATE units SET u31 = u31 +  '" . $UP31 . "', u32 = u32 + '" . $UP32 . "', u34 = u34 + '" . $UP34 . "', u37 = u37 + '" . $UP37 . "', u40 = u40 + '" . $UP40 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
                case 10:
                case 11:
                    // Oasis Random populate
                    $UP31 = rand(5, 40) * (SPEED / 10);
                    $UP33 = rand(5, 30) * (SPEED / 10);
                    $UP37 = rand(1, 25) * (SPEED / 10);
                    $UP39 = rand(0, 25) * (SPEED / 10);
                    //+25% lumber per hour
                    $q = "UPDATE units SET u31 = u31 +  '" . $UP31 . "', u33 = u33 + '" . $UP33 . "', u37 = u37 + '" . $UP37 . "', u39 = u39 + '" . $UP39 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
                case 12:
                    // Oasis Random populate
                    $UP31 = rand(5, 40) * (SPEED / 10);
                    $UP33 = rand(5, 30) * (SPEED / 10);
                    $UP38 = rand(1, 25) * (SPEED / 10);
                    $UP39 = rand(0, 25) * (SPEED / 10);
                    $fil = rand(0, 20);
                    if ($fil == 1) {
                        $UP40 = rand(0, 31) * (SPEED / 10);
                    } else {
                        $UP40 = 0;
                    }
                    //+25% lumber per hour
                    $q = "UPDATE units SET u31 = u31 +  '" . $UP31 . "', u33 = u33 + '" . $UP33 . "', u38 = u38 + '" . $UP38 . "', u39 = u39 + '" . $UP39 . "', u40 = u40 + '" . $UP40 . "' WHERE vref = '" . $wid . "'";
                    $result = mysql_query($q, $this->connection);
                    break;
            }
        }
    }

    public function hasBeginnerProtection($vid)
    {
        $q = "SELECT u.protect FROM users u,vdata v WHERE u.id=v.owner AND v.wref=" . $vid;
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if (!empty($dbarray)) {
            if (time() < $dbarray[0]) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function addCLP($uid, $clp)
    {
        $q = "UPDATE users set clp = clp + $clp where id = $uid";
        return mysql_query($q, $this->connection);
    }

    public function sendwlcMessage($client, $owner, $topic, $message, $send)
    {
        $q = "INSERT INTO mdata values (0,$client,$owner,'$topic',\"$message\",1,0,$send,time())";
        return mysql_query($q, $this->connection);
    }

    public function getLinks($id)
    {
        $q = 'SELECT `url`,`name` FROM links WHERE `userid` = ' . $id . ' ORDER BY `pos` ASC';
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function removeLinks($id, $uid)
    {
        $q = "DELETE FROM links WHERE `id` = " . $id . " and `userid` = " . $uid . "";
        return mysql_query($q, $this->connection);
    }

    public function getFarmlist($uid)
    {
        $q = 'SELECT id FROM farmlist WHERE owner = ' . $uid . ' ORDER BY name ASC';
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);

        if ($dbarray['id'] != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getRaidList($id)
    {
        $q = "SELECT * FROM raidlist WHERE id = " . $id . "";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getAllAuction()
    {
        $q = "SELECT * FROM auction WHERE finish = 0";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getVilFarmlist($wref)
    {
        $q = 'SELECT id FROM farmlist WHERE wref = ' . $wref . ' ORDER BY wref ASC';
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);

        if ($dbarray['id'] != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delFarmList($id, $owner)
    {
        $q = "DELETE FROM farmlist where id = $id and owner = $owner";
        return mysql_query($q, $this->connection);
    }

    public function delSlotFarm($id)
    {
        $q = "DELETE FROM raidlist where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function createFarmList($wref, $owner, $name)
    {
        $q = "INSERT INTO farmlist (`wref`, `owner`, `name`) VALUES ('$wref', '$owner', '$name')";
        return mysql_query($q, $this->connection);
    }

    public function addSlotFarm($lid, $towref, $x, $y, $distance, $t1, $t2, $t3, $t4, $t5, $t6, $t7, $t8, $t9, $t10)
    {
        $q = "INSERT INTO raidlist (`lid`, `towref`, `x`, `y`, `distance`, `t1`, `t2`, `t3`, `t4`, `t5`, `t6`, `t7`, `t8`, `t9`, `t10`) VALUES ('$lid', '$towref', '$x', '$y', '$distance', '$t1', '$t2', '$t3', '$t4', '$t5', '$t6', '$t7', '$t8', '$t9', '$t10')";
        return mysql_query($q, $this->connection);
    }

    public function editSlotFarm($eid, $lid, $wref, $x, $y, $dist, $t1, $t2, $t3, $t4, $t5, $t6, $t7, $t8, $t9, $t10)
    {
        $q = "UPDATE raidlist set lid = '$lid', towref = '$wref', x = '$x', y = '$y', t1 = '$t1', t2 = '$t2', t3 = '$t3', t4 = '$t4', t5 = '$t5', t6 = '$t6', t7 = '$t7', t8 = '$t8', t9 = '$t9', t10 = '$t10' WHERE id = $eid";
        return mysql_query($q, $this->connection);
    }

    public function removeOases($wref)
    {
        $q = "UPDATE odata SET conqured = 0, owner = 3, name = '" . UNOCCUPIEDOASES . "' WHERE wref = $wref";
        $r = mysql_query($q, $this->connection);
        $q = "UPDATE wdata SET occupied = 0 WHERE id = $wref";
        $r2 = mysql_query($q, $this->connection);
        return ($r && $r2);
    }

    public function getArrayMemberVillage($uid)
    {
        $q = 'SELECT a.wref, a.name, a.capital, b.x, b.y from vdata AS a left join wdata AS b ON b.id = a.wref where owner = ' . $uid . ' order by capital DESC,pop DESC';
        $result = mysql_query($q, $this->connection);
        $array = $this->mysql_fetch_all($result);
        return $array;
    }

    public function getNoticeData($nid)
    {
        $q = "SELECT `data` FROM ndata where id = $nid";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['data'];
    }

    public function getUsersNotice($uid, $ntype = -1, $viewed = -1)
    {
        $q = "SELECT * FROM ndata where uid=$uid ";
        if ($ntype >= 0) {
            $q .= " and ntype=$ntype ";
        }
        if ($viewed >= 0) {
            $q .= " and viewed=$viewed ";
        }
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }

    public function setSilver($uid, $silver, $mode)
    {
        if (!$mode) {
            $q = "UPDATE users set silver = silver - $silver where id = $uid";
            //Used Silver
            $q2 = "UPDATE users set usedsilver = usedsilver+" . $silver . " where id = $uid";
            mysql_query($q2, $this->connection);
        } else {
            $q = "UPDATE users set silver = silver + $silver where id = $uid";
            //Addgold gold
            $q2 = "UPDATE users set Addsilver = Addsilver+" . $silver . " where id = $uid";
            mysql_query($q2, $this->connection);
        }
        return mysql_query($q, $this->connection);
    }

    public function getAuctionSilver($uid)
    {
        $q = "SELECT * FROM auction where uid = $uid and finish = 0";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function delAuction($id)
    {
        $aucData = $this->getAuctionData($id);
        $usedtime = AUCTIONTIME - ($aucData['time'] - time());
        if (($usedtime < (AUCTIONTIME / 10)) && !$aucData['bids']) {
            $this->modifyHeroItem($aucData['itemid'], 'num', $aucData['num'], 1);
            $this->modifyHeroItem($aucData['itemid'], 'proc', 0, 0);
            $q = "DELETE FROM auction where id = $id and finish = 0";
            return mysql_query($q, $this->connection);
        } else {
            return false;
        }
    }

    public function getAuctionData($id)
    {
        $q = "SELECT * FROM auction where id = $id";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function modifyHeroItem($id, $column, $value, $mode)
    {
        // mode=0 set; 1 add; 2 sub; 3 mul; 4 div
        switch ($mode) {
            case 0:
                $cmd = " $column = $value ";
                break;
            case 1:
                $cmd = " $column = $column+$value ";
                break;
            case 2:
                $cmd = " $column = $column-$value ";
                break;
            case 3:
                $cmd = " $column = $column*$value ";
                break;
            case 4:
                $cmd = " $column = $column/$value ";
                break;
        }
        $q = "UPDATE heroitems set $cmd where id = $id";
        $result = mysql_query($q, $this->connection);
        return ($result ? true : false);
    }

    public function getAuctionUser($uid)
    {
        $q = "SELECT * FROM auction where owner = $uid";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function addAuction($owner, $itemid, $btype, $type, $amount)
    {
        $time = time() + AUCTIONTIME;
        $itemData = $this->getHeroItem($itemid);
        if ($amount >= $itemData['num']) {
            $amount = $itemData['num'];
            $this->modifyHeroItem($itemid, 'proc', 1, 0);
        }
        if ($amount <= 0) return false;
        $this->modifyHeroItem($itemid, 'num', $amount, 2);
        switch ($btype) {
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
            case 14:
                $silver = $amount;
                break;
            default:
                $silver = $amount * 100;
                break;
        }
        $q = "INSERT INTO auction (`owner`, `itemid`, `btype`, `type`, `num`, `uid`, `bids`, `silver`, `maxsilver`, `time`, `finish`) VALUES ('$owner', '$itemid', '$btype', '$type', '$amount', 0, 0, '$silver', '$silver', '$time', 0)";
        return mysql_query($q, $this->connection);
    }

    public function getHeroItem($id = 0, $uid = 0, $btype = 0, $type = 0, $proc = 2)
    {
        $q = "SELECT * FROM heroitems WHERE TRUE "
            . ($id ? (" AND id = " . $id) : (""))
            . ($uid ? (" AND uid = " . $uid) : (""))
            . ($btype ? (" AND btype = " . $btype) : (""))
            . ($type ? (" AND type = " . $type) : (""))
            . ($proc != 2 ? (" AND proc = " . $proc) : (""));
        $result = $this->query_return($q);
        if ($id) $result = $result[0];
        return $result;
    }

    public function addBid($id, $uid, $silver, $maxsilver, $time)
    {
        $q = "UPDATE auction set uid = $uid, silver = $silver, maxsilver = $maxsilver, bids = bids + 1, time = $time where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function removeBidNotice($id)
    {
        $q = "DELETE FROM auction where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function addHeroItem($uid, $btype, $type, $num)
    {
        $q = "INSERT INTO heroitems (`uid`, `btype`, `type`, `num`, `proc`) VALUES ('$uid', '$btype', '$type', '$num', 0)";
        return mysql_query($q, $this->connection);
    }

    public function checkHeroItem($uid, $btype, $type = 0, $proc = 2)
    {
        $q = "SELECT id, btype FROM heroitems WHERE TRUE "
            . ($uid ? " AND uid = '$uid'" : '')
            . ($btype ? " AND btype = '$btype'" : '')
            . ($type ? " AND type = '$type'" : '')
            . ($proc != 2 ? " AND proc = '$proc'" : '');
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        if (isset($dbarray['btype'])) {
            return $dbarray['id'];
        } else {
            return false;
        }
    }

    public function editBid($id, $maxsilver, $minsilver)
    {
        $q = "UPDATE auction set maxsilver = $maxsilver, silver = $minsilver where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function getBidData($id)
    {
        $q = "SELECT * FROM auction WHERE id = $id";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getFLData($id)
    {
        $q = "SELECT * FROM farmlist where id = $id";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getHeroField($uid, $field)
    {
        $q = "SELECT " . $field . " FROM hero WHERE uid = $uid";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    public function getCapBrewery($uid)
    {
        $capWref = $this->getVFH($uid);
        if ($capWref) {
            $q = "SELECT * FROM fdata WHERE vref = " . $capWref;
            $result = mysql_query($q, $this->connection);
            if ($result) {
                $dbarray = mysql_fetch_assoc($result);
                if (!empty($dbarray)) {
                    for ($i = 19; $i <= 40; $i++) {
                        if ($dbarray['f' . $i . 't'] == 35) {
                            return $dbarray['f' . $i];
                        }
                    }
                }
            }
        }
        return 0;
    }

    public function getVFH($uid)
    {
        $q = "SELECT wref FROM vdata WHERE owner = $uid and capital = 1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray['wref'];
    }

    public function getNotice2($id, $field)
    {
        $q = "SELECT " . $field . " FROM ndata where `id` = '$id'";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_array($result);
        return $dbarray[$field];
    }

    public function addAdventure($wref, $uid, $time = 0, $dif = 0)
    {
        if ($time == 0) {
            $time = time();
        }
        $sql = mysql_query("SELECT id FROM wdata ORDER BY id DESC LIMIT 1");
        $myvar = mysql_fetch_assoc($sql);

        $lastw = $myvar['id'];
        if (($wref - 10000) <= 10) {
            $w1 = rand(10, ($wref + 10000));
        } elseif (($wref + 10000) >= $lastw) {
            $w1 = rand(($wref - 10000), ($lastw - 10000));
        } else {
            $w1 = rand(($wref - 10000), ($wref + 10000));
        }
        $q = "INSERT into adventure (`wref`, `uid`, `dif`, `time`, `end`) values ('$w1', '$uid', '$dif', '$time', 0)";
        return mysql_query($q, $this->connection);
    }

    public function addHero($uid)
    {
        $time = time();
        $hash = md5($time);
        switch ($this->getUserField($uid, 'tribe', 0)) {
            case 0:
                $cpproduction = 0;
                $speed = 7;
                $rob = 0;
                $fsperpoint = 100;
                $extraresist = 0;
                $vsnatars = 0;
                $autoregen = 10;
                $extraexpgain = 0;
                $accountmspeed = 0;
                $allymspeed = 0;
                $longwaymspeed = 0;
                $returnmspeed = 0;
                break;
            case 1:
                $cpproduction = 5;
                $speed = 6;
                $rob = 0;
                $fsperpoint = 100;
                $extraresist = 4;
                $vsnatars = 25;
                $autoregen = 20;
                $extraexpgain = 0;
                $accountmspeed = 0;
                $allymspeed = 0;
                $longwaymspeed = 0;
                $returnmspeed = 0;
                break;
            case 2:
                $cpproduction = 0;
                $speed = 8;
                $rob = 10;
                $fsperpoint = 90;
                $extraresist = 0;
                $vsnatars = 0;
                $autoregen = 10;
                $extraexpgain = 15;
                $accountmspeed = 0;
                $allymspeed = 0;
                $longwaymspeed = 0;
                $returnmspeed = 0;
                break;
            case 3:
                $cpproduction = 0;
                $speed = 10;
                $rob = 0;
                $fsperpoint = 80;
                $extraresist = 0;
                $vsnatars = 0;
                $autoregen = 10;
                $extraexpgain = 0;
                $accountmspeed = 30;
                $allymspeed = 15;
                $longwaymspeed = 25;
                $returnmspeed = 30;
                break;
            case 4:
                $cpproduction = 0;
                $speed = 7;
                $rob = 0;
                $fsperpoint = 100;
                $extraresist = 0;
                $vsnatars = 0;
                $autoregen = 10;
                $extraexpgain = 0;
                $accountmspeed = 0;
                $allymspeed = 0;
                $longwaymspeed = 0;
                $returnmspeed = 0;
                break;
            case 5:
                $cpproduction = 0;
                $speed = 7;
                $rob = 0;
                $fsperpoint = 100;
                $extraresist = 0;
                $vsnatars = 0;
                $autoregen = 10;
                $extraexpgain = 0;
                $accountmspeed = 0;
                $allymspeed = 0;
                $longwaymspeed = 0;
                $returnmspeed = 0;
                break;
            default:
                $cpproduction = 0;
                $speed = 7;
                $rob = 0;
                $fsperpoint = 100;
                $extraresist = 0;
                $vsnatars = 0;
                $autoregen = 10;
                $extraexpgain = 0;
                $accountmspeed = 0;
                $allymspeed = 0;
                $longwaymspeed = 0;
                $returnmspeed = 0;
                break;
        }
        $q = "INSERT into hero (`uid`, `wref`, `level`, `speed`, `points`, `experience`, `dead`, `health`, `power`, `fsperpoint`, `offBonus`, `defBonus`, `product`, `r0`, `autoregen`, `extraexpgain`, `cpproduction`, `rob`, `extraresist`, `vsnatars`, `accountmspeed`, `allymspeed`, `longwaymspeed`, `returnmspeed`, `lastupdate`, `lastadv`, `hash`) values
				('$uid', 0, 0, '$speed', 0, '0', 0, '100', '0', '$fsperpoint', '0', '0', '4', '1', '$autoregen', '$extraexpgain', '$cpproduction', '$rob', '$extraresist', '$vsnatars', '$accountmspeed', '$allymspeed', '$longwaymspeed', '$returnmspeed', '$time', '0', '$hash')";
        return mysql_query($q, $this->connection);
    }

    // Add new password => mode:0
    // Add new email => mode: 1
    public function addNewProc($uid, $npw, $nemail, $act, $mode)
    {
        $time = time();
        if (!$mode) {
            $q = "INSERT into newproc (uid, npw, act, time, proc) values ('$uid', '$npw', '$act', '$time', 0)";
        } else {
            $q = "INSERT into newproc (uid, nemail, act, time, proc) values ('$uid', '$nemail', '$act', '$time', 0)";
        }

        return mysql_query($q, $this->connection);
    }

    public function checkProcExist($uid)
    {
        $q = "SELECT uid FROM newproc where uid = '$uid' and proc = 0";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return false;
        } else {
            return true;
        }
    }

    public function removeProc($uid)
    {
        $q = "DELETE FROM newproc where uid = $uid";
        return mysql_query($q, $this->connection);
    }

    public function checkBan($uid)
    {
        $q = "SELECT access FROM users WHERE id = $uid LIMIT 1";
        $result = $this->query_return($q);
        if (!empty($result) && ($result[0]['access'] <= 1 /*|| $result[0]['access']>=7*/)) {
            return true;
        } else {
            return false;
        }
    }

    public function getNewProc($uid)
    {
        $q = "SELECT `npw`,`act` FROM newproc WHERE uid = $uid";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result)) {
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }

    public function CheckAdventure($uid, $wref, $end)
    {
        $q = "SELECT `id` FROM adventure WHERE uid = $uid AND wref = $wref AND end = $end";
        $result = mysql_query($q, $this->connection);
        if ($result) {
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }

    public function getAdventure($uid, $wref = 0, $end = 2)
    {
        $q = "SELECT `id`,`dif` FROM adventure WHERE uid = $uid "
            . ($wref != 0 ? " AND wref = '$wref'" : '')
            . ($end != 2 ? " AND end = $end" : '');
        $result = mysql_query($q, $this->connection);
        if ($result) {
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }

    public function editTableField($table, $field, $value, $refField, $ref)
    {
        $q = "UPDATE " . $table . " set $field = '$value' where " . $refField . " = '$ref'";
        return mysql_query($q, $this->connection);
    }

    public function config()
    {
        $q = "SELECT * FROM config";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_array($result);
    }

    public function getAllianceDipProfile($aid, $type)
    {
        $q = "SELECT `alli2` FROM diplomacy WHERE alli1 = '$aid' AND type = '$type' AND accepted = '1'";
        $result = mysql_query($q, $this->connection);
        if (mysql_num_rows($result) == 0) {
            $q2 = "SELECT `alli1` FROM diplomacy WHERE alli2 = '$aid' AND type = '$type' AND accepted = '1'";
            $result2 = mysql_query($q2, $this->connection);
            while ($row = mysql_fetch_array($result2)) {
                $alliance = $this->getAlliance($row['alli1']);
                $text = "";
                $text .= "<a href=allianz.php?aid=" . $alliance['id'] . ">" . $alliance['tag'] . "</a><br> ";
            }
        } else {
            while ($row = mysql_fetch_array($result)) {
                $alliance = $this->getAlliance($row['alli2']);
                $text = "";
                $text .= "<a href=allianz.php?aid=" . $alliance['id'] . ">" . $alliance['tag'] . "</a><br> ";
            }
        }
        if (strlen($text) == 0) {
            $text = "-<br>";
        }
        return $text;
    }

    public function getAlliance($id, $mod = 0)
    {
        if (!$id) return 0;
        switch ($mod) {
            case 0:
                $where = ' id = "' . $id . '"';
                break;
            case 1:
                $where = ' name = "' . $id . '"';
                break;
            case 2:
                $where = ' tag = "' . $id . '"';
                break;
        }
        $q = "SELECT `id`,`tag`,`desc`,`max`,`name`,`notice` from alidata where " . $where;
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function canClaimArtifact($vref, $type)
    {
        $DefenderFields = $this->getResourceLevel($vref);
        for ($i = 19; $i <= 38; $i++) {
            if ($DefenderFields['f' . $i . 't'] == 27) {
                $defcanclaim = FALSE;
                //$defTresuaryLevel = $DefenderFields['f' . $i];
            } else {
                $defcanclaim = TRUE;
            }
        }
        $AttackerFields = $this->getResourceLevel($vref);
        for ($i = 19; $i <= 38; $i++) {
            if ($AttackerFields['f' . $i . 't'] == 27) {
                $attTresuaryLevel = $AttackerFields['f' . $i];
                if ($attTresuaryLevel >= 10) {
                    $villageartifact = TRUE;
                } else {
                    $villageartifact = FALSE;
                }
                if ($attTresuaryLevel == 20) {
                    $accountartifact = TRUE;
                } else {
                    $accountartifact = FALSE;
                }
            }
        }
        if ($type == 1) {
            if ($defcanclaim == TRUE && $villageartifact == TRUE) {
                return TRUE;
            }
        } else if ($type == 2) {
            if ($defcanclaim == TRUE && $accountartifact == TRUE) {
                return TRUE;
            }
        } else if ($type == 3) {
            if ($defcanclaim == TRUE && $accountartifact == TRUE) {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
    {
        if (!isset($pct)) {
            return false;
        }
        $pct /= 100;
        // Get image width and height
        $w = imagesx($src_im);
        $h = imagesy($src_im);
        // Turn alpha blending off
        imagealphablending($src_im, false);
        // Find the most opaque pixel in the image (the one with the smallest alpha value)
        $minalpha = 127;
        for ($x = 0; $x < $w; $x++)
            for ($y = 0; $y < $h; $y++) {
                $alpha = (imagecolorat($src_im, $x, $y) >> 24) & 0xFF;
                if ($alpha < $minalpha) {
                    $minalpha = $alpha;
                }
            }
        //loop through image pixels and modify alpha for each
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                //get current alpha value (represents the TANSPARENCY!)
                $colorxy = imagecolorat($src_im, $x, $y);
                $alpha = ($colorxy >> 24) & 0xFF;
                //calculate new alpha
                if ($minalpha !== 127) {
                    $alpha = 127 + 127 * $pct * ($alpha - 127) / (127 - $minalpha);
                } else {
                    $alpha += 127 * $pct;
                }
                //get the color index with new alpha
                $alphacolorxy = imagecolorallocatealpha($src_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha);
                //set pixel with the new color + opacity
                if (!imagesetpixel($src_im, $x, $y, $alphacolorxy)) {
                    return false;
                }
            }
        }
        // The image copy
        imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
    }

    public function getCropProdstarv($wref)
    {
        global $bid4, $bid8, $bid9;

        $basecrop = $grainmill = $bakery = 0;
        $owner = $this->getVillageField($wref, 'owner');
        $bonus = $this->getUserField($owner, 'b4', 0);

        $buildarray = $this->getResourceLevel($wref);
        $cropholder = array();
        for ($i = 1; $i <= 38; $i++) {
            if ($buildarray['f' . $i . 't'] == 4) {
                array_push($cropholder, 'f' . $i);
            }
            if ($buildarray['f' . $i . 't'] == 8) {
                $grainmill = $buildarray['f' . $i];
            }
            if ($buildarray['f' . $i . 't'] == 9) {
                $bakery = $buildarray['f' . $i];
            }
        }
        $q = "SELECT type FROM `odata` WHERE conqured = $wref";
        $oasis = $this->query_return($q);
        $cropo = 0;
        foreach ($oasis as $oa) {
            switch ($oa['type']) {
                case 3:
                    $cropo += 1;
                    break;
                case 6:
                    $cropo += 1;
                    break;
                case 9:
                    $cropo += 1;
                    break;
                case 10:
                case 11:
                    $cropo += 1;
                    break;
                case 12:
                    $cropo += 2;
                    break;
            }
        }
        for ($i = 0; $i <= count($cropholder) - 1; $i++) {
            $basecrop += $bid4[$buildarray[$cropholder[$i]]]['prod'];
        }
        $crop = $basecrop + $basecrop * 0.25 * $cropo;
        if ($grainmill >= 1 || $bakery >= 1) {
            $crop += $basecrop / 100 * ($bid8[$grainmill]['attri'] + $bid9[$bakery]['attri']);
        }
        if ($bonus > time()) {
            $crop *= 1.25;
        }
        $crop *= SPEED;
        return $crop;
    }

    public function getNatarsProgress()
    {
        $q = "SELECT * FROM natarsprogress";
        $sql = mysql_query($q);
        $result = mysql_fetch_array($sql);
        return $result;
    }

    public function setNatarsProgress($field, $value)
    {
        $q = "UPDATE natarsprogress SET `$field` = '$value'";
        return mysql_query($q, $this->connection);
    }

    public function getNatarsCapital()
    {
        $q = "SELECT `wref` FROM vdata WHERE owner=2 AND capital = 1 ORDER BY created ASC";
        $result = $this->query_return($q);
        return $result[0];
    }

    public function getNatarsWWVillages()
    {
        $q = "SELECT `owner` FROM vdata WHERE owner=2 AND name = 'WW Village' ORDER BY created ASC";
        $result = $this->query_return($q);
        return $result;
    }

    public function addNatarsVillage($wid, $uid, $username, $capital)
    {
        $total = count($this->getVillagesID($uid));
        $vname = sprintf("[%05d] Natars", $total + 1);
        $time = time();
        $q = "INSERT into vdata "
            . " (wref, owner, name, capital, pop, cp, celebration, wood, clay, iron, maxstore, crop, maxcrop, lastupdate, created, natar)"
            . " values ('$wid', '$uid', '$vname', '$capital', 2, 1, 0, 780, 780, 780, 800, 780, 800, '$time', '$time', '1')";
        return mysql_query($q, $this->connection);
    }

    public function instantTrain($vref)
    {
        $q = 'SELECT `id` FROM training WHERE `vref`=' . $vref;
        $count = count($this->query_return($q));
        $q = 'UPDATE training SET `commence`=0,`eachtime`=1,`endat`=0,`timestamp`=0 WHERE `vref`=' . $vref;
        $result = mysql_query($q, $this->connection);
        if ($result) {
            return $count;
        } else {
            return -1;
        }
    }

    public function hasWinner()
    {
        $sql = mysql_query("SELECT vref FROM fdata WHERE f99 = '100' and f99t = '40'");
        $winner = mysql_num_rows($sql);
        return ($winner > 0 ? true : false);
    }

    public function getVillageActiveArte($vref)
    {
        $q = 'SELECT * FROM artefacts WHERE `vref`=' . $vref . ' AND `status`=1 AND `conquered`<=' . (time() - max(86400 / SPEED, 600));
        return $this->query_return($q);
    }

    public function getAccountActiveArte($owner)
    {
        $q = 'SELECT * FROM artefacts WHERE `owner`=' . $owner . ' AND `status`=1 AND `conquered`<=' . (time() - max(86400 / SPEED, 600));
        return $this->query_return($q);
    }

    public function getArtEffMSpeed($wref)
    {
        $artEff = 1;
        $res = $this->getArteEffectByType($wref, 4);
        if ($res != 0) $artEff = $res;
        return $artEff;
    }

    public function getArteEffectByType($wref, $type)
    {
        $artEff = 0;
        $this->updateFoolArtes();
        $vinfo = $this->getVillage($wref);
        if (!empty($vinfo) && isset($vinfo['owner'])) {
            $owner = $vinfo['owner'];
            $q = 'SELECT `vref`,`effect`,`aoe` FROM artefacts WHERE `owner`=' . $owner . ' AND `effecttype`=' . $type . ' AND `status`=1 AND `conquered`<=' . (time() - max(86400 / SPEED, 600)) . ' ORDER BY `conquered` DESC';
            $result = $this->query_return($q);
            if (!empty($result) && count($result) > 0) {
                $i = 0;
                foreach ($result as $r) {
                    if ($r['vref'] == $wref) {
                        return $r['effect'];
                    }
                    if ($r['aoe'] == 3) {
                        return $r['effect'];
                    }
                    $i += 1;
                    if ($i >= 3) break;
                }
            }
        }

        return $artEff;
    }

    public function updateFoolArtes()
    {
        $q = 'SELECT `id`,`size` FROM artefacts WHERE `type`=3 AND `status`=1 AND `conquered`<=' . (time() - max(86400 / SPEED, 600)) . ' AND lastupdate<=' . (time() - max(86400 / SPEED, 600));
        $result = $this->query_return($q);
        if (!empty($result) && count($result) > 0) {
            foreach ($result as $r) {
                $effecttype = rand(3, 9);
                if ($effecttype == 3) $effecttype = 2;
                $aoerand = rand(1, 100);
                if ($aoerand <= 75) {
                    $aoe = 1;
                } elseif ($aoerand <= 95) {
                    $aoe = 2;
                } else {
                    $aoe = 3;
                }
                switch ($effecttype) {
                    case 2:
                        if ($r['size'] == 1) {
                            $effect = rand(100, 500) / 100;
                        } else {
                            $effect = rand(100, 1000) / 100;
                        }
                        break;
                    case 4:
                        if ($r['size'] == 1) {
                            $effect = rand(100, 300) / 100;
                        } else {
                            $effect = rand(100, 600) / 100;
                        }
                        break;
                    case 5:
                        if ($r['size'] == 1) {
                            $effect = rand(100, 1000) / 100;
                        } else {
                            $effect = rand(100, 2000) / 100;
                        }
                        break;
                    case 6:
                        if ($r['size'] == 1) {
                            $effect = rand(50, 100) / 100;
                        } else {
                            $effect = rand(25, 100) / 100;
                        }
                        break;
                    case 7:
                        if ($r['size'] == 1) {
                            $effect = rand(100, 50000) / 100;
                        } else {
                            $effect = rand(100, 100000) / 100;
                        }
                        break;
                    case 8:
                        if ($r['size'] == 1) {
                            $effect = rand(50, 100) / 100;
                        } else {
                            $effect = rand(25, 100) / 100;
                        }
                        break;
                    case 9:
                        if ($r['size'] == 1) {
                            $effect = 1;
                        }
                        break;
                }
                if ($r['size'] == 1 && rand(1, 100) <= 50) {
                    $effect = 1 / $effect;
                }
                $q = 'UPDATE artefacts SET `effecttype`=' . $effecttype . ',`effect`=' . $effect . ',`aoe`=' . $aoe . ' WHERE `id`=' . $r['id'];
                mysql_query($q, $this->connection);
            }
        }
    }

    public function getArtEffDiet($wref)
    {
        $artEff = 1;
        $res = $this->getArteEffectByType($wref, 6);
        if ($res != 0) $artEff = $res;
        return $artEff;
    }

    public function getArtEffGrt($wref)
    {
        $artEff = 0;
        $res = $this->getArteEffectByType($wref, 9);
        if ($res != 0) $artEff = $res;
        return $artEff;
    }

    public function getArtEffArch($wref)
    {
        $artEff = 1;
        $res = $this->getArteEffectByType($wref, 2);
        if ($res != 0) $artEff = $res;
        return $artEff;
    }

    public function getArtEffSpy($wref)
    {
        $artEff = 0;
        $res = $this->getArteEffectByType($wref, 5);
        if ($res != 0) $artEff = $res;
        return $artEff;
    }

    public function getArtEffTrain($wref)
    {
        $artEff = 1;
        $res = $this->getArteEffectByType($wref, 8);
        if ($res != 0) $artEff = $res;
        return $artEff;
    }

    public function getArtEffConf($wref)
    {
        $artEff = 1;
        $res = $this->getArteEffectByType($wref, 7);
        if ($res != 0) $artEff = $res;
        return $artEff;
    }

    public function getArtEffBP($wref)
    {
        $artEff = 0;
        $vinfo = $this->getVillage($wref);
        $owner = $vinfo['owner'];
        $q = 'SELECT `id` FROM artefacts WHERE `owner`=' . $owner . ' AND `effecttype`=11 AND `status`=1 AND `conquered`<=' . (time() - max(86400 / SPEED, 600)) . ' ORDER BY `conquered` DESC';
        $result = $this->query_return($q);
        if (!empty($result) && count($result) > 0) {
            return $artEff = 1;
        }
        return $artEff;
    }

    public function getArtEffAllyBP($uid)
    {
        $artEff = 0;
        $userAlli = $this->getUserField($uid, 'alliance', 0);
        $q = 'SELECT `alli1`,`alli2` FROM diplomacy WHERE alli1=' . $userAlli . ' OR alli2=' . $userAlli . ' AND accepted<>0';
        $diplos = $this->query_return($q);
        $diplos[] = array('alli1' => $userAlli, 'alli2' => $userAlli);
        if (!empty($diplos) && count($diplos) > 0) {
            $al = array();
            foreach ($diplos as $ds) {
                $al[] = $ds['alli1'];
                $al[] = $ds['alli2'];
            }
            $al = array_unique($al);
            $alstr = implode(',', $al);
            $q = 'SELECT `id` FROM users WHERE alliance IN (' . $alstr . ') AND id<>' . $uid;
            $mate = $this->query_return($q);
            if (!empty($mate) && count($mate) > 0) {
                $ml = array();
                foreach ($mate as $ms) {
                    $ml[] = $ms['id'];
                }
                $matestr = implode(',', $ml);
                $q = 'SELECT `id` FROM artefacts WHERE `owner` IN (' . $matestr . ') AND `effecttype`=11 AND `status`=1 AND `conquered`<=' . (time() - max(86400 / SPEED, 600)) . ' ORDER BY `conquered` DESC';
                $result = $this->query_return($q);
                if (!empty($result) && count($result) > 0) {
                    return $artEff = 1;
                }
            }
        }
        return $artEff;
    }

    public function modifyExtraVillage($wid, $column, $value)
    {
        return $this->query("UPDATE vdata SET $column=$column+$value WHERE wref=$wid");
    }

    public function modifyFieldLevel($wid, $field, $level, $mode)
    {
        $b = 'f' . $field;
        if (!$mode) {
            return $this->query("UPDATE fdata SET $b=$b-$level WHERE vref=" . $wid);
        }
        return $this->query("UPDATE fdata SET $b=$b+$level WHERE vref=" . $wid);
    }

    public function modifyFieldType($wid, $field, $type)
    {
        $b = 'f' . $field . 't';
        return $this->query("UPDATE fdata SET $b=$type WHERE vref=" . $wid);
    }

    public function resendact($mail)
    {
        $q = "SELECT `email`, `username`, `password`, `id` from users WHERE email = " . $mail . " LIMIT 0,1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray;
    }

    public function changemail($mail, $id)
    {
        $q = "UPDATE users set email= '$mail' WHERE id ='$id'";
        mysql_query($q, $this->connection);
    }

    public function register2($username, $password, $email, $act, $activateat)
    {
        $time = time();
        if (strtotime(START_TIME) > time()) {
            $time = strtotime(START_TIME);
        }
        $timep = ($time + PROTECTION);
        $rand = rand(8900, 9000);
        $q = "INSERT INTO users (username,password,access,email,timestamp,act,protect,fquest,clp,cp,reg2,activateat) VALUES ('$username', '$password', " . USER . ", '$email', $time, '$act', $timep, '0,0,0,0,0,0,0,0,0,0,0', '$rand', 1, 1,$activateat)";
        if (mysql_query($q, $this->connection)) {
            return mysql_insert_id($this->connection);
        } else {
            return false;
        }
    }

    public function checkname($id)
    {
        $q = "SELECT `username`, `email` FROM users WHERE id = $id  LIMIT 0,1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray;
    }

    public function settribe($tribe, $id)
    {
        $q = "UPDATE users set tribe= '$tribe' WHERE id ='$id' && reg2 = 1";
        mysql_query($q, $this->connection);
    }

    public function checkreg($uid)
    {
        $q = "SELECT `reg2` FROM users WHERE id = '" . $uid . "'  LIMIT 0,1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray;
    }

    public function checkreg2($name)
    {
        $q = "SELECT `reg2` FROM users WHERE username = '" . $name . "'  LIMIT 0,1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray;
    }

    public function checkid($name)
    {
        $q = "SELECT `id` FROM users WHERE username = '" . $name . "'  LIMIT 0,1";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray;
    }

    public function setreg2($id)
    {
        $q = "UPDATE users set reg2= 0 WHERE id ='$id' && reg2 = 1";
        mysql_query($q, $this->connection);
    }

    public function getNotice5($uid)
    {
        $q = "SELECT `id` FROM ndata where uid = $uid and viewed = 0 ORDER BY time DESC LIMIT 1";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function setref($id, $name)
    {
        $q = "INSERT into refrence values (0,'$id','$name')";
        mysql_query($q, $this->connection);
        return mysql_insert_id($this->connection);
    }

    public function getAttackCasualties($time)
    {
        $q = "SELECT `time` FROM general where shown = 1";
        $casualties = 0;
        $res = mysql_query($q);
        while ($general = mysql_fetch_assoc($res)) {
            if (date("j. M", $time) == date("j. M", $general['time'])) {
                $casualties += $general['casualties'];
            }
        }
        return $casualties;
    }

    public function getAttackByDate($time)
    {
        $q = "SELECT `time` FROM general where shown = 1";
        $attack = 0;
        $res = mysql_query($q);
        while ($general = mysql_fetch_assoc($res)) {
            if (date("j. M", $time) == date("j. M", $general['time'])) {
                $attack += 1;
            }
        }
        return $attack * 100;
    }

    public function getStatsinfo($uid, $time, $inf)
    {
        $q = "SELECT `$inf`,`time` FROM stats where owner = " . $uid;
        $t = 0;
        if ($inf == 'rank') {
            $res = mysql_query($q);
            while ($user = mysql_fetch_assoc($res)) {
                if (date("j. M", $time) == date("j. M", $user['time'])) {
                    $t = $user[$inf];
                    break;
                }
            }
        } else {
            $res = mysql_query($q);
            while ($user = mysql_fetch_assoc($res)) {
                if (date("j. M", $time) == date("j. M", $user['time'])) {
                    $t += $user[$inf];
                }
            }
        }
        return $t;
    }

    public function modifyHero2($column, $value, $uid, $mode)
    {
        switch ($mode) {
            case 1:
                $q = "UPDATE hero SET $column = $column + $value WHERE uid = $uid";
                break;
            case 2:
                $q = "UPDATE hero SET $column = $column - $value WHERE uid = $uid";
                break;
            default:
                $q = "UPDATE hero SET $column = $value WHERE uid = $uid";
        }
        return mysql_query($q, $this->connection);
    }

    public function createTradeRoute($uid, $wid, $from, $r1, $r2, $r3, $r4, $start, $deliveries, $merchant, $time)
    {
        $x = "UPDATE users SET gold = gold - 2 WHERE id = " . $uid . "";
        mysql_query($x, $this->connection) or die(mysql_error());
        $q = "INSERT into route values (0,$uid,$wid,$from,$r1,$r2,$r3,$r4,$start,$deliveries,$merchant,'$time')";
        return mysql_query($q, $this->connection) or die(mysql_error());
    }

    public function getTradeRoute($uid)
    {
        $q = "SELECT * FROM route where uid = $uid ORDER BY timestamp ASC";
        $result = mysql_query($q, $this->connection);
        return $this->mysql_fetch_all($result);
    }

    public function getTradeRoute2($id)
    {
        $q = "SELECT * FROM route where id = $id";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray;
    }

    public function getTradeRouteUid($id)
    {
        $q = "SELECT `uid` FROM route where id = $id";
        $result = mysql_query($q, $this->connection);
        $dbarray = mysql_fetch_assoc($result);
        return $dbarray['uid'];
    }

    public function editTradeRoute($id, $column, $value, $mode)
    {
        if (!$mode) {
            $q = "UPDATE route set $column = $value where id = $id";
        } else {
            $q = "UPDATE route set $column = $column + $value where id = $id";
        }
        return mysql_query($q, $this->connection);
    }

    public function deleteTradeRoute($id)
    {
        $q = "DELETE FROM route where id = $id";
        return mysql_query($q, $this->connection);
    }

    public function getHeroData($uid)
    {
        $q = "SELECT * FROM hero WHERE uid = $uid";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function getHeroData2($uid)
    {
        $q = "SELECT `heroid` FROM hero WHERE dead = 0 and uid = $uid LIMIT 0, 1";
        $result = mysql_query($q, $this->connection);
        return mysql_fetch_assoc($result);
    }

    public function getHeroInVillid($uid, $mode)
    {
        $q = "SELECT `wref`, `name` FROM vdata WHERE owner = " . $uid . " ORDER BY owner";
        $result = mysql_query($q);
        while ($row = mysql_fetch_assoc($result)) {
            $q2 = "SELECT `hero` FROM units WHERE vref = " . $row['wref'] . " ORDER BY vref";
            $result2 = mysql_query($q2);
            while ($row2 = mysql_fetch_assoc($result2)) {
                if ($mode) {
                    if ($row2['hero'] == 1) {
                        $name = $row['name'];
                    }
                } else {
                    if ($row2['hero'] == 1) {
                        $name = $row['wref'];
                    }
                }
            }
        }
        return $name;
    }
}

$database = new MYSQL_DB;
