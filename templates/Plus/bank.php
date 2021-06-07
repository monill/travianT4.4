<h1 class="titleInHeader">
    <font color=\"#1e9431\"> Travian Bank</font>
    </font>
</h1>
<div class="contentNavi subNavi">
    <div title="" class="container <?php if (isset($_GET['bank'])) {
                                        echo "active";
                                    } else {
                                        echo "normal";
                                    } ?>">
        <div class="background-start">&nbsp;</div>
        <div class="background-end">&nbsp;</div>
        <div class="content">
            <a href="plus.php"><span class="tabItem">Back to Plus</span></a>
        </div>
    </div>
    <div title="" class="container <?php if (isset($_GET['banker'])) {
                                        echo "active";
                                    } else {
                                        echo "normal";
                                    } ?>">
        <div class="background-start">&nbsp;</div>
        <div class="background-end">&nbsp;</div>
        <div class="content">
            <a href="plus.php?banker"><span class="tabItem">Get Gold</span></a>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php

if ($_POST) {
    $gold = filter_var($_POST['gold'], FILTER_SANITIZE_NUMBER_INT);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
    $player = filter_var($_POST['player'], FILTER_SANITIZE_MAGIC_QUOTES);
    $uid = $session->uid;
    include_once("GameEngine/Database/connection.php");
    mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS);
    mysql_select_db(SQL_DB);
    $q = "SELECT `username`,`password`,`boughtgold`,`email` FROM " . TB_PREFIX . "users WHERE id=" . $uid . " LIMIT 1";
    $result = mysql_query($q);
    if (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        $boughtgold = $row['boughtgold'];
        $password2 = $row['password'];
        $username = $row['username'];
        $email = $row['email'];
    }
    if ($gold <= $boughtgold) {
        if ($gold != '' || $gold != 0) {
            if ($gold > 0) {

                include("GameEngine/Database/connectionbank.php");
                $db_connect = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']);
                mysql_select_db($AppConfig['db']['database'], $db_connect);
                $result1 = mysql_query("SELECT `id` FROM bank");
                if (isset($result1)) {
                    while ($row = mysql_fetch_array($result1)) {
                        $id = $row['id'];
                    }
                    $id2 = ($id + 1);
                }
                $p = rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9) . "-" . rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9) . "-" . rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9) . "-" . rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9);

                $sql2 = "INSERT INTO `bank` (`id`, `code`, `gold`,`email`,`account`) VALUES ('{$id2}', '{$p}', '$gold','$email','$username')";

                try {
                    if (!mysql_query($sql2)) {
                        throw new Exception(PL_BANKERROR);
                    } else {
                        $form->addError("gold", PL_DEPTOBANK);
                        $form->addError("gold2", PL_RECIPCODE . " : $p");
                        $topic = sprintf(PL_TRAVIANBANK, "Bank");
                        $message = sprintf(PL_RECIPTEXT, $username, $p, $gold);

                        include_once("GameEngine/Database/connection.php");
                        mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS);
                        mysql_select_db(SQL_DB);
                        mysql_query("UPDATE " . TB_PREFIX . "users SET boughtgold = boughtgold - " . $gold . " WHERE id = '" . $uid . "'");
                        mysql_query("UPDATE " . TB_PREFIX . "users SET gold = gold - " . $gold . " WHERE id = '" . $uid . "'");
                        $database->sendMessage($uid, 4, $topic, $message, 0, 0, 0, 0, 0);

                        $headers = "From: " . ADMIN_EMAIL . "\n";

                        mail($email, $topic, $message, $headers);
                    }
                } catch (Exception $e) {
                    $form->addError("gold", PL_BANKERROR);
                }
            }
        } else {
            $form->addError("gold", PL_ENTERGOLD);
        }
    } else {
        $form->addError("gold", MK_NOTENOUGHGOLD);
    }
}
?>

<div id="silverExchange">
    <h3>Travian Bank System</h3>

    <p>Here you will be able to transfer your purchased gold to other servers.</p>

    <font color=red>Points</font><br>
    <li>
        Your coins are valid for 30 days.
    </li>
    <li>
        <?php echo sprintf(PL_WAGE, '5%'); ?>
    </li>
    <li>
        You can transport and store the maximum amount of coins purchased in this game...
    </li>
    <li>
        <font color="red">If you save your gold and use it on another server, you won't be able to save it again !!!!
        </font>
    </li>

    <br /><br />
    <?php

    $id = $_SESSION['id'];
    include_once("GameEngine/Database/connection.php");
    mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS);
    mysql_select_db(SQL_DB);
    $uid = $session->uid;
    $q = "SELECT `password`,`boughtgold`,`gold` FROM " . TB_PREFIX . "users WHERE id=" . $uid . " LIMIT 1";
    $result = mysql_query($q);
    if (mysql_num_rows($result)) {
        $row = mysql_fetch_assoc($result);
        $boughtgold = $row['boughtgold'];
        $password = $row['password'];
        $gold = $row['gold'];
    }

    $new_gold = min($boughtgold, $gold);

    if ($new_gold != 0) { ?>
        <form action="plus.php?bank" method="post">
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
            <input type="hidden" name="a" value="84">
            <input type="hidden" name="c" value="18a">
        <?php } ?>
        <center>
            <div class="boxes boxesColor gray exchange3">
                <div class="boxes-tl"></div>
                <div class="boxes-tr"></div>
                <div class="boxes-tc"></div>
                <div class="boxes-ml"></div>
                <div class="boxes-mr"></div>
                <div class="boxes-mc"></div>
                <div class="boxes-bl"></div>
                <div class="boxes-br"></div>
                <div class="boxes-bc"></div>
                <div class="boxes-contents">
                    <table cellpadding="1" cellspacing="1" class="exchangeOffice transparent">
                        <tbody>
                            <tr>
                                <td>
                                    <center>
                                        <img src="/assets/images/x.gif" class="gold" alt="<?php echo TRAVIAN ?> Gold" title="Gold">
                                        <?php echo PL_YURBGHTGOLD . ':';
                                        if ($new_gold == 0) {
                                            echo "<font color='red'>0</font>";
                                        } else {
                                            echo $new_gold;
                                        }
                                        ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <center>
                                        <?php if ($new_gold != 0) { ?>
                                            <input name="gold" id="goldInput" type="text" class="text" value="" style="width:120px;" title="<?php echo HDR_GOLD; ?>" maxlength="4">
                                        <?php } else { ?>
                                            <input name="gold" placeholder="<?php echo PL_YDHBGHTGOLD; ?>" id="goldInput" size="80" type="text" class="text" style="width:130px;" value="" title="<?php echo HDR_GOLD; ?>" disabled="disable">
                                        <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                </div>
            </div>

            <p>
                <button type="submit" value="Send" class="green ">
                    <div class="button-container addHoverClick">
                        <div class="button-background">
                            <div class="buttonStart">
                                <div class="buttonEnd">
                                    <div class="buttonMiddle"></div>
                                </div>
                            </div>
                        </div>
                        <div class="button-content"><?php echo SI_SAVE; ?></div>
                    </div>
                </button>
        </center>

        <br />
        <div class="error RTL"><?php echo $form->getError("gold"); ?>
            <br />
            <div class="error RTL"><?php echo $form->getError("gold2"); ?></div>
        </div>
        </p>
        </form>
</div>
<font color="#c5c5c5" size="1" style="left:3px;position:absolute;top:525px">
    Travian System by <b> Of course</b>
</font>