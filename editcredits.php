<?php

include("GameEngine/Protection.php");
include_once("GameEngine/Village.php");

if ($session->access != ADMIN) {
    die("You are not an admin. Please don't try.");
}
if (isset($_POST['method'])) {
    if (is_numeric($_POST['amount']) && $_POST['amount'] > 0) {
        $amount = $_POST['amount'];
        if (strlen($_POST['uname']) > 0) {
            $uname = $_POST['uname'];
            $result = mysql_query("SELECT * FROM users WHERE username='$uname'");
            if (@mysql_num_rows($result) >= 1) {
                $row = mysql_fetch_array($result);
                $gold = $row['gold'];
                $silver = $row['silver'];
                switch ($_POST['method']) {
                    case "addgold":
                        $gold = $gold + $amount;
                        echo "the amount of " . $amount . " Gold unit to user " . $uname . " added. Total Gold: " . $gold . "<br>";
                        mysql_query("UPDATE users SET gold=" . $gold . " WHERE username='$uname'");
                        break;
                    case "addsilver":
                        $silver = $silver + $amount;
                        echo "the amount of " . $amount . " Silver unit to user " . $uname . "added. Total Silver: " . $silver . "<br>";
                        mysql_query("UPDATE users SET silver=" . $silver . " WHERE username='$uname'");
                        break;
                    default:
                        echo "Unknown method<br>";
                }
            }
        }
    } else {
        echo "Unknown method.<br>";
    }
}
?>
<h1 style="border-bottom:1px black solid;">Gold</h1>
<table>
    <tr>
        <td>Action</td>
        <td>the amount of</td>
        <td>User</td>
        <td>the operation</td>
    </tr>
    <tr>
        <form action="" method="post">
            <input type="hidden" value="addgold" name="method">
            <td>add</td>
            <td><input type="number" value="" name="amount"></td>
            <td><input type="text" value="" name="uname"></td>
            <td><input type="submit" value="انجام"></td>
        </form>
    </tr>
</table>
<br>
<h1 style="border-bottom:1px black solid;">Silver</h1>
<table>
    <tr>
        <td>Action</td>
        <td>the amount of</td>
        <td>User</td>
        <td>the operation</td>
    </tr>
    <tr>
        <form action="" method="post">
            <input type="hidden" value="addsilver" name="method">
            <td>add</td>
            <td><input type="number" value="" name="amount"></td>
            <td><input type="text" value="" name="uname"></td>
            <td><input type="submit" value="انجام"></td>
        </form>
    </tr>
</table>
