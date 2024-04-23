<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

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
$herodetail = $database->getHero($session->uid);
include("templates/html.php");
?>

<body class="v35 gecko hero hero_adventure perspectiveBuildings">



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
                        <a id="closeContentButton" class="contentTitleButton" href="dorf1.php" title="Close">&nbsp;</a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="Travian Answers">&nbsp;</a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="hero_adventure">
                            <h1 class="titleInHeader">
                                <h1 class="titleInHeader"><?php echo U0; ?></h1>

                                <div class="contentNavi subNavi">
                                    <div class="container normal">
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content">
                                            <a href="hero_inventory.php">
                                                <span class="tabItem"><?php echo HERO_HEROATTRIBUTES; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="container normal">
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content">
                                            <a href="hero.php">
                                                <span class="tabItem"><?php echo HERO_HEROAPPEARANCE; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="container active">
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content">
                                            <a href="hero_adventure.php">
                                                <span class="tabItem"><?php echo HERO_HEROADVENTURE; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="container normal">
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content">
                                            <a href="hero_auction.php">
                                                <span class="tabItem"><?php echo HERO_HEROAUCTION; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <script type="text/javascript">
                                    window.addEvent('domready', function() {
                                        $$('.subNavi').each(function(element) {
                                            new Travian.Game.Menu(element);
                                        });
                                    });
                                </script>
                                <table cellspacing="1" cellpadding="1">
                                    <thead>
                                        <tr>

                                            <th colspan="6"> Need more adventure? Are you buying right now?
                                                <button type="button" value="activate" id="button459713248zSadvpw" class="gold prosButton buyAdventure">
                                                    <div class="button-container addHoverClick ">
                                                        <div class="button-background">
                                                            <div class="buttonStart">
                                                                <div class="buttonEnd">
                                                                    <div class="buttonMiddle"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="button-content">The cost of buying
                                                            <img src="/assets/images/x.gif" class="goldIcon" alt="">
                                                            <span class="goldValue">1</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <script type="text/javascript">
                                                    window.addEvent('domready', function() {
                                                        if ($('button459713248zSadvpw')) {
                                                            $('button459713248zSadvpw').addEvent('click', function() {
                                                                window.fireEvent('buttonClicked', [this, {
                                                                    "type": "button",
                                                                    "value": "<?php echo HR_BUYNOW; ?>",
                                                                    "name": "",
                                                                    "id": "button459713248zSadvpw",
                                                                    "class": "gold productionBoostButton",
                                                                    "title": "<?php echo HR_BUYNOW; ?>",
                                                                    "confirm": "",
                                                                    "onclick": "",
                                                                    "adventureBuyDialog": {
                                                                        "infoIcon": "#"
                                                                    }
                                                                }]);
                                                            });
                                                        }
                                                    });
                                                </script>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="location" colspan="2"><?php echo AT_LOCATION; ?></th>
                                            <th class="moveTime"><?php echo BL_TIME; ?></th>
                                            <th class="difficulty">Hardship</th>
                                            <th class="timeLeft">Remaining time</th>
                                            <th class="goTo">link</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysql_query("SELECT `wref`,`dif`,`time` FROM adventure WHERE end = 0 and uid = " . $session->uid . " ORDER BY time ASC");
                                        $query = mysql_num_rows($sql);

                                        $outputList = '';

                                        if ($query == 0) {
                                            $outputList .= "<td colspan=\"6\" class=\"none\"><center>" . HR_NOADVFOUND . "</center></td>";
                                        } else {
                                            while ($row = mysql_fetch_array($sql)) {
                                                include "templates/Auction/alt.php";

                                                //find slowest unit.
                                                $eigen = $database->getCoor($herodetail['wref']);
                                                $coor = $database->getCoor($row['wref']);
                                                $from = array('x' => $eigen['x'], 'y' => $eigen['y']);
                                                $to = array('x' => $coor['x'], 'y' => $coor['y']);
                                                $speed = $herodetail['speed'] + $herodetail['itemspeed'];
                                                $time = $generator->procDistanceTime($from, $to, $speed, 1);

                                                $isoasis = $database->isVillageOases($row['wref']);
                                                if ($isoasis) {
                                                    $get = $database->getOMInfo($row['wref']);
                                                    $type = $get['type'];
                                                } else {
                                                    $get = $database->getMInfo($row['wref']);
                                                    $type = $get['fieldtype'];
                                                }
                                                switch ($type) {
                                                    case 1:
                                                    case 2:
                                                    case 3:
                                                        $tname = MP_FOREST;
                                                        break;
                                                    case 4:
                                                    case 5:
                                                    case 6:
                                                        $tname = MP_FIELD;
                                                        break;
                                                    case 7:
                                                    case 8:
                                                    case 9:
                                                        $tname = MP_MOUNTAIN;
                                                        break;
                                                    case 10:
                                                    case 11:
                                                    case 12:
                                                        $tname = MP_SEA;
                                                        break;
                                                }

                                                $outputList .= "<tr><td class=\"location\">" . $tname . "</td>";

                                                $outputList .= '<td class="coords"><a href="karte.php?x=' . $coor['x'] . '&amp;y=' . $coor['y'] . '">';
                                                if (DIRECTION == 'ltr') {
                                                    $outputList .= '<span class="coordinates coordinatesAligned"><span class="coordinateX">(' . $coor['x'] . '</span><span class="coordinatePipe">|</span><span class="coordinateY">' . $coor['y'] . ')</span>';
                                                } elseif (DIRECTION == 'rtl') {
                                                    $outputList .= '<span class="coordinates coordinatesAligned"><span class="coordinateY">' . $coor['y'] . ')</span><span class="coordinatePipe">|</span><span class="coordinateX">(' . $coor['x'] . '</span>';
                                                }
                                                $outputList .= '</span><span class="clear"></span></a></td>';
                                                $outputList .= "<td class=\"moveTime\"> " . $generator->getTimeFormat($time) . " </td>";
                                                if (!$row['dif']) {
                                                    $outputList .= "<td class='difficulty'><img src='/assets/images/x.gif' class='adventureDifficulty2' title='" . HR_NORMAL . "' /></td>";
                                                } else {
                                                    $outputList .= "<td class='difficulty'><img src='/assets/images/x.gif' class='adventureDifficulty0' title='" . HR_HARD . "' /></td>";
                                                }
                                                $outputList .= "<td class=\"timeLeft\"><span id=\"timer" . $timer . "\">" . $generator->getTimeFormat($row['time'] - $_SERVER['REQUEST_TIME']) . "</span></td>";
                                                $outputList .= "<td class=\"goTo\"><a class=\"gotoAdventure arrow\" href=\"a2b.php?id=" . $row['wref'] . "&h=1\">" . AT_STARTADV . "</a></td></tr>";
                                                $timer++;
                                            }
                                        }
                                        echo $outputList;
                                        ?>
                                    </tbody>
                                </table>
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
                &nbsp;<?php
                        include 'templates/footer.php';
                        ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>
