<?php

include('GameEngine/Village.php');

$start = $generator->pageLoadTimeStart();
$profile->procProfile($_POST);
$profile->procSpecial($_GET);

if (isset($_GET['newdid'])) {
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_NUMBER_INT);
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_MAGIC_QUOTES);
    $t = mysql_query("SELECT `owner` FROM vdata WHERE wref = '" . $_GET['newdid'] . "'");
    $row = mysql_fetch_assoc($t);
    if ($row['owner'] == $session->uid) {
        $_SESSION['wid'] = $_GET['newdid'];
    }
    if (isset($_GET['s'])) {
        header('Location: spieler.php?s=' . preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['s']));
        exit;
    } else if (isset($_GET['uid'])) {
        header('Location: spieler.php?uid=' . preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['uid']));
        exit;
    } else {
        header('Location: spieler.php');
        exit;
    }
}

if (!isset($_GET['s']) && !isset($_GET['uid'])) {
    header('Location: options.php?s=1');
    exit;
}

include('templates/html.php');
?>

<body class="v35 gecko options perspectiveResources">

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
                    <div class='contentContainer'>
                        <div id='content' class='options'>
                            <?php $username = $database->getUserField($_GET['uid'], 'username', 0); ?>
                            <h1 class='titleInHeader'><?php echo PF_PREFERENCES; ?></h1>

                            <div class="contentNavi subNavi">
                                <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 1) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content">
                                        <a href="options.php?s=1">
                                            <span class="tabItem"><?php echo PF_GAME; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 2) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content">
                                        <a href="options.php?s=2">
                                            <span class="tabItem"><?php echo PF_ACCOUNT; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 3) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content">
                                        <a href="options.php?s=3">
                                            <span class="tabItem"><?php echo PF_SITTER; ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <?php

                            if ($session->is_sitter != 1) {
                                if (isset($_GET['s'])) {
                                    $_GET['s'] = filter_var($_GET['s'], FILTER_SANITIZE_NUMBER_INT);
                                    $_GET['s'] = str_replace('-', '', $_GET['s']);

                                    switch ($_GET['s']) {
                                        case 1:
                                            include('templates/Profile/settings.php');
                                            break;
                                        case 2:
                                            include('templates/Profile/account.php');
                                            break;
                                        case 3:
                                            include('templates/Profile/sitter.php');
                                            break;
                                        default:
                                            include('templates/Profile/settings.php');
                                            break;
                                    }

                                    if ($_GET['s'] > 4 or $session->is_sitter == 1) {
                                        header('Location: ' . $_SERVER['PHP_SELF'] . '?uid=' . preg_replace('/[^a-zA-Z0-9_-]/', '', $session->uid));
                                        die;
                                    }
                                }
                                if (isset($_GET['id']) == $session->uid && isset($_GET['type']) == 3) {
                                    $_GET['owner'] = filter_var($_GET['owner'], FILTER_SANITIZE_NUMBER_INT);
                                    $_GET['owner'] = str_replace('-', '', $_GET['owner']);
                                    $_GET['id'] = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                                    $_GET['id'] = str_replace('-', '', $_GET['id']);
                                    $owner = $_GET['owner'];
                                    $id = $_GET['id'];
                                    $database->removeMeSit($owner, $id);
                                }
                            } else {
                                echo "<font color=red>" . PF_CANTACCESS . "</font>";
                            }
                            ?>
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>
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
                &nbsp;
                <?php include 'templates/footer.php'; ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>
