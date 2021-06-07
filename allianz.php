<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");
include("GameEngine/Chat.php");

if (!isset($_GET['aid']) && $session->alliance == 0) {
    header('Location: dorf1.php');
    exit;
}

$start = $generator->pageLoadTimeStart();
$alliance->procAlliance($_GET);
include("templates/html.php");

if (isset($_GET['fid'])) {
    $fid = preg_replace('/[^0-9]/', '', $_GET['fid']);
    $forum = mysql_query('SELECT `forum_name`,`forum_area`,`alliance` FROM ' . TB_PREFIX . 'forum_cat WHERE id = ' . $fid . '');
    $forum_type = mysql_fetch_assoc($forum);
    if ($forum_type['forum_name'] != '' && $forum_type['forum_area'] != 1) {
        if ($forum_type['forum_area'] == 0) {
            if ($forum_type['alliance'] != $session->alliance) {
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        } else if ($forum_type['forum_area'] == 2) {
            if (count($database->diplomacyExistingRelationships($session->alliance))) {
                foreach ($database->diplomacyExistingRelationships($session->alliance) as $key => $row) {
                    $type = $row['type'];
                    switch ($type) {
                        case 1:
                            $type = AL_CONFWITH;
                            break;
                        case 2:
                            $type = AL_NAPWITH;
                            break;
                        case 3:
                            $type = AL_WARWITH;
                            break;
                    }
                }
            } elseif (count($database->diplomacyExistingRelationships2($session->alliance))) {
                foreach ($database->diplomacyExistingRelationships2($session->alliance) as $key => $row) {
                    switch ($type) {
                        case 1:
                            $type = AL_CONFWITH;
                            break;
                        case 2:
                            $type = AL_NAPWITH;
                            break;
                        case 3:
                            $type = AL_WARWITH;
                            break;
                    }
                }
            }
            if (isset($type) && $type != AL_CONFWITH) {
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        } else if ($forum_type['forum_area'] == 3) {
            if ($forum_type['alliance'] != $session->alliance) {
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        } else {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

if (isset($_POST['tid']) && $_POST['tid'] == 4) {
    $q = mysql_query("SELECT `voters`,`p1`,`p2`,`p3`,`p4` FROM " . TB_PREFIX . "forum_poll where id = '" . $_GET['tid'] . "'");
    $q = mysql_fetch_assoc($q);

    $votr = explode(',', $q['voters']);
    foreach ($votr as $voter) {
        if ($voter == $session->uid) {
            $yes = 1;
        }
    }
    if ($yes != 1) {
        if ($_POST['vote_option'] == 306) {
            $poll = 1;
        } elseif ($_POST['vote_option'] == 307) {
            $poll = 2;
        } elseif ($_POST['vote_option'] == 308) {
            $poll = 3;
        } elseif ($_POST['vote_option'] == 309) {
            $poll = 4;
        }
        $p = ($q['p' . $poll] + 1);
        $z = mysql_query("UPDATE " . TB_PREFIX . "forum_poll set `p" . $poll . "` = " . $p . " where id = '" . $_GET['tid'] . "'") or die(mysql_error());
        if ($q['voters'] == 0) {
            $votcode = $session->uid;
        } else {
            $votcode = $q['voters'] . ',' . $session->uid;
        }
        mysql_query("UPDATE " . TB_PREFIX . "forum_poll set `voters` = '" . $votcode . "' where id = '" . $_GET['tid'] . "'") or die(mysql_error());
    }
}
$alliance->procAlliance($_GET);
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
                        <?php

                        if (isset($_GET['s']) && $_GET['s'] == 2) {
                            echo "<div id='content' class='forum'>";
                        } else {
                            echo "<div id='content' class='alliance'>";
                        }

                        ?>

                        <?php if ($session->alliance == $aid) {
                        ?>
                            <div class="contentNavi subNavi">
                                <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 4) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="allianz.php?s=4"><span class="tabItem">Overview</span></a>
                                    </div>
                                </div>
                                <div title="" class="container <?php if (!isset($_GET['s'])) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="allianz.php"><span class="tabItem">Profile</span></a>
                                    </div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 3) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="allianz.php?s=3"><span class="tabItem">Attacks</span></a>
                                    </div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 2) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="allianz.php?s=2"><span class="tabItem">Forum</span></a>
                                    </div>
                                </div>
                                <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 6) {
                                                                    echo "active";
                                                                } else {
                                                                    echo "normal";
                                                                } ?>">
                                    <div class="background-start">&nbsp;</div>
                                    <div class="background-end">&nbsp;</div>
                                    <div class="content"><a href="allianz.php?s=6"><span class="tabItem">Chat</span></a>
                                    </div>
                                </div>
                                <?php if ($session->is_sitter == 0) { ?>
                                    <div title="" class="container <?php if (isset($_GET['s']) && $_GET['s'] == 5) {
                                                                        echo "active";
                                                                    } else {
                                                                        echo "normal";
                                                                    } ?>">
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="allianz.php?s=5"><span class="tabItem">Options</span></a>
                                        </div>
                                    </div><?php } ?>
                                <div class="clear"></div>
                            </div>
                        <?php
                        }
                        ?>
                        <script type="text/javascript">
                            window.addEvent('domready', function() {
                                $$('.subNavi').each(function(element) {
                                    new Travian.Game.Menu(element);
                                });
                            });
                        </script>
                        <?php
                        $result = mysql_query('SELECT `Alliance` FROM ' . TB_PREFIX . 'users WHERE id = ' . $session->uid . ' LIMIT 1');
                        $row = mysql_fetch_assoc($result);
                        $Alliance = $row['Alliance'];

                        if (!isset($_GET['aid']) && $Alliance == 0) {
                            if ($Alliance == 0) {
                                header('Location: dorf2.php');
                                exit;
                            }
                        } else {
                            if (isset($_GET['s'])) {
                                if ($Alliance == 0) {
                                    if (isset($aid)) {
                                        $aid = $aid;
                                    } else {
                                        $aid = $session->alliance;
                                    }
                                    $allianceinfo = $database->getAlliance($aid);
                                    $noticeArray = $database->readAlliNotice($aid);
                                    include('templates/Alliance/alli_menu.php');
                                } else {
                                    if (isset($_GET['o'])) {
                                        switch ($_GET['o']) {
                                            case 1:
                                                include('templates/Alliance/assignpos.php');
                                                break;
                                            case 2:
                                                include('templates/Alliance/kick.php');
                                                break;
                                            case 3:
                                                include('templates/Alliance/allidesc.php');
                                                break;
                                            case 4:
                                                include('templates/Alliance/invite.php');
                                                break;
                                            case 6:
                                                include('templates/Alliance/chgdiplo.php');
                                                break;
                                            case 11:
                                                include('templates/Alliance/quitalli.php');
                                                break;
                                            case 100:
                                                include('templates/Alliance/changename.php');
                                                break;
                                        }
                                    } else {
                                        switch ($_GET['s']) {
                                            case 2:
                                                include('templates/Alliance/forum.php');
                                                break;
                                            case 3:
                                                include('templates/Alliance/attacks.php');
                                                break;
                                            case 4:
                                                include('templates/Alliance/news.php');
                                                break;
                                            case 5:
                                                include('templates/Alliance/option.php');
                                                break;
                                            case 6:
                                                include('templates/Alliance/chat.php');
                                                break;
                                                //case 7:
                                                //   include('templates/Alliance/forum2.php');
                                                //   break;
                                            case 1:
                                            default:
                                                include('templates/Alliance/overview.php');
                                                break;
                                        }
                                    }
                                }
                            } elseif (isset($_POST['o'])) {
                                switch ($_POST['o']) {
                                    case 1:
                                        if ($database->getAlliancePermission($session->uid, 'opt1', 0) == 1) {
                                            if (isset($_POST['s']) == 5 && isset($_POST['a_user'])) {
                                                $alliance->procAlliForm($_POST);
                                                include('templates/Alliance/changepos.php');
                                            } else {
                                                include('templates/Alliance/assignpos.php');
                                            }
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 2:
                                        if ($database->getAlliancePermission($session->uid, 'opt2', 0) == 1) {
                                            if (isset($_POST['s']) == 5 && isset($_POST['a']) == 2) {
                                                $alliance->procAlliForm($_POST);
                                                include('templates/Alliance/kick.php');
                                            } else {
                                                include('templates/Alliance/kick.php');
                                            }
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 3:
                                        if ($database->getAlliancePermission($session->uid, 'opt3', 0) == 1) {
                                            if (isset($_POST['s']) == 5 && isset($_POST['a']) == 3) {
                                                $alliance->procAlliForm($_POST);
                                                include('templates/Alliance/allidesc.php');
                                            } else {
                                                include('templates/Alliance/allidesc.php');
                                            }
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 4:
                                        // echo "s";die;
                                        if ($database->getAlliancePermission($session->uid, 'opt4', 0) == 1) {
                                            if (isset($_POST['s']) == 5 && isset($_POST['a']) == 4) {
                                                $alliance->procAlliForm($_POST);
                                                include('templates/Alliance/invite.php');
                                            } else {
                                                include('templates/Alliance/invite.php');
                                            }
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 5:
                                        include('templates/Alliance/linkforum.php');
                                        break;
                                    case 6:
                                        if ($database->getAlliancePermission($session->uid, 'opt6', 0) == 1) {
                                            if (isset($_POST['dipl']) and isset($_POST['a_name'])) {
                                                $alliance->procAlliForm($_POST);
                                                include('templates/Alliance/chgdiplo.php');
                                            } else {
                                                include('templates/Alliance/chgdiplo.php');
                                            }
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 11:
                                        if (isset($_POST['s']) == 5 && isset($_POST['a']) == 11) {
                                            $alliance->procAlliForm($_POST);
                                            include('templates/Alliance/quitalli.php');
                                        } else {
                                            include('templates/Alliance/quitalli.php');
                                        }
                                        break;
                                    case 100:
                                        if ($database->getAlliancePermission($session->uid, 'opt3', 0) == 1) {
                                            if (isset($_POST['s']) == 5 && isset($_POST['a']) == 100) {
                                                $alliance->procAlliForm($_POST);
                                                include('templates/Alliance/changename.php');
                                            } else {
                                                include('templates/Alliance/changename.php');
                                            }
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 101:
                                        if ($database->getAlliancePermission($session->uid, 'opt6', 0) == 1) {
                                            $post = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['id']);
                                            $database->diplomacyCancelOffer($post);
                                            include('templates/Alliance/chgdiplo.php');
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 102:
                                        if ($database->getAlliancePermission($session->uid, 'opt6', 0) == 1) {
                                            $post = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['id']);
                                            $post2 = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['alli2']);
                                            $database->diplomacyInviteDenied($post, $post2);
                                            include('templates/Alliance/chgdiplo.php');
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 103:
                                        if ($database->getAlliancePermission($session->uid, 'opt6', 0) == 1) {
                                            $post = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['id']);
                                            $post2 = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['alli2']);
                                            $database->diplomacyInviteAccept($post, $post2);
                                            include('templates/Alliance/chgdiplo.php');
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    case 104:
                                        if ($database->getAlliancePermission($session->uid, 'opt6', 0) == 1) {
                                            $post = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['id']);
                                            $post2 = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['alli2']);
                                            $database->diplomacyCancelExistingRelationship($post, $post2);
                                            include('templates/Alliance/chgdiplo.php');
                                        } else {
                                            include('templates/Alliance/overview.php');
                                        }
                                        break;
                                    default:
                                        include('templates/Alliance/option.php');
                                        break;
                                }
                            } else {
                                include('templates/Alliance/overview.php');
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
            &nbsp;<?php
                    include 'templates/footer.php';
                    ?>
        </div>
        <div id="ce"></div>
    </div>
</body>

</html>