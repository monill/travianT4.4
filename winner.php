<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

$start = $generator->pageLoadTimeStart();

if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    header("Location: " . $_SERVER['PHP_SELF']);
} else {
    $building->procBuild($_GET);
}

include("templates/html.php");

## Get Rankings for Ranking Section
$sql = $ranking->procUsersRanking();
$pop[] = mysql_fetch_assoc($sql);
$pop[] = mysql_fetch_assoc($sql);
$pop[] = mysql_fetch_assoc($sql);
$sql = $ranking->procUsersAttRanking();
$attacker[] = mysql_fetch_assoc($sql);
$attacker[] = mysql_fetch_assoc($sql);
$attacker[] = mysql_fetch_assoc($sql);
$sql = $ranking->procUsersDefRanking();
$defender[] = mysql_fetch_assoc($sql);
$defender[] = mysql_fetch_assoc($sql);
$defender[] = mysql_fetch_assoc($sql);

## Get WW Winner Details
$sql = mysql_fetch_assoc(mysql_query("SELECT vref FROM " . TB_PREFIX . "fdata WHERE f99 = '100' and f99t = '40'"));
$vref = $sql['vref'];

$sql = mysql_fetch_assoc(mysql_query("SELECT name FROM " . TB_PREFIX . "vdata WHERE wref = '$vref'"));
$winningvillagename = $sql['name'];

$sql = mysql_fetch_assoc(mysql_query("SELECT owner FROM " . TB_PREFIX . "vdata WHERE wref = '$vref'"));
$owner = $sql['owner'];

$sql = mysql_fetch_assoc(mysql_query("SELECT username FROM " . TB_PREFIX . "users WHERE id = '$owner'"));
$username = $sql['username'];

$sql = mysql_fetch_assoc(mysql_query("SELECT alliance FROM " . TB_PREFIX . "users WHERE id = '$owner'"));
$allianceid = $sql['alliance'];

$sql = mysql_fetch_assoc(mysql_query("SELECT name, tag FROM " . TB_PREFIX . "alidata WHERE id = '$allianceid'"));
$winningalliance = $sql;

$sql = mysql_fetch_assoc(mysql_query("SELECT tag FROM " . TB_PREFIX . "alidata WHERE id = '$allianceid'"));
$winningalliancetag = $sql['tag'];

$winner = $database->hasWinner();

if ($winner) {
?>

    <body class='v35 gecko universal perspectiveResources'>

        <div id="background">
            <div id="headerBar"></div>
            <div id="bodyWrapper">
                <img style="filter:chroma();" src="/assets/images/x.gif" id="msfilter" alt="" />
                <?php
                include('templates/Header.php');
                ?>
                <div id="center">
                    <a id="ingameManual" href="help.php">
                        <img class="question" alt="Help" src="/assets/images/x.gif">
                    </a>

                    <div id="sidebarBeforeContent" class="sidebar beforeContent">
                        <?php
                        include('templates/heroSide.php');
                        include('templates/Alliance.php');
                        include('templates/infomsg.php');
                        include('templates/links.php');
                        ?>
                        <div class="clear"></div>
                    </div>
                    <div id="contentOuterContainer">
                        <?php include('templates/res.php'); ?>
                        <div class="contentTitle">
                            <a id="closeContentButton" class="contentTitleButton" href="dorf1.php" title="<?php echo BL_CLOSE; ?>">&nbsp;</a>
                            <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="<?php echo BL_TRAVIANANS; ?>">&nbsp;</a>
                        </div>
                        <div class="contentContainer">
                            <div id="content" class="universal">
                                <script type="text/javascript">
                                    window.addEvent('domready', function() {
                                        $$('.subNavi').each(function(element) {
                                            new Travian.Game.Menu(element);
                                        });
                                    });
                                </script>
                                <img src="/assets/images/g/g40_100-<?php echo DIRECTION; ?>.png" <?php if (DIRECTION == 'ltr') {
                                                                                                        echo 'align="right"';
                                                                                                    } else {
                                                                                                        echo 'align="left"';
                                                                                                    } ?> style="padding-top: 40px;">
                                <p>
                                    <?php
                                    echo sprintf(
                                        WINNER_STR,
                                        SERVER_NAME,
                                        $vref,
                                        $generator->getMapCheck($vref),
                                        $winningvillagename,
                                        date('H:i:s', WINMOMENT),
                                        date('Y-m-d', WINMOMENT),
                                        $allianceid,
                                        $winningalliancetag,
                                        $owner,
                                        $username,
                                        $owner,
                                        $username,
                                        $pop[0]['userid'],
                                        $pop[0]['totalvillages'],
                                        $pop[0]['totalpop'],
                                        $pop[0]['username'],
                                        $pop[1]['userid'],
                                        $pop[1]['totalvillages'],
                                        $pop[1]['totalpop'],
                                        $pop[1]['username'],
                                        $pop[2]['userid'],
                                        $pop[2]['totalvillages'],
                                        $pop[2]['totalpop'],
                                        $pop[2]['username'],
                                        $attacker[0]['userid'],
                                        $attacker[0]['totalvillages'],
                                        $attacker[0]['apall'],
                                        $attacker[0]['username'],
                                        $defender[0]['userid'],
                                        $defender[0]['totalvillages'],
                                        $defender[0]['dpall'],
                                        $defender[0]['username'],
                                        SERVER_NAME
                                    );
                                    ?>

                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class='contentFooter'>&nbsp;</div>
                    </div>
                    <div id="sidebarAfterContent" class="sidebar afterContent">
                        <div id="sidebarBoxActiveVillage" class="sidebarBox ">
                            <div class="sidebarBoxBaseBox">
                                <div class="baseBox baseBoxTop">
                                    <div class="baseBox baseBoxBottom">
                                        <div class="baseBox baseBoxCenter"></div>
                                    </div>
                                </div>
                            </div>
                            <?php include 'templates/sideinfo.php'; ?>
                        </div>
                        <?php
                        include 'templates/multivillage.php';
                        include 'templates/quest.php';
                        ?>
                    </div>
                    <div class="clear"></div>
                    <?php include 'templates/footer.php'; ?>
                </div>
                <div id="ce"></div>
            </div>
    </body>

    </html>
<?php
} else {
    header("Location: dorf1.php");
}
?>