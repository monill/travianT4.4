<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

if (isset($_POST['name'])) $_POST['name'] = strval($_POST['name']);
if (isset($_POST['rank'])) $_POST['rank'] = intval($_POST['rank']);

if ((!isset($_POST['name']) || (isset($_POST['name']) && $_POST['name'] == '')) && (!isset($_POST['rank']) || (isset($_POST['rank']) && ($_POST['rank'] == '' || $_POST['rank'] == 0)))) {
    unset($_POST['name']);
    unset($_POST['rank']);
}
$start = $generator->pageLoadTimeStart();
if (isset($_GET['rank'])) {
    $_POST['rank'] = $_GET['rank'];
}
if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    header("Location: " . $_SERVER['PHP_SELF']);
    die;
}
include("templates/html.php");
if ($_SESSION['qst'] == 15) {
    $_SESSION['statistics'] = 1;
}
?>

<body class="v35 gecko statistics perspectiveBuildings">

    <div id="background">
        <div id="headerBar"></div>
        <div id="bodyWrapper">
            <img style="filter:chroma();" src="/assets/images/x.gif" id="msfilter" alt="" />

            <?php include('templates/Header.php'); ?>
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
                        <a id="closeContentButton" class="contentTitleButton" href="dorf1.php" title="<?php echo BL_CLOSE; ?>">
                            &nbsp;</a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="<?php echo BL_TRAVIANANS; ?>">&nbsp;</a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="statistics">
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>

                            <h1 class="titleInHeader"><?php echo HDR_STATIS; ?></h1>

                            <div class="contentNavi subNavi">
                                <div title="" <?php if (!isset($_GET['tid']) || (isset($_GET['tid']) && ($_GET['tid'] == 1 || $_GET['tid'] == 31 || $_GET['tid'] == 32 || $_GET['tid'] == 7))) {
                                                    echo "class=\"container active\"";
                                                } else {
                                                    echo "class=\"container normal\"";
                                                } ?>>
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content">
                                        <a href="statistiken.php">
                                            <span class="tabItem"><?php echo AL_OVERVIEW; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div title="" <?php if (isset($_GET['tid']) && ($_GET['tid'] == 4 || $_GET['tid'] == 41 || $_GET['tid'] == 42 || $_GET['tid'] == 43)) {
                                                    echo "class=\"container active\"";
                                                } else {
                                                    echo "class=\"container normal\"";
                                                } ?>>
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content">
                                        <a href="statistiken.php?tid=4">
                                            <span class="tabItem"><?php echo AL_ALLIANCE; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 2) {
                                                    echo "class=\"container active\"";
                                                } else {
                                                    echo "class=\"container normal\"";
                                                } ?>>
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="statistiken.php?tid=2"><span class="tabItem"><?php echo VILLAGES; ?></span></a></div>
                                </div>
                                <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 8) {
                                                    echo "class=\"container active\"";
                                                } else {
                                                    echo "class=\"container normal\"";
                                                } ?>>
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="statistiken.php?tid=8"><span class="tabItem"><?php echo U0; ?></span></a></div>
                                </div>
                                <?php if ($session->plus) { ?>
                                    <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 50) {
                                                        echo "class=\"container gold active\"";
                                                    } else {
                                                        echo "class=\"container gold normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="statistiken.php?tid=50"><span class="tabItem"><?php echo PF_PLUS; ?></span></a></div>
                                    </div>
                                <?php } ?>
                                <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 0) {
                                                    echo "class=\"container active\"";
                                                } else {
                                                    echo "class=\"container normal\"";
                                                } ?>>
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content">
                                        <a href="statistiken.php?tid=0">
                                            <span class="tabItem"><?php echo PF_GENERAL; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 9) {
                                                    echo "class=\"container active\"";
                                                } else {
                                                    echo "class=\"container normal\"";
                                                } ?>>
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content">
                                        <a href="statistiken.php?tid=9">
                                            <span class="tabItem"><?php echo AL_BONUS; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <?php if (SHOWWW2 == TRUE) {
                                    include "templates/Ranking/ww2.php";
                                }
                                if ($session->uid == 4) {
                                ?>
                                    <div title='' class='container gold normal'>
                                        <div class='background-start'>&nbsp;</div>
                                        <div class='background-end'>&nbsp;</div>
                                        <div class='content'>
                                            <a href='admins.php'>
                                                <span class='tabItem'><?php echo PF_ADMIN; ?></span>
                                            </a>
                                        </div>
                                    </div> <?php } ?>
                                <div class="clear"></div>
                            </div>
                            <?php
                            if (isset($_GET['tid'])) {
                                switch ($_GET['tid']) {
                                    case 31:
                                        include("templates/Ranking/player_attack.php");
                                        break;
                                    case 32:
                                        include("templates/Ranking/player_defend.php");
                                        break;
                                    case 7:
                                        include("templates/Ranking/player_top10.php");
                                        break;
                                    case 2:
                                        include("templates/Ranking/villages.php");
                                        break;
                                    case 4:
                                        include("templates/Ranking/alliance.php");
                                        break;
                                    case 8:
                                        include("templates/Ranking/heroes.php");
                                        break;
                                    case 9:
                                        include("templates/Ranking/winner.php");
                                        break;
                                    case 41:
                                        include("templates/Ranking/alliance_attack.php");
                                        break;
                                    case 42:
                                        include("templates/Ranking/alliance_defend.php");
                                        break;
                                    case 43:
                                        include("templates/Ranking/ally_top10.php");
                                        break;
                                    case 0:
                                        include("templates/Ranking/general.php");
                                        break;
                                    case 50:
                                        include("templates/Ranking/2plus.php");
                                        break;
                                    case 1:
                                        include("templates/Ranking/overview.php");
                                        break;
                                    default:
                                        include("templates/Ranking/ww.php");
                                        break;
                                }
                            } else {
                                include("templates/Ranking/overview.php");
                            }
                            ?>
                        </div>
                    </div>
                    <div class="contentFooter">&nbsp;</div>
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