<?php
include("GameEngine/Protection.php");
include("GameEngine/Village.php");
include("GameEngine/Lang/ui/dorf.php");

if (COMMENCE > $_SERVER['REQUEST_TIME']) {
    header("Location: logout.php");
}
$start = $generator->pageLoadTimeStart();
if (isset($_GET['ok'])) {
    $database->updateUserField($session->username, 'ok', '0', '0');
    $_SESSION['ok'] = '0';
}

if (isset($_GET['newdid'])) {
    $_GET['newdid'] = filter_var($_GET['newdid'], FILTER_SANITIZE_NUMBER_INT);

    $t = mysql_query("SELECT `owner` FROM vdata WHERE wref = '" . $_GET['newdid'] . "' LIMIT 1");
    $row = mysql_fetch_assoc($t);
    if ($row['owner'] == $session->uid) {
        $_SESSION['wid'] = $_GET['newdid'];
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} else {
    $building->procBuild($_GET);
}

$time = time();

if (isset($_GET['masterbuild']) && $session->userinfo['gold'] >= 1 and (($_SESSION['MASTER'] < $time) || !isset($_SESSION['MASTER']))) {
    $_SESSION['MASTER'] = time() + 60;
    function isMax($id, $field, $loop = 0)
    {
        $name = "bid" . $id;
        global $$name, $village;
        $dataarray = $$name;

        if ($id <= 4) {
            if ($village->capital == 1) {
                return ($village->resarray['f' . $field] == (count($dataarray) - 1 - $loop));
            } else {
                return ($village->resarray['f' . $field] == (count($dataarray) - 11 - $loop));
            }
        } else {
            return ($village->resarray['f' . $field] == count($dataarray) - $loop);
        }
    }

    $master = 0;

    for ($i = 1; $i <= 18; $i++) {

        $RPA_LEVEL = $village->resarray['f' . $i];
        $FIELD_BID = $village->resarray['f' . $i . 't'];
        $bindicate = isMax($i, $FIELD_BID);
        $maxLvL = sizeof($GLOBALS['bid' . $FIELD_BID]) - 1;

        if ($village->resarray['f' . $i . 't'] < $maxLvL && $session->userinfo['gold'] >= 1) {

            if (!in_array($FIELD_BID, array(40, 25, 26)) && !in_array($bindicate, array(1, 10, 11))) {
                if ($maxLvL - $RPA_LEVEL) {
                    $pop = $cp = 0;
                    $lvl = $RPA_LEVEL + 1;
                    //for($ix = 1+$RPA_LEVEL; $ix <= $maxLvL; ++$ix){
                    $pop += $GLOBALS['bid' . $FIELD_BID][$lvl]['pop'];
                    $cp += $GLOBALS['bid' . $FIELD_BID][$lvl]['cp'];
                    //}
                    $database->modifyPop($village->wid, $pop, 0);
                    $database->addCP($village->wid, $cp);

                    $uprequire = $building->resourceRequired($i, $village->resarray['f' . $i . 't']);

                    //$time = time()+ $uprequire['time'];

                    $loop = ($bindicate == 9 ? 1 : 0);
                    $loopsame = 0;
                    if ($loop == 1) {
                        foreach ($building->buildArray as $build) {
                            if ($build['field'] == $i) {
                                $loopsame += 1;
                                $uprequire = $building->resourceRequired($i, $village->resarray['f' . $i . 't'], ($loopsame > 0 ? 2 : 1));
                            }
                        }
                        if ($session->tribe == 1 || ALLOW_ALL_TRIBE) {
                            if ($i >= 19) {
                                foreach ($building->buildArray as $build) {
                                    if ($build['field'] >= 19) {
                                        $time = $build['timestamp'] + $uprequire['time'];
                                    }
                                }
                            } else {
                                foreach ($building->buildArray as $build) {
                                    if ($build['field'] <= 18) {
                                        $time = $build['timestamp'] + $uprequire['time'];
                                    }
                                }
                            }
                        } else {
                            $time = $building->buildArray[0]['timestamp'] + $uprequire['time'];
                        }
                    }
                    $level = $database->getResourceLevel($village->wid);
                    if ($master == 0) {
                        $time = time();
                        $time = $time + ($loop == 1 ? ceil(60 / SPEED) : 0);
                    } else {
                        $time = 2;
                    }
                    $newlevel = $level['f' . $i] + 1 + count($database->getBuildingByField($village->wid, $i));
                    if ($database->addBuilding($village->wid, $i, $village->resarray['f' . $i . 't'], $loop, $time, $master, $newlevel)) {
                        $database->modifyResource($village->wid, $uprequire['wood'], $uprequire['clay'], $uprequire['iron'], $uprequire['crop'], 0);
                        $master = 1;
                        //$database->modifyGold($session->uid, 1, 0);
                    }
                }
            }
        }
    }
    header("Location: dorf1.php");
    exit;
}

include("templates/html.php");
?>

<body class="v35 gecko chrome village1 perspectiveResources">

    <div id="background">
        <div id="headerBar"></div>
        <div id="bodyWrapper">
            <img style="filter:chroma();" src="/assets/images/x.gif" id="msfilter" alt="" />
            <?php
            include('templates/Header.php');
            ?>
            <div id="center">
                <a id="ingameManual" href="help.php">
                    <img class="question" alt="<?php echo HDR_HELP2; ?>" src="/assets/images/x.gif">
                </a>

                <div id="sidebarBeforeContent" class="sidebar beforeContent">
                    <?php
                    require('templates/heroSide.php');
                    require('templates/Alliance.php');
                    require('templates/infomsg.php');
                    require('templates/links.php');
                    ?>
                    <div class="clear"></div>
                </div>
                <div id="contentOuterContainer">
                    <?php require('templates/res.php'); ?>
                    <div class="contentTitle">
                        <a id="closeContentButton" class="contentTitleButton" href="dorf1.php"></a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="village1">
                            <?php
                            require('templates/field.php');
                            if ($building->NewBuilding) {
                                require('templates/Building.php');
                            }
                            ?>
                            <div id="map_details">
                                <?php
                                require 'templates/movement.php';
                                require 'templates/production.php';
                                require 'templates/troops.php';
                                ?>
                                <div class="clear"></div>
                            </div>
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
                        <?php require 'templates/sideinfo.php'; ?>
                    </div>
                    <?php
                    require 'templates/multivillage.php';
                    require 'templates/quest.php';
                    ?>
                </div>
                <div class="clear">&nbsp;</div>
                <?php
                require 'templates/footer.php';
                ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>
