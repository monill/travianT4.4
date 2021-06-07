<?php

include("templates/Plus/price.php");
include("GameEngine/Village.php");
$start = $generator->pageLoadTimeStart();
include("templates/html.php");
?>

<body class="v35 webkit chrome statistics">

    <div id="background">
        <div id="headerBar"></div>
        <div id="bodyWrapper">
            <img style="filter:chroma();" src="/assets/images/x.gif" id="msfilter" alt="" />
            <div id="header">
                <div id="mtop">
                    <?php
                    include("templates/topheader.php");
                    include("templates/toolbar.php");
                    ?>
                </div>
            </div>
            <div id="center">
                <?php include("templates/sideinfo.php"); ?>

                <div id="contentOuterContainer">
                    <?php include("templates/res.php"); ?>
                    <div class="contentTitle">&nbsp;</div>
                    <div class="contentContainer">
                        <div id="content" class="plus">
                            <script type="text/javascript">
                                window.addEvent('domready', function() {
                                    $$('.subNavi').each(function(element) {
                                        new Travian.Game.Menu(element);
                                    });
                                });
                            </script>
                            <center>
                                <h2> Wait for connection to port
                                    <h2>
                                        <center>
                                            <?php
                                            if (!isset($_GET['c'])) {
                                                header('Location: plus.php');
                                            } else if (isset($_GET['c'])) {
                                                if (($_GET['c'] == 1) || ($_GET['c'] == 2) || ($_GET['c'] == 3) || ($_GET['c'] == 4) || ($_GET['c'] == 5) || ($_GET['c'] == 6) || ($_GET['c'] == 7) || ($_GET['c'] == 8)) {
                                                    
                                                    $id = $session->uid;
                                                    $name = $session->username;
                                                    $email = $session->email;
                                                    $_GET['c'] = $_GET['c'] - 1;
                                                    $Esi = explode('pay.php', $_SERVER['REQUEST_URI']);
                                                    $ReturnPath = "http://" . $_SERVER['HTTP_HOST'] . $Esi[0] . "parspal.php";
                                                    $Codemaker = rand(10000, 200000000);
                                                    $goldenb = $AppConfig['plus']['packages'][$_GET['c']]['gold'];

                                                    $price = $AppConfig['plus']['packages'][$_GET['c']]['cost'];
                                                    $token = md5(sprintf("%s:%s:%s:%s", $AppConfig['plus']['payments']['paypal']['merchant_id'], $price, strtolower($AppConfig['plus']['payments']['paypal']['currency']), $AppConfig['plus']['payments']['paypal']['testMode'] ? $AppConfig['plus']['payments']['paypal']['testKey'] : $AppConfig['plus']['payments']['paypal']['key']));
                                                    $dtest = sprintf("%s " . text_gold_lang, $AppConfig['plus']['packages'][$_GET['c']]['gold']);

                                                    echo '<form action="http://merchant.parspal.com/postservice/" method="post" name="payment"/>
                                                    <input type="hidden" id="MerchantID" value="' . $AppConfig['plus']['payments']['paypal']['merchant_id'] . '" name="MerchantID"/>
                                                    <input type="hidden" id="Password" value="' . $AppConfig['plus']['payments']['paypal']['key'] . '" name="Password"/>
                                                    <input type="hidden" id="Paymenter" value="' . $name . '" name="Paymenter"/>
                                                    <input type="hidden" id="Email" value="' . $email . '" name="Email"/>
                                                    <input type="hidden" id="Price" value="' . $price . '" name="Price"/>
                                                    <input type="hidden" id="Mobile" value="' . $id . '" name="Mobile"/>
                                                    <input type="hidden" id="ResNumber" value="' . $session->uid . '_' . $_GET['c'] . '" name="ResNumber"/>
                                                    <input type="hidden" id="Description" value="' . $Codemaker . '" name="Description"/>
                                                    <input type="hidden" id="ReturnPath" value="' . $ReturnPath . '" name="ReturnPath"/>
                                                    <script language="Javascript">document.payment.submit();</script></form>';
                                                } else {
                                                    echo '<p align="center">
                                                <fieldset style="padding: 0">
                                                <legend> Fraud </legend>
                                                   You are trying to cheat gold. Please stop. Otherwise your account will be reported to Multi Hunter. <br />
                                                 Your IP has been registered to the server
                                                </fieldset></p>';
                                                }
                                            }
                                            ?>

                        </div>
                    </div>
                    <div class="contentFooter">&nbsp;</div>
                </div>
                <?php include("templates/rightsideinfor.php"); ?>
                <div class="clear"></div>
            </div>
            <?php
            include("templates/footer.php");
            include("templates/time.php");
            ?>
            <div id="ce"></div>
        </div>
</body>

</html>