<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

$start = $generator->pageLoadTimeStart();
if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    header("Location: " . $_SERVER['PHP_SELF']);
} else {
    $message->noticeType($_GET);
    $message->procNotice($_POST);
}
include("templates/html.php");
if ($_SESSION['qst'] == 11) {
    $_SESSION['done'][0] = 1;
    $q = "SELECT COUNT(id) FROM ndata WHERE ntype = 9 AND viewed=1 AND uid=" . $session->uid;
    $result = mysql_query($q);
    $data = mysql_fetch_assoc($result);
    if ($data['COUNT(id)'] > 0) {
        $_SESSION['done'][1] = 1;
    }
}
?>

<body class='v35 gecko reports perspectiveBuildings'>

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
                        <div id='content' class='reports'>
                            <h1 class="titleInHeader"><?php echo MS_REPORTS; ?></h1>

                            <div class="contentNavi subNavi">
                                <div title="" class="container <?php if (!isset($_GET['t'])) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="berichte.php"><span class="tabItem"><?php echo HEADER_ALL; ?></span></a></div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['t']) && $_GET['t'] == 1) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="berichte.php?t=1"><span class="tabItem"><?php echo HEADER_TRADE; ?></span></a></div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['t']) && $_GET['t'] == 2) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="berichte.php?t=2"><span class="tabItem"><?php echo HEADER_REINFORCEMENT; ?></span></a></div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['t']) && $_GET['t'] == 3) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="berichte.php?t=3"><span class="tabItem"><?php echo HEADER_MISCELLANEOUS; ?></span></a></div>
                                </div>
                                <?php if ($session->plus) { ?>
                                    <div title="" class="container <?php if (isset($_GET['t']) && $_GET['t'] == 4) {
                                                                        echo "active";
                                                                    } else {
                                                                        echo "normal";
                                                                    } ?>">
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="berichte.php?t=4"><span class="tabItem"><?php echo MS_ARCHIVE; ?></span></a></div>
                                    </div> <?php } ?>
                                <div class="clear"></div>
                            </div>
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>

                            <?php

                            if (isset($_GET['n1']) && isset($_GET['del']) == 1) {
                                $database->delNotice($_GET['n1'], $session->uid);
                                header("Location: berichte.php");
                            }
                            if (isset($_GET['aid'])) {
                                if ($session->alliance == $_GET['aid']) {
                                    if (isset($_GET['id'])) {
                                        $type = $database->getNotice2($_GET['id'], 'ntype');
                                        switch ($type) {
                                            case 0:
                                            case 1:
                                            case 2:
                                            case 3:
                                                $type = 1;
                                                break;
                                            case 4:
                                            case 5:
                                            case 6:
                                            case 7:
                                                $type = 4;
                                                break;
                                            case 10:
                                            case 11:
                                            case 12:
                                            case 13:
                                            case 14:
                                                $type = 10;
                                                break;
                                            case 15:
                                            case 16:
                                            case 17:
                                            case 18:
                                            case 19:
                                                $type = 1;
                                                break;
                                        }
                                        include("templates/Notice/" . $type . ".php");
                                    }
                                }
                            } else {
                                if (isset($_GET['t'])) {
                                    include("templates/Notice/t_" . $_GET['t'] . ".php");
                                } elseif (isset($_GET['id'])) {
                                    if ($database->getNotice2($_GET['id'], 'uid') == $session->uid || $session->access >= MULTIHUNTER) {
                                        $type = ($message->readingNotice['ntype'] == 5) ? $message->readingNotice['archive'] : $message->readingNotice['ntype'];
                                        switch ($type) {
                                            case 0:
                                            case 1:
                                            case 2:
                                            case 3:
                                                $type = 1;
                                                break;
                                            case 4:
                                            case 5:
                                            case 6:
                                            case 7:
                                                $type = 4;
                                                break;
                                            case 10:
                                            case 11:
                                            case 12:
                                            case 13:
                                            case 14:
                                                $type = 10;
                                                break;
                                            case 15:
                                            case 16:
                                            case 17:
                                            case 18:
                                            case 19:
                                                $type = 1;
                                                break;
                                        }
                                        include("templates/Notice/" . $type . ".php");
                                    }
                                } else {
                                    include("templates/Notice/all.php");
                                }
                            }
                            ?>
                            <div class='clear'>&nbsp;</div>
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
