<?php

$query = mysql_query("SELECT id FROM users WHERE username = 'Farm' LIMIT 1");
$result = mysql_fetch_assoc($query);

if (!$result) {
    function generateHash($plainText, $salt = 1)
    {
        $salt = substr($salt, 0, 9);
        return $salt . md5($salt . $plainText);
    }

    $name = "Farm";
    $pw = $act = $generator->generateRandStr(10);
    $email = "Farm@Travian.pw";
    $kid = 2;

    $uid = $database->register2($name, generateHash($pw), $email, $act, time());
    $database->settribe($kid, $uid);
    $frandom0 = rand(0, 3);
    $frandom1 = rand(0, 3);
    $frandom2 = rand(0, 4);
    $frandom3 = rand(0, 3);
    $database->addHeroFace($uid, $frandom0, $frandom1, $frandom2, $frandom3, $frandom3, $frandom2, $frandom1, $frandom0, $frandom2);
    $database->addHero($uid);
    $database->updateUserField($uid, "act", "", 1);
    $account->generateBase($_POST['sector'], $uid, $name);

    $wid = $database->getVFH($uid);
    $database->setreg2($uid);

    for ($count = 1; $count <= 18; $count++) {
        mysql_query("UPDATE fdata SET f" . $id . " =20 WHERE vref = " . $wid . "");
    }
    for ($i = 1; $i <= 1; $i++) {
        $wid = $wid + 1;
        addVillage($wid, $uid, $name, 1, $i);
    }
}
function addVillage($wid, $uid, $name, $capital, $i)
{
    $vname = $name . " Village";
    $time = time();
    $wood = 780 * (SPEED / 10);
    $clay = 780 * (SPEED / 10);
    $iron = 780 * (SPEED / 10);
    $crop = 780 * (SPEED / 10);
    $maxstore = 800 * (SPEED / 10);
    $maxcrop = 800 * (SPEED / 10);
    $q = "INSERT IGNORE into vdata (wref, owner, name, capital, pop, cp, celebration, wood, clay, iron, maxstore, crop, maxcrop, lastupdate, created) values
        ('$wid', '$uid', '$vname', '$capital', " . rand(2, 10) . ", 1, 0, '$wood', '$clay', '$iron', '$maxstore', '$crop', '$maxcrop', '$time', '$time')";
    return mysql_query($q) or die(mysql_error());
}

