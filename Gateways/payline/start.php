<?php

error_reporting(0);
mysql_query('CREATE TABLE IF NOT EXISTS payment_payline(ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY (ID),user text,amount int,id_get int,trans_id int,status text,created int,paid int)');
$url = 'http://payline.ir/payment/gateway-send';

if ($prov['test']) {
    $url = 'http://payline.ir/payment-test/gateway-send';
}

$amount = $prod['cost'] * 10;
$api = $prov['api'];
$redirect = urlencode($prov['return']);

function send($a, $b, $c, $d)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $a);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "api=$b&amount=$c&redirect=$d");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

$result = send($url, $api, $amount, $redirect);

if ($result > 0 && is_numeric($result)) {
    $user = $session->username;
    $amount2 = $amount / 10;
    mysql_query("INSERT INTO payment_payline(user,amount,id_get,status,created) VALUES('$user','$amount2','$result','pending','" . time() . "')");
    $g_url = "http://payline.ir/payment/gateway-$result";
    if ($prov['test']) {
        $g_url = "http://payline.ir/payment-test/gateway-$result";
    }
} else {
    switch ($result) {
        case "-1":
            $g_error = "The api sent is not compatible with the api type defined in the payline";
            break;
        case "-2":
            $g_error = "The amount of data is not numeric or less than 1000";
            break;
        case "-3":
            $g_error = "Redirect value is null string";
            break;
        case "-4":
            $g_error = "No port was found with the information you submitted or is pending";
            break;
        default:
            $g_error = "An unknown error occurred";
            break;
    }
}
?>