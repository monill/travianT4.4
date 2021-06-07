<?php

if ($session->uid > 2 && $session->uid != 4) die('Hacking attemp!');
if (isset($_GET['del'])) {
    $fp = fopen('templates/text_infobox.php', 'w');
    fwrite($fp, '<?php
$txt2="";

// bbcode = html code
$txt2 = preg_replace("/\[b\]/is",\'<b>\', $txt2);
$txt2 = preg_replace("/\[\/b\]/is",\'</b>\', $txt2);
$txt2 = preg_replace("/\[i\]/is",\'<i>\', $txt2);
$txt2 = preg_replace("/\[\/i\]/is",\'</i>\', $txt2);
$txt2 = preg_replace("/\[u\]/is",\'<u>\', $txt2);
$txt2 = preg_replace("/\[\/u\]/is",\'</u>\', $txt2);
?>');
    fclose($fp);
}
if ($_POST['submit'] == 'Transmission') {
    unset($_SESSION['m_message']);
    $_SESSION['m_message'] = $_POST['message'];
    $NextStep = true;
}

if (isset($_POST['confirm'])) {
    if ($_POST['confirm'] == 'not') $Interupt = true;
    if ($_POST['confirm'] == 'yes') {
        if (file_exists("templates/text_infobox.php")) {
            $myFile = 'templates/text_infobox.php';
            $fh = fopen($myFile, 'w') or die("<br/><br/><br/>Can't open file: templates/text_infobox.php");
            $text = file_get_contents('templates/text_infobox_format.php');
            $text = preg_replace("'%TEKST%'", $_SESSION['m_message'], $text);
            fwrite($fh, $text);
            $done = true;
        } else {
            die('<br/><br/><br/>wrong');
        }
    }
}
include('templates/html.php'); ?>

<h4 class="round">Message inside info box</h4>
<?php if (@!$NextStep && @!$NextStep2 && @!$done) { ?>
    <form method="POST" action="admins.php?tid=4" name="myform" id="myform">
        <table cellspacing="1" cellpadding="1" class="tbg" style="background-color:#C0C0C0; border: 0px solid #C0C0C0; font-size: 10pt;">
            <tbody>
                <tr>
                    <td class="rbg" style="font-size: 10pt; text-align:center;">Info box</td>
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
                        <input type="submit" value="Transmission" name="submit" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <a href="admins.php?tid=4&del">Clear previous public message</a>
<?php } elseif (@$NextStep) { ?>
    <form method="POST" action="admins.php?tid=4">
        <table cellspacing="1" cellpadding="2" class="tbg">
            <tbody>
                <tr>
                    <td class="rbg" colspan="2">Message confirmation</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 200px;">Are you sure you want to send the message?</td>
                    <td style="text-align: left;">
                        <input type="submit" style="width: 240px;" class="fm" name="confirm" value="Yes">
                        <input type="submit" style="width: 240px;" class="fm" name="confirm" value="Not">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
<?php
    $txt2 = $_SESSION['m_message'];
    $txt2 = preg_replace("/\[b\]/is", '<b>', $txt2);
    $txt2 = preg_replace("/\[\/b\]/is", '</b>', $txt2);
    $txt2 = preg_replace("/\[i\]/is", '<i>', $txt2);
    $txt2 = preg_replace("/\[\/i\]/is", '</i>', $txt2);
    $txt2 = preg_replace("/\[u\]/is", '<u>', $txt2);
    $txt2 = preg_replace("/\[\/u\]/is", '</u>', $txt2);

    echo 'Sent Message: <br /> <br />';
    echo '
<textarea class="fm" name="message" cols="60" rows="13" disabled=disable>';
    echo $txt2;
    echo '</textarea>';
} elseif ($Interupt) { ?>
    <b><?php echo MASS_ABORT; ?></b>
<?php } elseif ($done) { ?>
    A public message was sent
<?php } else {
    die('There is a problem');
} ?>