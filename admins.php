<?php

include_once("GameEngine/Account.php");
include_once("GameEngine/Village.php");
if ($session->access < MULTIHUNTER) {
    die("Hacking Attempt !");
}
include("templates/html.php");
?>

<body class="v35 gecko statistics perspectiveBuildings">

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
                        <a id="closeContentButton" class="contentTitleButton" href="dorf1.php" title="Close Window">
                            &nbsp;</a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="Travian Answers">&nbsp;</a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="statistics">
                            <h1 class='titleInHeader'>Admin Panel</h1>

                            <div class='contentNavi subNavi'>
                                <div title='' <?php if ((isset($_GET['tid']) && ($_GET['tid'] == 1))) {
                                                    echo 'class=\'container active\'';
                                                } else {
                                                    echo 'class=\'container normal\'';
                                                } ?>>
                                    <div class='background-start'>&nbsp;</div>
                                    <div class='background-end'>&nbsp;</div>
                                    <div class='content'><a href='admins.php?tid=1'><span class='tabItem'>Send in-game message</span></a>
                                    </div>
                                </div>
                                <div title='' <?php if (!isset($_GET['tid']) || isset($_GET['tid']) && ($_GET['tid'] == 2)) {
                                                    echo 'class=\'container active\'';
                                                } else {
                                                    echo 'class=\'container normal\'';
                                                } ?>>
                                    <div class='background-start'>&nbsp;</div>
                                    <div class='background-end'>&nbsp;</div>
                                    <div class='content'><a href='admins.php?tid=2'><span class='tabItem'>Send a public message</span></a>
                                    </div>
                                </div>
                                <div title='' <?php if (isset($_GET['tid']) && $_GET['tid'] == 3) {
                                                    echo 'class=\'container active\'';
                                                } else {
                                                    echo 'class=\'container normal\'';
                                                } ?>>
                                    <div class='background-start'>&nbsp;</div>
                                    <div class='background-end'>&nbsp;</div>
                                    <div class='content'><a href='admins.php?tid=3'><span class='tabItem'>Manage messages</span></a>
                                    </div>
                                </div>
                                <div title='' <?php if (isset($_GET['tid']) && $_GET['tid'] == 4) {
                                                    echo 'class=\'container active\'';
                                                } else {
                                                    echo 'class=\'container normal\'';
                                                } ?>>
                                    <div class='background-start'>&nbsp;</div>
                                    <div class='background-end'>&nbsp;</div>
                                    <div class='content'><a href='admins.php?tid=4'><span class='tabItem'>Message Box Info</span></a>
                                    </div>
                                </div>
                                <div title='' <?php if (isset($_GET['tid']) && $_GET['tid'] == 5) {
                                                    echo 'class=\'container active\'';
                                                } else {
                                                    echo 'class=\'container normal\'';
                                                } ?>>
                                    <div class='background-start'>&nbsp;</div>
                                    <div class='background-end'>&nbsp;</div>
                                    <div class='content'><a href='admins.php?tid=5'><span class='tabItem'>Auction's</span></a></div>
                                </div>
                                <div title='' <?php if (isset($_GET['tid']) && $_GET['tid'] == 6) {
                                                    echo 'class=\'container active\'';
                                                } else {
                                                    echo 'class=\'container normal\'';
                                                } ?>>
                                    <div class='background-start'>&nbsp;</div>
                                    <div class='background-end'>&nbsp;</div>
                                    <div class='content'><a href='admins.php?tid=6'><span class='tabItem'>Farm</span></a>
                                    </div>
                                </div>
                                <div class='clear'></div>
                            </div>
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>
                            <?php

                            if (isset($_GET['tid'])) {
                                switch ($_GET['tid']) {
                                    case 1:
                                        include('templates/Admin/massmessage.php');
                                        break;
                                    case 2:
                                        include('templates/Admin/SMssg.php');
                                        break;
                                    case 3:
                                        include('templates/Admin/MMssg.php');
                                        break;
                                    case 4:
                                        include('templates/Admin/Infobox_message.php');
                                        break;
                                    case 5:
                                        include('templates/Admin/addauction.php');
                                        break;
                                    case 6:
                                        include('templates/Admin/farm.php');
                                        break;
                                    default:
                                        include('templates/Admin/SMssg.php');
                                        break;
                                }
                            } else {
                                include('templates/Admin/SMssg.php');
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
                &nbsp;<?php
                        include 'templates/footer.php';
                        ?>
            </div>
            <div id="ce"></div>
        </div>
</body>

</html>