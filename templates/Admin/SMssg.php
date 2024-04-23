<?php

if ($session->uid > 2 && $session->uid != 4) die('Hacking attemp!');
if (isset($_GET['del'])) {
    $query = "SELECT `id` FROM users ORDER BY id + 0 DESC";
    $result = mysql_query($query) or die(mysql_error());
    $max = mysql_num_rows($result);
    for ($i = 0; $i <= $max; $i++) {
        $row = mysql_fetch_row($result);
        $updateattquery = mysql_query("UPDATE users SET ok = '0' WHERE id = '" . $row[0] . "'")
            or die(mysql_error());
    }
    $fp = fopen('templates/text.php', 'w');
    fwrite($fp, '<?php
$txt="";
// bbcode = html code
$txt = preg_replace("/\[b\]/is",\'<b>\', $txt);
$txt = preg_replace("/\[\/b\]/is",\'</b>\', $txt);
$txt = preg_replace("/\[i\]/is",\'<i>\', $txt);
$txt = preg_replace("/\[\/i\]/is",\'</i>\', $txt);
$txt = preg_replace("/\[u\]/is",\'<u>\', $txt);
$txt = preg_replace("/\[\/u\]/is",\'</u>\', $txt);
?>');
    fclose($fp);
}
if ($_POST['submit'] == 'ارسال') {
    unset($_SESSION['m_message']);
    $_SESSION['m_message'] = $_POST['message'];
    $NextStep = true;
}

if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] == 'نه') $Interupt = true;
    if ($_POST['confirm'] == 'بله') {
        if (file_exists("templates/text.php")) {
            $myFile = 'templates/text.php';
            $fh = fopen($myFile, 'w') or die("<br/><br/><br/>Can't open file: templates/text.php");
            $text = file_get_contents('templates/text_format.php');
            $text = preg_replace("'%TEKST%'", $_SESSION['m_message'], $text);
            fwrite($fh, $text);
            $query = "SELECT `id` FROM users ORDER BY id + 0 DESC";
            $result = mysql_query($query) or die(mysql_error());
            $max = mysql_num_rows($result);
            for ($i = 0; $i <= $max; $i++) {
                $row = mysql_fetch_row($result);
                $updateattquery = mysql_query("UPDATE users SET ok = '1' WHERE id = '" . $row[0] . "'") or die(mysql_error());
            }
            $done = true;
        } else {
            die('<br/><br/><br/>wrong');
        }
    }
}

include('templates/html.php');
include('templates/text.php'); ?>

<h4 class="round">Send a public letter</h4>
<?php if (@!$NextStep && @!$NextStep2 && @!$done) { ?>
    <form method="POST" action="admins.php?tid=2" name="myform" id="myform">
        <table cellspacing="1" cellpadding="1" class="tbg" style="background-color:#C0C0C0; border: 0px solid #C0C0C0; font-size: 10pt;">
            <tbody>
                <tr>
                    <td class="rbg" style="font-size: 10pt; text-align:center;">The universal message</td>
                </tr>
                <tr>
                    <td style="font-size: 10pt; text-align:center;">BB Code :<br><b>[b] txt [/b]</b> - <i>[i] txt [/i]</i> -
                        <u>[u] txt [/u]</u> <br />
                        <textarea class="fm" name="message" cols="60" rows="23"><?php echo $txt; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">All sections are required</td>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <input type="submit" value="ارسال" name="submit" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <a href="Mssg.php?del">Clear previous public message</a>
<?php } elseif (@$NextStep) { ?>
    <form method="POST" action="admins.php?tid=2">
        <table cellspacing="1" cellpadding="2" class="tbg">
            <tbody>
                <tr>
                    <td class="rbg" colspan="2">Message confirmation</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 200px;">Are you sure you want to send the message?</td>
                    <td style="text-align: left;">
                        <input type="submit" style="width: 240px;" class="fm" name="confirm" value="Yes">
                        <input type="submit" style="width: 240px;" class="fm" name="confirm" value="No">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

<?php
    $txt = $_SESSION['m_message'];
    $txt = preg_replace("/\[b\]/is", '<b>', $txt);
    $txt = preg_replace("/\[\/b\]/is", '</b>', $txt);
    $txt = preg_replace("/\[i\]/is", '<i>', $txt);
    $txt = preg_replace("/\[\/i\]/is", '</i>', $txt);
    $txt = preg_replace("/\[u\]/is", '<u>', $txt);
    $txt = preg_replace("/\[\/u\]/is", '</u>', $txt);

    echo 'Sent Message: <br /> <br />';
    echo '
<textarea class="fm" name="message" cols="60" rows="13" disabled=disable>';
    echo $txt;
    echo '</textarea>';
} elseif ($Interupt) { ?>
    <b><?php echo MASS_ABORT; ?></b>
<?php } elseif ($done) { ?>
    A public message was sent
<?php } else {
    die('There is a problem');
} ?>