$villages = array();
if ($_POST['no'] > 0) {
    $no = $_POST['no'];
    $lvl = $_POST['lvl'];
    $no2 = $no + 10;
    $sql = "SELECT * FROM odata WHERE conqured = 0 ORDER BY rand() DESC LIMIT " . $no;
    $result = mysql_query($sql) or die(mysql_error());
    $row = array();
    while ($row2 = mysql_fetch_array($result)) {
        array_push($row, $row2);
    }

    if ((count($row)) < $no) {
        echo "No space to create a given number of villages! <br>";
    } else {
        for ($i = 1; $i <= $no; $i++) {
            if ($_POST['pop'] > 0) {
                $pop = $_POST['pop'];
            } else {
                $pop = 60;
            };

            do {
                $rndo = mt_rand(0, count($row) - 1);
                $oasis = $row[$rndo];
            } while (!is_array($oasis));
            $o = $oasis;

            $wid = $o['wref'];
            $status = 0;
            $query = mysql_query("SELECT id FROM users WHERE username = 'Farm' LIMIT 1");
            $result = mysql_fetch_assoc($query);
            $uid = $result['id'];

            if (true) {
                $database->setFieldTaken($wid);
                $database->addVillage($wid, $uid, 'Farm Village', '0');
                $database->addResourceFields($wid, 1);
                //$database->addUnits($wid);
                $database->addTech($wid);
                $database->addABTech($wid);
                //mysql_query("INSERT INTO fdata(vref) VALUES('".$wid."')"); // this is the fix :|
                for ($count = 1; $count <= 18; $count++) {
                    mysql_query("UPDATE fdata SET f" . $count . " =20 WHERE vref = " . $wid . "");
                }
                //mysql_query("UPDATE units SET u41 = " . (rand(3000, 6000) * SPEED) . ", u42 = " . (rand(4500, 6000) * SPEED) . ", u43 = 10000, u44 = " . (rand(635, 1575) * SPEED) . ", u45 = " . (rand(3600, 5700) * SPEED) . ", u46 = " . (rand(4500, 6000) * SPEED) . ", u47 = " . (rand(1500, 2700) * SPEED) . ", u48 = " . (rand(300, 900) * SPEED) . " , u49 = 0, u50 = 9 WHERE vref = " . $wid . "");
                //mysql_query("UPDATE fdata SET f22t = 27, f22 = 10, f28t = 25, f28 = 10, f19t = 23, f19 = 10, f99t = 40, f26 = 0, f26t = 0, f21 = 1, f21t = 15, f39 = 1, f39t = 16 WHERE vref = " . $wid . "");
                mysql_query("UPDATE vdata SET pop = '" . $pop . "' WHERE wref = '$wid'");
                mysql_query("UPDATE vdata SET name = 'Farm Village' WHERE wref = '$wid'");
                mysql_query("UPDATE vdata SET capital = 1 WHERE wref = '$wid'");
                //mysql_query("UPDATE vdata SET natar = 0 WHERE wref = '$wid'");
                //mysql_query("UPDATE units SET u31 = 0, u32 = 0, u33 = 0, u34 = 0, u35 = 0, u36 = 0, u37 = 0, u38 = 0, u39 = 0, u40 = 0, u41 = 0, u42 = 0, u43 = 0, u44 = 0, u45 = 0, u46 = 0, u47 = 0, u48 = 0, u49 = 0, u50 = 0 WHERE vref = " . $wid . "");
                //mysql_query("INSERT INTO fdata(vref) VALUES('".$wid."')"); // this is the fix :|
                //mysql_query("UPDATE fdata SET f21 = 1, f21t = 15 WHERE vref = " . $wid . "");
            }

            $lvl = 1; // should be removed later?
            mysql_query("UPDATE wdata SET fieldtype = '" . $lvl . "' WHERE id = " . $o['wref']);
            mysql_query("UPDATE wdata SET oasistype = '0' WHERE id = " . $o['wref']);
            mysql_query("UPDATE wdata SET image = 't0' WHERE id = " . $o['wref']);
            mysql_query("UPDATE wdata SET occupied = '1' WHERE id = " . $o['wref']);

            if ($_POST['prod'] > 800) {
                $prod = $_POST['prod'];
            } else {
                $prod = 800;
            };
            if ($_POST['maxs'] > 0) {
                $maxs = $_POST['maxs'];
            } else {
                $maxs = 800;
            };
            mysql_query("UPDATE vdata SET woodp = $prod , clayp = $prod , ironp = $prod , cropp = $prod WHERE wref = " . $wid);
            mysql_query("UPDATE vdata SET wood = $prod , clay = $prod , iron = $prod , crop = $prod WHERE wref = " . $wid);
            mysql_query("UPDATE vdata SET maxstore = $maxs , maxcrop = $maxs WHERE wref = " . $wid);
            mysql_query("DELETE FROM odata WHERE wref = " . $oasis['wref']);
            ///////////////////////
            $row[$rndo] = (string)"";
            array_push($villages, $wid);
        }
        echo "تعداد <b>" . $_POST['no'] . "</b> Village with Product Level <b>" . $_POST['lvl'] . "</b> and population <b>" . $pop . "</b> Added to map. <br> Villages:";
        foreach ($villages as $wid) {
            $result = mysql_query("SELECT * FROM wdata WHERE id = " . $wid);
            $row = mysql_fetch_array($result);
            echo '<a href="karte.php?x=' . $row['x'] . '&y=' . $row['y'] . '">' . $wid . '</a>, ';
        }
        echo "<br><br>";
    }
}
?>
<h2> Adding Farm Villages </h2>
---------------------------------------------------<br>
To add random and random farm villages to the map, specify production levels and number of villages. <br> <br>...
<form action="" method="POST">
    <table>
        <tr>
            <td> Number of Villages </td>
            <td><input type="number" name="no" value="15"></td>
        </tr>
        <tr>
            <td> Population per village </td>
            <td><input type="number" name="pop" value="50"></td>
        </tr>
        <tr>
            <td> Village output per hour </td>
            <td><input type="number" name="prod" value="800"></td>
        </tr>
        <tr>
            <td> Village Warehouse Capacity </td>
            <td><input type="number" name="maxs" value="1600"></td>
        </tr>
        <!--
        <tr><td> Village Product Level </td> <td> <select name="lvl">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            echo '<option value="' . $i . '">Level ' . $i . '</option>';
        }; ?></select></td></tr>
        -->
        <tr>
            <td></td>
            <td><input type="submit" value="انجام" </td>
            </td>
        </tr>
    </table>
</form>
<br>
<strong> Note: </strong> <br>
The value you enter for the warehouse capacity is calculated for the wheat capacity as well. <br>
The amount you produce per hour is considered for wheat, clay, iron and wheat. <br>
The minimum allowable capacity for production is 800. <br>
Try to fit the values you enter. <br>
