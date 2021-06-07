<?php
include("GameEngine/Protection.php");
include("GameEngine/Session.php");

$uLang = $_SESSION['lang'];
$session->Logout();
$_SESSION['lang'] = $uLang;

if (isset($_GET['del_cookie'])) {
    header("Location: login.php");
    die;
}

include("templates/html.php");
?>

<body class="v35 webkit chrome logout">

    <div id="background">
        <img id="staticElements" src="/assets/images/x.gif" alt="">

        <div id="bodyWrapper">
            <img style="filter:chroma();" src="/assets/images/x.gif" id="msfilter" alt="">

            <div id="header">
                <div id="mtop">
                    <a id="logo" href="<?php echo HOMEPAGE; ?>" target="_blank" title="<?php echo SERVER_NAME; ?>"></a>

                    <div class="clear"></div>
                </div>
            </div>
            <div id="center">
                <div id="sidebarBeforeContent" class="sidebar beforeContent">
                    <div id="sidebarBoxMenu" class="sidebarBox   ">
                        <div class="sidebarBoxBaseBox">
                            <div class="baseBox baseBoxTop">
                                <div class="baseBox baseBoxBottom">
                                    <div class="baseBox baseBoxCenter"></div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebarBoxInnerBox">
                            <div class="innerBox header noHeader">
                            </div>
                            <div class="innerBox content">
                                <ul>
                                    <li>
                                        <a href="index.php" title="Home">Home</a>
                                    </li>

                                    <li class="active">
                                        <a href="login.php" title="Login">Login</a>
                                    </li>

                                    <li>
                                        <a href="anmelden.php" title="Register">Register</a>
                                    </li>

                                    <li>
                                        <a href="#" title="Forum">Forum</a>
                                    </li>

                                    <li class="support">
                                        <a href="#" title="Support">Support</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="innerBox footer">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="contentOuterContainer">
                    <div class="contentTitle">&nbsp;</div>
                    <div class="contentContainer">
                        <div id="content" class="logout">
                            <h1 class="titleInHeader"><?php echo LGO_LOGOUTTITLE; ?></h1>
                            <h4><?php echo LGO_THANKS; ?></h4>

                            <p><?php echo LGO_DESC; ?></p>

                            <p><a class="arrow" href="login.php?del_cookie"><?php echo LGO_LINK; ?></a></p>

                            <div class="clear">&nbsp;</div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="contentFooter">&nbsp;</div>
                </div>
                <div id="sidebarAfterContent" class="sidebar afterContent">
                    <?php
                    if (NEWSBOX1) {
                        $t1 = trim(file_get_contents("templates/News/newsbox1.php"));
                        if (strlen($t1) > 0) {
                    ?>
                            <div id="sidebarBoxNews1" class="sidebarBox   sidebarBoxNews">
                                <div class="sidebarBoxBaseBox">
                                    <div class="baseBox baseBoxTop">
                                        <div class="baseBox baseBoxBottom">
                                            <div class="baseBox baseBoxCenter"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebarBoxInnerBox">
                                    <div class="innerBox header noHeader"></div>
                                    <div class="innerBox content">
                                        <?php
                                        echo '<div class="news news1">';
                                        ?>
                                        <a href="#" class="newsContent newsContentWithLink" onclick="$H({data:{cmd:'News',id:'1'}}).dialog(); return false;">
                                            <?php
                                            echo $t1 . "</a><br>";
                                            echo '<a class="newsContentMoreInfoLink" target="_blank" href="' . HOMEPAGE . '">' . LG_MOREINFO . '</a></center></div>'; ?>
                                    </div>
                                    <div class="innerBox footer"> </div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                    if (NEWSBOX2) {
                        $t2 = trim(file_get_contents("templates/News/newsbox2.php"));
                        if (strlen($t2) > 0) {
                        ?>
                            <div id="sidebarBoxNews2" class="sidebarBox   sidebarBoxNews">

                                <div class="sidebarBoxBaseBox">
                                    <div class="baseBox baseBoxTop">
                                        <div class="baseBox baseBoxBottom">
                                            <div class="baseBox baseBoxCenter"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebarBoxInnerBox">
                                    <div class="innerBox header noHeader"></div>
                                    <div class="innerBox content">
                                        <?php
                                        echo '<div class="news news2">';
                                        ?>
                                        <a href="#" class="newsContent newsContentWithLink" onclick="$H({data:{cmd:'News',id:'2'}}).dialog(); return false;">
                                            <?php
                                            echo $t2 . "</a><br>";
                                            echo '<a class="newsContentMoreInfoLink" target="_blank" href="' . HOMEPAGE . '">' . LG_MOREINFO . '</a></div>';
                                            ?>
                                    </div>
                                    <div class="innerBox footer">
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
            </div>

            <?php include('templates/footer.php'); ?>
        </div>
        <div id="ce"></div>
    </div>
    </div>
    </div>
</body>

</html>