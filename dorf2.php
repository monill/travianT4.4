<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

$start = $generator->pageLoadTimeStart();
if (isset($_GET['ok'])) {
    $database->updateUserField($session->username, 'ok', '0', '0');
    $_SESSION['ok'] = '0';
}
if (isset($_GET['newdid'])) {
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_NUMBER_INT);
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_MAGIC_QUOTES);
    $t = mysql_query("SELECT `owner` FROM vdata WHERE wref = '" . $_GET['newdid'] . "' LIMIT 1");
    $row = mysql_fetch_assoc($t);
    if ($row['owner'] == $session->uid) {
        $_SESSION['wid'] = $_GET['newdid'];
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
} else {
    $building->procBuild($_GET);
}
include("templates/html.php");
if ($_SESSION['qst'] == 6) {
    $_SESSION['done'][0] = 1;
}
?>

<body class="v35 gecko chrome village2 perspectiveBuildings">

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
                        <a id="closeContentButton" class="contentTitleButton" href="dorf2.php">&nbsp;</a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank">&nbsp;</a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="village2">
                            <div class="villageMapWrapper">
                                <?php include('templates/dorf2.php'); ?>
                            </div>
                            <?php
                            if (NEWSBOX3) {
                                include('templates/News/newsbox3.php');
                            }
                            if ($building->NewBuilding) {
                                include('templates/Building.php');
                            }
                            ?>
                            <div class="clear">&nbsp;</div>
                        </div>
                    </div>
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
                <?php
                include 'templates/footer.php';
                ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>
