<?php

include('GameEngine/Village.php');
include('templates/html.php');

$start = $generator->pageLoadTimeStart();
if (isset($_GET['ok'])) {
    $database->updateUserField($session->username, 'ok', '0', '0');
    $_SESSION['ok'] = '0';
}
if (isset($_GET['newdid'])) {
    $_SESSION['wid'] = $_GET['newdid'];
    header("Location: " . $_SERVER['PHP_SELF']);
} else {
    $building->procBuild($_GET);
}
include("templates/html.php");
include "templates/Plus/price.php";
// Set Your Setting This Part

$MerchantID = $AppConfig['plus']['payments']['parspal']['MerchantID'];
$Password = $AppConfig['plus']['payments']['parspal']['password'];
$mehdi = explode(",", $_GET['back']);

if (isset($_GET['provider']) or $mehdi) {
    if (isset($_GET['provider'])) {
        $pid = $_GET['provider'];
    }
    if (isset($_GET['back'])) {
        $pid = $mehdi[0];
    }
}

$Prices = array(

    array(
        "package B",
        $AppConfig['plus']['packages'][0]['gold'],
        $AppConfig['plus']['packages'][0]['cost']
    ),
    array(
        "package C",
        $AppConfig['plus']['packages'][1]['gold'],
        $AppConfig['plus']['packages'][1]['cost']
    ),
    array(
        "package D",
        $AppConfig['plus']['packages'][2]['gold'],
        $AppConfig['plus']['packages'][2]['cost']
    ),
    array(
        "package E",
        $AppConfig['plus']['packages'][3]['gold'],
        $AppConfig['plus']['packages'][3]['cost']
    ),
    array(
        "package F",
        $AppConfig['plus']['packages'][4]['gold'],
        $AppConfig['plus']['packages'][4]['cost']
    ),
    array(
        "package g",
        $AppConfig['plus']['packages'][5]['gold'],
        $AppConfig['plus']['packages'][5]['cost']
    ),
    array(
        "package h",
        $AppConfig['plus']['packages'][6]['gold'],
        $AppConfig['plus']['packages'][6]['cost']
    ),
    array(
        "package m",
        $AppConfig['plus']['packages'][7]['gold'],
        $AppConfig['plus']['packages'][7]['cost']
    ),
    array(
        "package a",
        $AppConfig['plus']['packages'][5]['gold'],
        $AppConfig['plus']['packages'][5]['cost']
    )

);

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
                    <?php
                    include('templates/res.php');
                    ?>
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

                            <?php

                            if (isset($pid)) {

                                if ($pid == 1) {
                                    $id = isset($_GET['id']) ? "" : "";

                                    if ($id == "") {

                                        $id = $session->username;

                                        $rest = mysql_query("SELECT * FROM " . TB_PREFIX . "users where `username`='$id' ");
                                        $row = mysql_fetch_assoc($rest);
                                        $Paymenter = $row['username'];
                                        $Email = $row['email'];

                                        if (isset($_GET['buy'])) {

                                            $package = intval($_GET['buy']);
                                            if ($package < 0 || $package >= count($Prices)) {
                                                echo 'The package you found was not found!';
                                            } else {
                                                $Price = intval($Prices[$package][2]);
                                                $ReturnPath = $AppConfig['plus']['payments']['parspal']['Link'] . $package;
                                                $ResNumber = $id;
                                                $Description = urlencode('خريد ' . $Prices[$package][0]);

                                                require_once('templates/Plus/nusoapp.php');

                                                $client = new nusoap_client('http://merchant.parspal.com/WebService.asmx?wsdl', 'wsdl');

                                                $parameters = array(
                                                    "MerchantID" => $MerchantID,
                                                    "Password" => $Password,
                                                    "Price" => $Price,
                                                    "ReturnPath" => $ReturnPath,
                                                    "ResNumber" => $ResNumber,
                                                    "Description" => $Description,
                                                    "Paymenter" => $Paymenter,
                                                    "Email" => $Email,
                                                    "Mobile" => '-'
                                                );

                                                $result = $client->call('RequestPayment', array(
                                                    $parameters
                                                ));
                                                $PayPath = $result['RequestPaymentResult']['PaymentPath'];
                                                $Status = $result['RequestPaymentResult']['ResultStatus'];

                                                if ($Status == 'Succeed') {
                                                    echo '<h1 class="titleInHeader">Port Connection</h1>
                    <div style="text-align:center; font-family:tahoma" >
                    <img src="loading.gif" />
                    <br><br>
                    Connecting to payment gateway, please wait ...</div>
                  <script>
                   window.addEvent("load", function() {
						window.location = "' . $PayPath . '"
					});
                    </script>';
                                                    //echo $PayPath;
                                                } else {
                                                    echo 'There was an error connecting to the port! ' . $Status;
                                                }
                                            }
                                            //echo 'Connect to port'.$id.'-'.$name.'=='.$email;
                                        } else if (isset($_GET['verify'])) {
                                            $package = intval($_GET['verify']);
                                            if ($package < 0 || $package >= count($Prices)) {
                                                echo 'The package you found was not found!';
                                            } else {

                                                echo '<h1 class="titleInHeader">Payment Result</h1>';

                                                if (isset($_POST['status']) && $_POST['status'] == 100) {

                                                    $Price = intval($Prices[$package][2]);
                                                    $Status = $_POST['status'];

                                                    $Refnumber = $_POST['refnumber'];
                                                    $Resnumber = $_POST['resnumber']; //Your Order ID

                                                    require_once('templates/Plus/nusoapp.php');
                                                    $client = new nusoap_client('http://merchant.parspal.com/WebService.asmx?wsdl', 'wsdl');

                                                    if ($id == $Resnumber) {

                                                        $parameters = array(
                                                            "MerchantID" => $MerchantID,
                                                            "Password" => $Password,
                                                            "Price" => $Price,
                                                            "RefNum" => $Refnumber
                                                        );

                                                        $result = $client->call('verifyPayment', $parameters);

                                                        $Status = $result['verifyPaymentResult']['ResultStatus'];
                                                        $PayPrice = $result['verifyPaymentResult']['PayementedPrice'];

                                                        if (strtolower($Status) == 'success') // Your Peyment Code Only This Event
                                                        {
                                                            $gold = $Prices[$package][1];
                                                            $query = mysql_query("UPDATE " . TB_PREFIX . "users SET gold = gold + '" . $gold . "' WHERE username = '" . $id . "'");
                                                            $query2 = mysql_query("UPDATE " . TB_PREFIX . "users SET boughtgold = boughtgold + '" . $gold . "' WHERE username = '" . $id . "'");

                                                            echo '<div style="color:green; font-family:tahoma; direction:rtl; text-align:center">Dear User, Payment successfully completed. Your purchase details are as follows: <br><br>
                            purchased package:' . $Prices[$package][0] . '<br><br>
                            Number of coins:' . $gold . '<br><br>
                            Amount : ' . intval($PayPrice) . '<br><br>
                            Payment Receipt Number: ' . $Refnumber . '<br><br>
        				<br /></div>';

                                                            $subject = "Successful Shopping";
                                                            $sendsms = "Dear user, buy" . $Prices[$package][0] . "Successfully reached number" . $Refnumber . "Done and Number" . $gold . "Coin added to your account.";
                                                            $uid = $row['id'];

                                                            mysql_query("INSERT INTO `" . TB_PREFIX . "mdata` (`target`, `owner`, `topic`, `message`, `viewed`, `archived`, `send`, `time`  ) VALUES( $uid  , 0       , '$subject', '$sendsms', 0   , 0 , 0,  now())");
                                                        } else {
                                                            echo '<br /><br /><br /><br /><br /><div style="color:green; font-family:tahoma; direction:rtl; text-align:center">
	        			Error processing payment operations, payment result: ';
                                                            if ($Status == 'Verifyed')
                                                                echo '<br /><br /><br /><br /><br><br><b>Receipt number is already used!</b>';
                                                            else if ($Status == 'InvalidRef')
                                                                echo '<br /><br /><br /><br /><br><br><b style="color:red">Invalid shipping number!</b>';
                                                            else
                                                                echo $Status;
                                                            echo ' <br /></div>';
                                                        }
                                                    } else {
                                                        echo 'Current user is not the user requesting payment, please submit your receipt number to the admin for review. Receipt Number ' . $Refnumber;
                                                    }
                                                } else {
                                                    echo '<br /><br /><br /><br /><div style="color:red; font-family:tahoma; direction:rtl; text-align:center">
		            Return from payment operations, error in payment operations (delayed payment)!
            		<br /></div>';
                                                }
                                            }
                                        }
                                    }
                                } else {


                                    $id = $session->username;

                                    $rest = mysql_query("SELECT * FROM " . TB_PREFIX . "users where `username`='$id' ");
                                    $row = mysql_fetch_assoc($rest);
                                    $Paymenter = $row['username'];
                                    $Email = $row['email'];
                                    $package = intval($_GET['buy']);
                                    $Price = intval($Prices[$package][2]);

                                    if (isset($_GET['buy'])) {
                                        include_once("mehdi.php");
                                        $url = 'http://payline.ir/payment/gateway-send';
                                        $api = 'd947c-f5dd3-898b6-c106b-001cdc5cd48f38fd5abc865d0a61';
                                        $amount = $Price;
                                        $redirect = $AppConfig['plus']['payments']['payline']['Link'] . $package;
                                        $result = send($url, $api, $amount, $redirect);

                                        if ($result > 0 && is_numeric($result)) {
                                            $go = "http://payline.ir/payment/gateway-$result";
                                            header("Location: $go");
                                        }
                                    }
                                    if (isset($_GET['back'])) {
                                        echo '<h1 class="titleInHeader">Payment result</h1>';

                                        if (isset($_POST['trans_id']) && isset($_POST['"id_get'])) {

                                            $Price = intval($Prices[$package][2]);
                                            include_once("mehdi.php");

                                            $url = 'http://payline.ir/payment/gateway-result-second';
                                            $api = 'd947c-f5dd3-898b6-c106b-001cdc5cd48f38fd5abc865d0a61';
                                            $trans_id = $_POST['trans_id'];
                                            $id_get = $_POST['id_get'];
                                            $result = get($url, $api, $trans_id, $id_get);

                                            if ($result) // Your Peyment Code Only This Event
                                            {

                                                $gold = $Prices[$package][1];
                                                $query = mysql_query("UPDATE " . TB_PREFIX . "users SET gold = gold + '" . $gold . "' WHERE username = '" . $id . "'");
                                                $query2 = mysql_query("UPDATE " . TB_PREFIX . "users SET boughtgold = boughtgold + '" . $gold . "' WHERE username = '" . $id . "'");
                                                echo '<div style="color:green; font-family:tahoma; direction:rtl; text-align:center">
                            Dear User, Payment successfully completed. The details of your purchase are as follows: <br> <br>
                             package purchased: ' . $Prices[$package][0] . '<br> <br>
                             Number of coins: ' . $gold . '<br> <br>
                             Amount: <br> <br>
                             Payment Receipt Number: ' . $trans_id . '<br><br>
        				<br /></div>';

                                                $subject = "Successful Shopping";
                                                $sendsms = "Dear user, buy" . $Prices[$package][0] . "Successfully reached number" . $trans_id . "Done and Number" . $gold . "Coin added to your account.";
                                                $uid = $row['id'];

                                                mysql_query("INSERT INTO `" . TB_PREFIX . "mdata` (`target`, `owner`, `topic`, `message`, `viewed`, `archived`, `send`, `time`  ) VALUES( $uid  , 0       , '$subject', '$sendsms', 0   , 0 , 0,  now())");
                                            } else {
                                                echo '<br /><br /><br /><br /><br /><div style="color:green; font-family:tahoma; direction:rtl; text-align:center">
	        			Error processing payment operations, payment result: ';
                                                if ($result == '-4') {
                                                    echo '<br /><br /><br /><br /><br><br><b>Receipt number is already used!</b>';
                                                } else if ($result == '-2') {
                                                    echo '<br /><br /><br /><br /><br><br><b style="color:red">Invalid shipping number!</b>';
                                                } else {
                                                    echo $result;
                                                }
                                                echo ' <br /></div>';
                                            }
                                        } else {
                                            echo '<br /><br /><br /><br /><div style="color:red; font-family:tahoma; direction:rtl; text-align:center">
		            Return from payment operations, error in payment operations (delayed payment)!
            		<br /></div>';
                                        }
                                    }
                                }
                            } else {
                                echo 'We\'re having trouble choosing the port!';
                            }
                            ?>
                            <font style="left:3px;position:absolute;top:525px" color="#c5c5c5" size="1">
                                Travian Payments by <b> Mehdi Zabet</b>
                            </font>
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
                        <?php
                        include 'templates/sideinfo.php';
                        ?>
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