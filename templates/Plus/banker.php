<h1 class="titleInHeader">
    <font color="#1e9431"> Bank Account </font>
</h1>
<div class="contentNavi subNavi">
    <div title="" class="container <?php if (isset($_GET['banker'])) {
                                        echo "active";
                                    } else {
                                        echo "normal";
                                    } ?>">
        <div class="background-start">&nbsp;</div>
        <div class="background-end">&nbsp;</div>
        <div class="content"><a href="plus.php"><span class="tabItem">Back to Plus</span></a>
        </div>
    </div>
    <div title="" class="container <?php if (isset($_GET['bank'])) {
                                        echo "active";
                                    } else {
                                        echo "normal";
                                    } ?>">
        <div class="background-start">&nbsp;</div>
        <div class="background-end">&nbsp;</div>
        <div class="content"><a href="plus.php?bank"><span class="tabItem">Gold Bank</span></a>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php

if ($_POST) {
    $code = filter_var($_POST['code'], FILTER_SANITIZE_NUMBER_INT);
    $uid = $session->uid;
    include("GameEngine/Database/connectionbank.php");
    $db_connect = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']);
    mysql_select_db($AppConfig['db']['database'], $db_connect);
    $result1 = mysql_query("SELECT * FROM bank WHERE code='" . $code . "'");
    if (isset($result1)) {
        while ($row = mysql_fetch_array($result1)) {
            $id = $row['id'];
            $codez = $row['code'];
            $gold = $row['gold'];
        }
    }
    if ($gold > 0) {
        if ($code == $codez) {
            if ($code != '') {
                include_once("GameEngine/Database/connection.php");
                mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS);
                mysql_select_db(SQL_DB);
                $q = "SELECT `gold`,`buygold` FROM " . TB_PREFIX . "users WHERE id=" . $uid . " LIMIT 1";
                $result = mysql_query($q);
                if (mysql_num_rows($result)) {
                    $row = mysql_fetch_assoc($result);
                    $buygold = $row['buygold'];
                    $golds = $row['gold'];
                }
                mysql_query("UPDATE " . TB_PREFIX . "users SET gold = gold + " . $gold . ",transferedgold = transferedgold + " . $gold . " WHERE id =" . $uid . "");
                $form->addError("gold", "Your Gold was Successfully transferred!");
                include("GameEngine/Database/connectionbank.php");
                $db_connect = mysql_connect($AppConfig['db']['host'], $AppConfig['db']['user'], $AppConfig['db']['password']);
                mysql_select_db($AppConfig['db']['database'], $db_connect);
                $result1 = mysql_query("UPDATE bank set gold=0 WHERE code='" . $code . "'");
            } else {
                $form->addError("gold", PL_ENRECIPCODE);
            }
        } else {
            $form->addError("gold", PL_RECISNOTOK);
        }
    } else {
        $form->addError("gold", PL_RECISNOTOK);
    }
}
?>
<div id="silverExchange">
    <h3>Gold Coin Recovery System</h3>

    <p>In this section you will be able to withdraw gold stored in your account.</p>
    <li>
        <font color=red>You will not be able to save them again after the coins are removed.</font>
    </li>
    <li>
        You need a save code to collect gold coins (if you don't have a multihunter)
    </li>
    <br /><br />
    <?php $id = $_SESSION['id']; ?>
    <Center>
        <form action="plus.php?banker" method="post">
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

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
                                    <img src="/assets/images/x.gif" class="code" alt="Recipient code" title="Recipient code">
                                    Your Receipt Code:
                                </td>
                                <td>
                                    <input name="code" placeholder="Receiver code" id="code" type="text" class="text" value="" style="width:120px;" title="recipient code">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                </div>
            </div>
            <p>
                <input type="hidden" name="a" value="84">
                <input type="hidden" name="c" value="18a">
                <button type="submit" value="Send" class="green ">
                    <div class="button-container addHoverClick">
                        <div class="button-background">
                            <div class="buttonStart">
                                <div class="buttonEnd">
                                    <div class="buttonMiddle"></div>
                                </div>
                            </div>
                        </div>
                        <div class="button-content">Get the gold coin</div>
                    </div>
                </button>
                <br />
            <div class="error RTL"><?php echo $form->getError("gold"); ?></div>
            <br />

            <div class="error RTL"><?php echo $form->getError("gold2"); ?></div>
            </p>
        </form>
</div>
<font color="#c5c5c5" size="1" style="left:3px;position:absolute;top:525px">
    Travian System by <b> Of course</b>
</font>