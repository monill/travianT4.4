<?php
if (!isset($aid)) {
    $aid = $session->alliance;
}
$allianceinfo = $database->getAlliance($aid);
echo "<h1 class=\"titleInHeader\">" . $allianceinfo['tag'] . " - " . $allianceinfo['name'] . "</h1>";
include("alli_menu.php");
?>
<form method="post" action="allianz.php">
    <input type="hidden" name="a" value="5">
    <input type="hidden" name="o" value="5">
    <input type="hidden" name="s" value="5">

    <tr>
        <th colspan="2"><b><?php echo AL_LINKTOFORUM; ?></b></th>
    </tr>

    </thead>
    <tbody>
        <br />
        <tr>
            <th>URL:</th>
            <td><input class="link text" type="text" name="f_link" value="" maxlength="200"></td>
        </tr>
        <br />
        <tr>
            <td colspan="2" class="info"><?php echo AL_OUTERFORUMDESC; ?></td>
        </tr>
    </tbody>
    </table>

    <p><input type="image" value="ok" name="s1" id="btn_ok" class="dynamic_img" src="/assets/images/x.gif" alt="OK" />
</form>
</p>