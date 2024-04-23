<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

if (isset($_GET['uid'])) $_GET['uid'] = intval($_GET['uid']);

$start = $generator->pageLoadTimeStart();
if (isset($_GET['newdid'])) {
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_NUMBER_INT);

    $t = mysql_query("SELECT `owner` FROM vdata WHERE wref = " . $_GET['newdid'] . " LIMIT 1");
    $row = mysql_fetch_assoc($t);
    if ($row['owner'] == $session->uid) {
        $_SESSION['wid'] = $_GET['newdid'];
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    die;
} else {
    $profile->procProfile($_POST);
    $profile->procSpecial($_GET);
}
include("templates/html.php");
?>

<body class="v35 gecko player perspectiveResources">

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
                        <div id='content' class='player'>
                            <?php $username = $database->getUserField($_GET['uid'], "username", 0); ?>
                            <h1 class="titleInHeader">
                                <?php
                                echo AL_PLAYER;
                                if (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
                                    echo "- " . $username;
                                } ?>
                            </h1>
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>
                            <?php
                            if ((isset($_GET['uid']) && $_GET['uid'] == $session->uid) || !isset($_GET['uid'])) {
                                include("templates/Profile/menu.php");
                            }
                            if (isset($_GET['uid'])) {
                                if ($_GET['uid'] >= 2) {
                                    $user = $database->getUser($_GET['uid'], 1);
                                    if (isset($user['id'])) {
                                        include("templates/Profile/overview.php");
                                    } else {
                                        include("templates/Profile/notfound.php");
                                    }
                                } else {
                                    include("templates/Profile/special.php");
                                }
                            } else if (isset($_GET['s'])) {
                                if ($_GET['s'] == 1) {
                                    include("templates/Profile/profile.php");
                                } elseif ($_GET['s'] == 2) {
                                    include("templates/Profile/preference.php");
                                } elseif ($_GET['s'] == 3) {
                                    include("templates/Profile/account.php");
                                } elseif ($_GET['s'] > 4 or $session->is_sitter == 1) {
                                    header("Location: " . $_SERVER['PHP_SELF'] . "?uid=" . preg_replace("/[^a-zA-Z0-9_-]/", "", $session->uid));
                                    die;
                                }
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
                <?php
                include 'templates/footer.php';
                ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>
