<?php
include("GameEngine/Protection.php");
include("GameEngine/Village.php");
include("GameEngine/Units.php");

$winner = $database->hasWinner();
if ($winner) {
    header("Location: winner.php");
    die;
}
if ($session->access < 2) {
    header("Location: banned.php");
    die;
}

$start = $generator->pageLoadTimeStart();
if (isset($_GET['newdid'])) {
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_NUMBER_INT);
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_MAGIC_QUOTES);
    $t = mysql_query("SELECT `owner` FROM vdata WHERE wref = '" . $_GET['newdid'] . "' LIMIT 1");
    $row = mysql_fetch_assoc($t);
    if ($row['owner'] == $session->uid) {
        $_SESSION['wid'] = $_GET['newdid'];
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . (isset($_GET['z']) ? '?z=' . preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['z']) : ''));
    die;
} else {
    $building->procBuild($_GET);
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if (isset($_GET['bid'])) {
    $bid = $_GET['bid'];
    $dbarray = $database->getNoticeData($bid);
    $dataarray = explode(",", $dbarray);
    $isoases = $database->isVillageOases($dataarray['31']);
    if ($isoases) {
        $too = $database->getOMInfo($dataarray['31']);
        if ($too['conqured'] == 0) {
            $disabledr = "disabled=disabled";
        } else {
            $disabledr = "";
        }
        $disabled = "disabled=disabled";
    } else {
        $disabledr = "";
        $disabled = "";
    }
    $checked = "checked=checked";
}
if (isset($_GET['w'])) {
    $w = $_GET['w'];
}
if (isset($_GET['r'])) {
    $r = $_GET['r'];
}
if (isset($_GET['o'])) {
    $o = $_GET['o'];
    $oid = $_GET['z'];
    $isconqured = $database->getOasisField($oid, "conqured");
    if ($isconqured == 0) {
        $disabledr = "disabled=disabled";
    } else {
        $disabledr = "";
    }
    $disabled = "disabled=disabled";
    $checked = "checked=checked";
}
if (isset($_GET['z'])) {
    $oid = $_GET['z'];
    $isoases = $database->isVillageOases($oid);
    if ($isoases) {
        $too = $database->getOMInfo($oid);
        if ($too['conqured'] == 0) {
            $disabledr = "disabled=disabled";
        } else {
            $disabledr = "";
        }
        $disabled = "disabled=disabled";
    } else {
        $disabledr = "";
        $disabled = "";
    }
    $checked = "checked=checked";
}

if ($building->getTypeLevel(16) < 20) $_POST['ctar2'] = 255;

$process = $units->procUnits($_POST);
include("templates/html.php");
?>

<body class="v35 gecko statistics perspectiveBuildings">
    <script type="text/javascript">
        window.addEvent("domready", function() {
            var btns = document.getElementsByTagName("button");
            for (i = 0; i < btns.length; i++) {
                btns[i].addEventListener('click', function() {
                    var vinpt = document.createElement("input");
                    vinpt.setAttribute('type', 'hidden');
                    vinpt.setAttribute('value', this.getAttribute('value'));
                    vinpt.setAttribute('name', this.getAttribute('name'));
                    this.parentNode.appendChild(vinpt);
                    this.setAttribute('disabled', 'disabled');
                    if (this.getAttribute("type") == "submit") {
                        this.up("form").submit();
                    }
                });
            }
            var ancs = document.getElementsByTagName("a");
            for (i = 0; i < ancs.length; i++) {
                ancs[i].addEventListener('click', function() {
                    var tmphref = this.getAttribute('href');
                    var tmptarg = this.getAttribute('target');
                    this.removeAttribute('href');
                    if (tmphref != '' && tmphref != null) {
                        if (tmptarg == '_blank') {
                            window.open(tmphref);
                        } else {
                            window.location.href = tmphref;
                        }
                    }
                });
            }
            var areas = document.getElementsByTagName("area");
            for (i = 0; i < areas.length; i++) {
                areas[i].addEventListener('click', function() {
                    var tmphref = this.getAttribute('href');
                    this.removeAttribute('href');
                    if (tmphref != '' && tmphref != null) window.location.href = tmphref;
                });
            }
        });
    </script>
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
                        <a id="closeContentButton" class="contentTitleButton" href="dorf1.php" title="<?php echo BL_CLOSE; ?>">
                            &nbsp;</a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="<?php echo BL_TRAVIANANS; ?>">&nbsp;</a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="a2b">

                            <?php

                            if (!empty($id)) {
                                if (isset($_GET['s'])) {
                                    include "templates/a2b/newdorf.php";
                                }
                                if (isset($_GET['h'])) {
                                    include "templates/a2b/adventure.php";
                                }
                            } else
                            if (isset($_GET['f']) || isset($_GET['k'])) {
                                $units->procTrapped($_GET);
                            } elseif (isset($_GET['anim'])) {
                                $units->procanimals();
                            } elseif (isset($w)) {
                                $enforce = $database->getEnforceArray($w, 0);
                                if ($enforce['vref'] == $village->wid) {
                                    $to = $database->getVillage($enforce['from']);
                                    $ckey = $w;
                                    include("templates/a2b/sendback.php");
                                } else {
                                    include("templates/a2b/units_" . $session->tribe . ".php");
                                    include("templates/a2b/search.php");
                                }
                            } elseif (isset($r)) {
                                $enforce = $database->getEnforceArray($r, 0);
                                if ($enforce['from'] == $village->wid) {
                                    $to = $database->getVillage($enforce['from']);
                                    $ckey = $r;
                                    include("templates/a2b/sendback.php");
                                } else {
                                    include("templates/a2b/units_" . $session->tribe . ".php");
                                    include("templates/a2b/search.php");
                                }
                            } else {
                                if (isset($process['0'])) {
                                    $coor = $database->getCoor($process['0']);
                                    include("templates/a2b/attack.php");
                                } else {
                                    include("templates/a2b/units_" . $session->tribe . ".php");
                                    include("templates/a2b/search.php");
                                }
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
                <?php
                include 'templates/footer.php';
                ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>
