<?php
include("GameEngine/Protection.php");
include("GameEngine/Village.php");
if ($session->access < 2) {
    header("Location: banned.php");
}
if ($session->goldclub == 0) {
    header("Location: dorf1.php");
}

if ($_POST['type'] == 15) {
    header("Location: " . $_SERVER['PHP_SELF'] . "?s=1&x=" . $_POST['x'] . '&y=' . $_POST['y']);
    die;
} elseif ($_POST['type'] == 9) {
    header("Location: " . $_SERVER['PHP_SELF'] . "?s=2&x=" . $_POST['x'] . '&y=' . $_POST['y']);
    die;
} elseif ($_POST['type'] == 'both') {
    header("Location: " . $_SERVER['PHP_SELF'] . "?s=3&x=" . $_POST['x'] . '&y=' . $_POST['y']);
    die;
}
include("templates/html.php");
?>

<body class="v35 gecko statistics perspectiveBuildings">

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
                        <a id="closeContentButton" class="contentTitleButton" href="dorf1.php" title="Close Window">&nbsp;</a>
                        <a id="answersButton" class="contentTitleButton" href="#" target="_blank" title="Travian Answers">&nbsp;</a>
                    </div>
                    <div class="contentContainer">
                        <div id="content" class="cropfinder">

                            <?php include "templates/cropfinder.php"; ?>
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