<?php

include("GameEngine/Protection.php");
include_once("GameEngine/Village.php");

if ($session->access != ADMIN) {
    echo "You are not an admin. Please don't try.";
} else {
    if (!isset($_GET['tid'])) {
        $_GET['tid'] = 0;
    }
    include("templates/html.php"); ?>

    <body class="v35 webkit chrome statistics">

        <div id="background">
            <div id="headerBar"></div>
            <div id="bodyWrapper">
                <img style="filter:chroma();" src="/assets/images/x.gif" id="msfilter" alt="" />
                <div id="header">
                    <?php
                    include("templates/topheader.php");
                    include("templates/toolbar.php");
                    ?>
                </div>
                <div id="center">
                    <a id="ingameManual" href="help.php">
                        <img class="question" alt="Help" src="/assets/images/x.gif">
                    </a>

                    <?php include("templates/sideinfo.php"); ?>

                    <div id="contentOuterContainer">

                        <?php include("templates/res.php"); ?>
                        <div class="contentTitle">&nbsp;</div>
                        <div class="contentContainer">

                            <div id="content" class="statistics">
                                <script type="text/javascript">
                                    window.addEvent('domready', function() {
                                        $$('.subNavi').each(function(element) {
                                            new Travian.Game.Menu(element);
                                        });
                                    });
                                </script>

                                <h1 class="titleInHeader"> Management <?php if ($session->access == ADMIN) {
                                                                            echo " <a href=\"\"></a>";
                                                                        } ?></h1>
                                <div class="contentNavi subNavi">
                                    <div title="" <?php if (isset($_GET['tid']) && ($_GET['tid'] == 0)) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="admin.php"><span class="tabItem">Direction</span></a>
                                        </div>
                                    </div>
                                    <div title="" <?php if (isset($_GET['tid']) && ($_GET['tid'] == 1)) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="?tid=1"><span class="tabItem">Section 1</span></a></div>
                                    </div>
                                    <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 2) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="?tid=2"><span class="tabItem">Section 2</span></a></div>
                                    </div>
                                    <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 3) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="?tid=3"><span class="tabItem">Section 3</span></a></div>
                                    </div>
                                    <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 4) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="?tid=4"><span class="tabItem">Section 4</span></a></div>
                                    </div>
                                    <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 5) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="?tid=5"><span class="tabItem">Section 5</span></a></div>
                                    </div>
                                    <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 6) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="?tid=6"><span class="tabItem">Section 6</span></a></div>
                                    </div>
                                    <div title="" <?php if (isset($_GET['tid']) && $_GET['tid'] == 7) {
                                                        echo "class=\"container active\"";
                                                    } else {
                                                        echo "class=\"container normal\"";
                                                    } ?>>
                                        <div class="background-start">&nbsp;</div>
                                        <div class="background-end">&nbsp;</div>
                                        <div class="content"><a href="?tid=7"><span class="tabItem">Section 7</span></a></div>
                                    </div>

                                    <div class="clear"></div>
                                </div>
                                <?php
                                function Convertnumber2farsi($srting)
                                {
                                    $num0 = "0";
                                    $num1 = "1";
                                    $num2 = "2";
                                    $num3 = "3";
                                    $num4 = "4";
                                    $num5 = "5";
                                    $num6 = "6";
                                    $num7 = "7";
                                    $num8 = "8";
                                    $num9 = "9";

                                    $stringtemp = "";
                                    $len = strlen($srting);
                                    for ($sub = 0; $sub < $len; $sub++) {
                                        if (substr($srting, $sub, 1) == "0") $stringtemp .= $num0;
                                        elseif (substr($srting, $sub, 1) == "1") $stringtemp .= $num1;
                                        elseif (substr($srting, $sub, 1) == "2") $stringtemp .= $num2;
                                        elseif (substr($srting, $sub, 1) == "3") $stringtemp .= $num3;
                                        elseif (substr($srting, $sub, 1) == "4") $stringtemp .= $num4;
                                        elseif (substr($srting, $sub, 1) == "5") $stringtemp .= $num5;
                                        elseif (substr($srting, $sub, 1) == "6") $stringtemp .= $num6;
                                        elseif (substr($srting, $sub, 1) == "7") $stringtemp .= $num7;
                                        elseif (substr($srting, $sub, 1) == "8") $stringtemp .= $num8;
                                        elseif (substr($srting, $sub, 1) == "9") $stringtemp .= $num9;
                                        else $stringtemp .= substr($srting, $sub, 1);
                                    }
                                    return $stringtemp;
                                }

                                if (isset($_GET['tid']) && ($_GET['tid'] == 0)) {
                                ?>
                                    <h4 class="round">General Overview</h4>
                                    <center><b>*** Description ***</b></center>
                                    <br>
                                    <a href="?tid=1"><b>1. Section <?php echo Convertnumber2farsi(1); ?></b></a>
                                    <br>
                                    This section includes the distribution of medals, public mail, public news, repair settings, registration closures, scroll settings and champion bug fixes.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="?tid=2"><b>2. Section <?php echo Convertnumber2farsi(2); ?></b></a>
                                    <br>
                                    This section contains all the permitted admin and player options available, for example, Force, etc.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="?tid=3"><b>3. Section <?php echo Convertnumber2farsi(3); ?></b></a>
                                    <br>
                                    This section covers all the options related to general server settings, plus option settings and advertising
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="?tid=4"><b>4. Section <?php echo Convertnumber2farsi(4); ?></b></a>
                                    <br>
                                    This section contains options for players emails
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="?tid=5"><b>5. Section <?php echo Convertnumber2farsi(5); ?></b></a>
                                    <br>
                                    This section includes options for letters and reports.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="?tid=6"><b>6. Section <?php echo Convertnumber2farsi(6); ?></b></a>
                                    <br>
                                    This section includes the possibility of adding gender to the auction.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="?tid=7"><b>7. Section <?php echo Convertnumber2farsi(7); ?></b></a>
                                    <br>
                                    This section includes the ability to edit users' gold and silver.
                                    <br><br>
                                    ------------------------------------
                                    <center>
                                        ------------------------------------------------------------------------
                                    </center><br>
                                    - <a href="mset.php">Automatically donate medal setting</a><br>
                                    - <a href="ntroops.php">Add Force</a><br>
                                    - <a href="farmer.php">Add farm</a><br>
                                    - <a href="farmer.php">Farm Management</a>

                                <?php
                                } elseif (isset($_GET['tid']) && ($_GET['tid'] == 1)) {
                                ?>
                                    <h4 class="round">Section <?php echo Convertnumber2farsi(1); ?></h4>
                                    <center><b>Switch</b></center>
                                    <br>

                                    <br>
                                    <a href="Mssg.php"><b>1. Post public news</b></a>
                                    <br>
                                    With this option you will be able to show news to all players
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="Mass.php"><b>2. Send a public letter</b></a>
                                    <br>
                                    With this option you will be able to send letters to all players at once
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="maintenanceset.php"><b>3. Repair settings</b></a>
                                    <br>
                                    With this option you will be able to upgrade the server and players will not be able to play in this mode.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="registercl.php"><b>4. Close registration</b></a>
                                    <br>
                                    With this option you will be able to close or open the registration, if the registration is closed no one will be able to register.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="Artefact.php"><b>5. Inscription settings</b></a>
                                    <br>
                                    Here you can edit settings for server scrolls.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="auctionconf.php"><b>6. Automatic auction settings</b></a>
                                    <br>
                                    Here you can edit the settings for the anomatic auction.
                                    <br><br>
                                <?php
                                } elseif (isset($_GET['tid']) && ($_GET['tid'] == 2)) {
                                ?>
                                    <h4 class="round">Section <?php echo Convertnumber2farsi(2); ?></h4>
                                    <center><b>Switch</b></center>
                                    <br>
                                    <a href="onlinerep.php"><b>1. Online players list</b></a>
                                    <br>
                                    With this option you can see the list of players online and you can also edit and modify the players profile.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="report.php"><b>2. Players list</b></a>
                                    <br>
                                    With this option you can view the player list and also edit and modify the player profile.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="plogin.php"><b>3. Login to the players account</b></a>
                                    <br>
                                    With this option you can log in to your players account.
                                    <br><br>
                                    ------------------------------------

                                    <br>
                                    <a href="villages.php"><b>4. Server Villages</b></a>
                                    <br>
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="oasises.php"><b>Server Server</b></a>
                                    <br>
                                    <br><br>
                                    ------------------------------------
                                    <br><br>
                                <?php
                                } elseif (isset($_GET['tid']) && ($_GET['tid'] == 3)) {
                                ?>
                                    <h4 class="round">Section <?php echo Convertnumber2farsi(3); ?></h4>
                                    <center><b>Switch</b></center>
                                    <br>
                                    <a href="new.php"><b>1. تغییر اخبار</b></a>
                                    <br>
                                    With this option you can make changes to the game news.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="managenews.php"><b>2. Manage game news</b></a>
                                    <br>
                                    With this option you can manage news.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="conf.php"><b>3. Change in config</b></a>
                                    <br>
                                    With this option you will be able to make changes to the configuration.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="peace.php"><b>4. Peace Day</b></a>
                                    <br>
                                    With this option you will be able to change the settings for Peace Day.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="ads.php"><b>5. Advertising system</b></a>
                                    <br>
                                    Here you can set the options for ads.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="pitems.php"><b>6. Plus options</b></a>
                                    <br>
                                    In this section you can change the settings for Plus options.
                                    <br><br>
                                    ------------------------------------
                                    <br><br>
                                <?php
                                } elseif (isset($_GET['tid']) && ($_GET['tid'] == 4)) {
                                ?>
                                    <h4 class="round">Section <?php echo Convertnumber2farsi(4); ?></h4>
                                    <center><b>Options</b></center>
                                    <br>
                                    <a href="email.php"><b>1. Send email to players</b></a>
                                    <br>
                                    With this option you will be able to email all players with just one click.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="emailbk.php"><b>2. Collect emails</b></a>
                                    <br>
                                    With this option you will be able to collect players' emails in both .txt and .zip formats.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="rep.php"><b>3. Server Reports</b></a>
                                    <br>
                                    With this option you will be able to view the complete list of players as well as send the list to your email.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="newsletter.php"><b>4. Newsletter</b></a>
                                    <br>
                                    With this option you will be able to send newsletters.
                                    <br><br>
                                    ------------------------------------
                                    <br><br>
                                <?php
                                } elseif (isset($_GET['tid']) && ($_GET['tid'] == 5)) {
                                ?>
                                    <h4 class="round">Section <?php echo Convertnumber2farsi(5); ?></h4>
                                    <center><b>Options</b></center>
                                    <br>
                                    <a href="payam.php"><b>1. Server Letters</b></a>
                                    <br>
                                    With this option you will be able to view all mail from the server.
                                    <br><br>
                                    ------------------------------------
                                    <br>
                                    <a href="repo.php"><b>2. Server Reports</b></a>
                                    <br>
                                    With this option you will be able to view all server logs.
                                    <br><br>
                                    ------------------------------------
                                    <br><br>
                                <?php
                                } elseif (isset($_GET['tid']) && ($_GET['tid'] == 6)) {
                                    include "./addauction.php";
                                } elseif (isset($_GET['tid']) && ($_GET['tid'] == 7)) {
                                    include "./editcredits.php";
                                };
                                ?>
                            </div>
                            <div id="side_info" class="outgame">
                            </div>
                        </div>
                        <div class="contentFooter">&nbsp;</div>

                    </div>
                    <?php
                    include("templates/rightsideinfor.php");
                    ?>
                    <div class="clear"></div>
                </div>
                <?php
                include("templates/footer.php");
                include("templates/header.php");
                ?>
            </div>
            <div id="ce"></div>
        </div>
    </body>

    </html>

<?php } ?>