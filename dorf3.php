<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

if ($session->access < 2) {
    header("Location: banned.php");
    die;
}
$start = $generator->pageLoadTimeStart();
if (isset($_GET['ok'])) {
    $database->updateUserField($session->username, 'ok', '0', '0');
    $_SESSION['ok'] = '0';
}
if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    header("Location: " . $_SERVER['PHP_SELF']);
} else {
    $building->procBuild($_GET);
}
include("templates/html.php");
?>

<body class='v35 gecko village3 perspectiveResources'>

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
                    <div class='clear'></div>
                </div>
                <div id='contentOuterContainer'>
                    <?php include('templates/res.php'); ?>
                    <div class="contentTitle">
                        <a id="closeContentButton" class="contentTitleButton" href="dorf1.php" title="<?php echo BL_CLOSE; ?>">&nbsp;</a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="<?php echo BL_TRAVIANANS; ?>">&nbsp;</a>
                    </div>
                    <div class='contentContainer'>
                        <div id='content' class='village3'>
                            <?php
                            if ($session->plus) {
                                if (isset($_GET['s'])) {
                                    switch ($_GET['s']) {
                                        case 2:
                                            include('templates/dorf3/2.php');
                                            break;
                                        case 3:
                                            include('templates/dorf3/3.php');
                                            break;
                                        case 4:
                                            include('templates/dorf3/4.php');
                                            break;
                                        case 5:
                                            include('templates/dorf3/5.php');
                                            break;
                                        case 6:
                                            include('templates/dorf3/6.php');
                                            break;
                                    }
                                } else {
                                    include('templates/dorf3/1.php');
                                }
                            } else {
                                include('templates/dorf3/noplus.php');
                            }
                            ?>
                        </div>
                        <div class='clear'></div>
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
                <?php
                include 'templates/footer.php';
                ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>