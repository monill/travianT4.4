<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment result</title>
    <style>
        body {
            direction: rtl;
            font-family: tahoma;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <br>
    <center>
        <?php
        error_reporting(0);
        if (file_exists("../../GameEngine/Config.php")) {
            include "../../GameEngine/Config.php";
        } else {
            include "../../GameEngine/config.php";
        }
        include "../../templates/Plus/price2.php";
        $api = $AppConfig['plus']['payments']['payline']['api'];

        @header('Content-Type: text/html; charset=utf-8');

        function get($a, $b, $c, $d)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $a);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "api=$b&id_get=$d&trans_id=$c");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        }

        $url = 'http://payline.ir/payment/gateway-result-second';
        if ($AppConfig['plus']['payments']['payline']['test']) {
            $url = 'http://payline.ir/payment-test/gateway-result-second';
        }
        $trans_id = $_POST['trans_id'];
        $id_get = $_POST['id_get'];
        $result = get($url, $api, $trans_id, $id_get);
        mysql_query("UPDATE payment_payline SET trans_id='$trans_id' WHERE id_get='$id_get'");
        switch ($result) {
            case "-1":
                echo "Api sent is not compatible with api type defined in payline";
                break;
            case "-2":
                echo "trans_id submission not valid";
                break;
            case "-3":
                echo "id_get sent invalid";
                break;
            case "-4":
                echo "There is no such transaction in the system or it has not been successful";
                break;
            case "1":
                $success = true;
                break;
            default:
                echo "An unknown error occurred";
                break;
        }

        if ($success) {
            $result = mysql_query("SELECT * FROM payment_payline WHERE id_get='$id_get'");
            $row = mysql_fetch_array($result);
            if (@$row['status'] == "pending") {
                mysql_query("UPDATE payment_payline SET status='paid' WHERE id_get='$id_get'");
                mysql_query("UPDATE payment_payline SET paid='" . time() . "' WHERE id_get='$id_get'");
                $gold = 0;
                foreach ($AppConfig['plus']['packages'] as $pkg) {
                    if ($row['amount'] == $pkg['cost']) {
                        $gold = $pkg['gold'];
                        break;
                    }
                }
                mysql_query("UPDATE users SET gold = gold + $gold, boughtgold = boughtgold + $gold WHERE username='" . $row['user'] . "'");
                // ADD CREDIT TO USER's ACCOUNT
                echo "Your payment in cash" . $row['amount'] . " Successful";
            } else {
                echo "This payment has already been calculated.";
            }
        }

        ?>
    </center>
</body>

</html>
