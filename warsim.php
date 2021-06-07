<?php

include("GameEngine/Protection.php");
include("GameEngine/Village.php");

$start = $generator->pageLoadTimeStart();
$battle->procSim($_POST);

include("templates/html.php");
?>

<body class='v35 gecko universal perspectiveResources'>

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
                            &nbsp;
                        </a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="<?php echo BL_TRAVIANANS; ?>">
                            &nbsp;
                        </a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="universal">
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>
                            <div id="content" class="warsim">
                                <h1><?php echo REPORT_WARSIM; ?></h1>
                                <form action="warsim.php" method="post">
                                    <?php
                                    if (isset($_POST['result'])) {
                                        echo '<h4 class="round">' . JR_ATTACK_COMBAT_MODEL . ': '
                                            . ($form->getValue('attack_type') == 1 ? JR_ATTACK_SCOUT : ($form->getValue('attack_type') == 4 ? JR_ATTACK_RAID : JR_ATTACK_NORMAL)) . '</h4>';
                                        include("templates/Simulator/res_a.php");
                                        include("templates/Simulator/res_d.php");
                                        echo '<h4 class="round">' . JR_WARSIM_ATTACKCONFIG . '</h4>';
                                        if (isset($_POST['result'][3]) && isset($_POST['result'][4])) {
                                            if ($_POST['result'][4] > $_POST['result'][3]) {
                                                echo "";
                                            } elseif ($_POST['result'][4] == 0) {
                                                echo "";
                                            } else {
                                                $demolish = $_POST['result'][4] / $_POST['result'][3];
                                                //$Katalife=round($_POST['result'][4]-($_POST['result'][4]*$_POST['result'][1]));
                                                //$totallvl = round($form->getValue('kata')-($form->getValue('kata') * $demolish));
                                                $totallvl = round(sqrt(pow(($form->getValue('kata') + 0.5), 2) - ($_POST['result'][4] * 8)));
                                                echo "<p>construction of <b>" . $form->getValue('kata') . "</b> " . LEVEL . " <b>" . $totallvl . "</b> injured</p>";
                                            }
                                        }
                                    }
                                    include("templates/Simulator/att.php");
                                    include("templates/Simulator/def.php");
                                    $attackertribe = isset($_POST['attackertribe']) ? $_POST['attackertribe'] : 0;
                                    $defendertribe = isset($_POST['defendertribe']) ? $_POST['defendertribe'] : 0;
                                    $enforcestribes = isset($_POST['enforcestribes']) ? $_POST['enforcestribes'] : array();
                                    //$participantstribes = isset($_POST['participantstribes'])?$_POST['participantstribes']:array();
                                    ?>
                                    <table id="select" cellpadding="1" cellspacing="1">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="fighterType">
                                                        <div class="boxes boxesColor red">
                                                            <div class="boxes-tl"></div>
                                                            <div class="boxes-tr"></div>
                                                            <div class="boxes-tc"></div>
                                                            <div class="boxes-ml"></div>
                                                            <div class="boxes-mr"></div>
                                                            <div class="boxes-mc"></div>
                                                            <div class="boxes-bl"></div>
                                                            <div class="boxes-br"></div>
                                                            <div class="boxes-bc"></div>
                                                            <div class="boxes-contents"><?php echo JR_WARSIM_ATTACKER; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                    <div class="choice">
                                                        <?php
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            echo '<label><input class="radio" type="radio" name="attackertribe" value="' . $i . '" '
                                                                . (($attackertribe == $i) ? "checked" : '') . '> ' . constant('TRIBE' . $i) . '</label><br/>';
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fighterType">
                                                        <div class="boxes boxesColor green">
                                                            <div class="boxes-tl"></div>
                                                            <div class="boxes-tr"></div>
                                                            <div class="boxes-tc"></div>
                                                            <div class="boxes-ml"></div>
                                                            <div class="boxes-mr"></div>
                                                            <div class="boxes-mc"></div>
                                                            <div class="boxes-bl"></div>
                                                            <div class="boxes-br"></div>
                                                            <div class="boxes-bc"></div>
                                                            <div class="boxes-contents"><?php echo REPORT_DEFENDER; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                    <div class="choice">
                                                        <?php
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            echo '<label><input class="radio" type="radio" name="defendertribe" value="' . $i . '" '
                                                                . (($defendertribe == $i) ? "checked" : '') . '> ' . constant('TRIBE' . $i) . '</label><br/>';
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fighterType">
                                                        <div class="boxes boxesColor green">
                                                            <div class="boxes-tl"></div>
                                                            <div class="boxes-tr"></div>
                                                            <div class="boxes-tc"></div>
                                                            <div class="boxes-ml"></div>
                                                            <div class="boxes-mr"></div>
                                                            <div class="boxes-mc"></div>
                                                            <div class="boxes-bl"></div>
                                                            <div class="boxes-br"></div>
                                                            <div class="boxes-bc"></div>
                                                            <div class="boxes-contents"><?php echo REPORT_REINFS; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                    <div class="choice">
                                                        <?php
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            echo '<label><input class="check" type="checkbox" name="reinf_' . $i . '" value="1" '
                                                                . (((in_array($i, $enforcestribes))) ? "checked" : '') . '> ' . constant('TRIBE' . $i) . '</label><br/>';
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fighterType">
                                                        <div class="boxes boxesColor darkgray">
                                                            <div class="boxes-tl"></div>
                                                            <div class="boxes-tr"></div>
                                                            <div class="boxes-tc"></div>
                                                            <div class="boxes-ml"></div>
                                                            <div class="boxes-mr"></div>
                                                            <div class="boxes-mc"></div>
                                                            <div class="boxes-bl"></div>
                                                            <div class="boxes-br"></div>
                                                            <div class="boxes-bc"></div>
                                                            <div class="boxes-contents"><?php echo JR_ATTACK_COMBAT_MODEL; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                    <div class="choice">
                                                        <label>
                                                            <input class="radio" type="radio" name="attack_type" value="3" <?php if ($form->getValue('attack_type') == 3 || $form->getValue('attack_type') == "") {
                                                                                                                                echo "checked";
                                                                                                                            } ?>> <?php echo JR_ATTACK_NORMAL; ?>
                                                        </label><br />
                                                        <label>
                                                            <input class="radio" type="radio" name="attack_type" value="4" <?php if ($form->getValue('attack_type') == 4) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>> <?php echo JR_ATTACK_RAID; ?>
                                                        </label><br />
                                                        <label>
                                                            <input class="radio" type="radio" name="attack_type" value="1" <?php if ($form->getValue('attack_type') == 1) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>> <?php echo JR_ATTACK_SCOUT; ?>
                                                        </label><br />
                                                        <input type="hidden" name="uid" value="<?php echo $session->uid; ?>">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="btn">
                                        <button type="submit" value="Simulate an attack" name="s1" id="btn_ok">
                                            <div class="button-container">
                                                <div class="button-position">
                                                    <div class="btl">
                                                        <div class="btr">
                                                            <div class="btc"></div>
                                                        </div>
                                                    </div>
                                                    <div class="bml">
                                                        <div class="bmr">
                                                            <div class="bmc"></div>
                                                        </div>
                                                    </div>
                                                    <div class="bbl">
                                                        <div class="bbr">
                                                            <div class="bbc"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="button-contents"><?php echo JR_WARSIM_SIMULATE; ?></div>
                                            </div>
                                        </button>
                                    </p>
                                </form>

                            </div>
                        </div>
                        <div class="clear"></div>
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
                <?php include 'templates/footer.php'; ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>