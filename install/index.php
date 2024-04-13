<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travian Installation</title>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="content-language" content="en" />

    <link href="/assets/css/compact.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/lang.css" rel="stylesheet" type="text/css" />

    <script src="/assets/js/mt-core.js" type="text/javascript"></script>
    <script src="/assets/js/new.js" type="text/javascript"></script>

    <script type="text/javascript">
        window.ajaxToken = '<?php echo md5($_SERVER['REQUEST_TIME']); ?>';
    </script>
</head>

<body class="v35 logout">
    <div id="background">
        <div id="bodyWrapper">
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
                                    <?php include("templates/menu.php"); ?>
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
                        <div id="content" class="statistics">
                            <h1 class="titleInHeader">Travian Installation Script</h1>
                            <?php
                            if (!isset($_GET['s'])) {
                                include("templates/greet.php");
                            } else {
                                switch ($_GET['s']) {
                                    case 1:
                                        include("templates/config.php");
                                        break;
                                    case 2:
                                        include("templates/dataform.php");
                                        break;
                                    case 3:
                                        include("templates/field.php");
                                        break;
                                    case 4:
                                        include("templates/multihunter.php");
                                        break;
                                    case 5:
                                        include("templates/oasis.php");
                                        break;
                                    case 6:
                                        include("templates/end.php");
                                        break;
                                }
                            }
                            ?>
                            <div class="clear">&nbsp;</div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
