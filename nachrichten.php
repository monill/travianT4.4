<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

$start = $generator->pageLoadTimeStart();
if (isset($_GET['newdid'])) {
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_NUMBER_INT);
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_MAGIC_QUOTES);
    $t = mysql_query("SELECT `owner` FROM " . TB_PREFIX . "vdata WHERE wref = '" . $_GET['newdid'] . "' LIMIT 1");
    $row = mysql_fetch_assoc($t);
    if ($row['owner'] == $session->uid) {
        $_SESSION['wid'] = $_GET['newdid'];
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} else {
    $message->procMessage($_POST);
}
include("templates/html.php");

?>

<body class='v35 gecko messages perspectiveBuildings'>

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
                        <div id="content" class="messages">
                            <h1 class="titleInHeader"><?php echo MS_HEADERMESSAGES; ?></h1>
                            <?php
                            include("templates/Message/menu.php");
                            ?>
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>
                            <?php
                            if (isset($_GET['token']) && $_GET['token'] == $_SESSION['tok_key']) {
                                echo '<p style="color:red">' . sprintf(MS_SPAMMSG, ANTI_SPAM_TIME) . '</p>';
                            }
                            if (isset($_GET['id']) && !isset($_GET['t'])) {
                                $message->loadMessage($_GET['id']);
                                include("templates/Message/read.php");
                            } elseif (isset($_GET['n1']) && !isset($_GET['t'])) {
                                $database->delMessage($_GET['n1']);
                                header("Location: nachrichten.php");
                                die;
                            } else if (isset($_GET['t'])) {
                                switch ($_GET['t']) {
                                    case 1:
                                        if (isset($_GET['id'])) {
                                            $id = $_GET['id'];
                                        }
                                        include("templates/Message/write.php");
                                        break;
                                    case 2:
                                        include("templates/Message/sent.php");
                                        break;
                                    case 3:
                                        if ($session->goldclub) {
                                            include("templates/Message/archive.php");
                                        }
                                        break;
                                    case 4:
                                        // if($session->plus) {
                                        $message->loadNotes();
                                        include("templates/Message/notes.php");
                                        // }
                                        break;
                                    case 5:
                                        include('templates/Message/ignore.php');
                                        break;
                                    case 6:
                                        include('templates/Message/reports.php');
                                        break;
                                    default:
                                        include("templates/Message/inbox.php");
                                        break;
                                }
                            } else {
                                include("templates/Message/inbox.php");
                            }
                            ?>
                            <div class="clear">&nbsp;</div>
                        </div>
                        <div class="clear"></div>
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