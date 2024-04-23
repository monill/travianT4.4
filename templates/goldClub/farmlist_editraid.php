<?php
if (isset($_GET['action']) == 'editSlot' && $_GET['eid']) {
    $eiddata = $database->getRaidList($_GET['eid']);
    $x = $eiddata['x'];
    $y = $eiddata['y'];
    $t1 = $eiddata['t1'];
    $t2 = $eiddata['t2'];
    $t3 = $eiddata['t3'];
    $t4 = $eiddata['t4'];
    $t5 = $eiddata['t5'];
    $t6 = $eiddata['t6'];
    $t7 = $eiddata['t7'];
    $t8 = $eiddata['t8'];
    $t9 = $eiddata['t9'];
    $t10 = $eiddata['t10'];
}

if (isset($_POST['action']) == 'editSlot' && $_POST['eid']) {

    if (!isset($_POST['x']) or $_POST['x'] == '' or !isset($_POST['y']) or $_POST['y'] == '') {
        $errormsg .= PL_ENTERCOORDINATES;
    } elseif (($_POST['x'] > WORLD_MAX or $_POST['x'] < -WORLD_MAX) or ($_POST['y'] > WORLD_MAX or $_POST['y'] < -WORLD_MAX)) {
        $errormsg .= PL_ENTERCORRECTCOORDINATES;
    } else {
        $Wref = $database->getVilWref($_POST['x'], $_POST['y']);
        $isoasis = $database->isVillageOases($Wref);
        $vdata['owner'] = 0;
        if (!$isoasis) $vdata = $database->getMInfo($Wref);
        if (!$isoasis && $vdata['owner'] == 0) {
            $errormsg .= PL_NOVILLATCOORDINATES;
        } else {
            $troops = 0;
            for ($i = 1; $i <= 10; $i++) {
                if (isset($_POST['t' . $i]) and $_POST['t' . $i] > 0) {
                    $troops = $troops + $_POST['t' . $i];
                }
            }
            if ($troops == 0) {
                $errormsg .= PL_NOTROOPSSELECTED;
            } else {

                $Wref = $database->getVilWref($_POST['x'], $_POST['y']);
                $coor = $database->getCoor($village->wid);

                function getDistance($coorx1, $coory1, $coorx2, $coory2)
                {
                    $max = 2 * WORLD_MAX + 1;
                    $x1 = intval($coorx1);
                    $y1 = intval($coory1);
                    $x2 = intval($coorx2);
                    $y2 = intval($coory2);
                    $distanceX = min(abs($x2 - $x1), abs($max - abs($x2 - $x1)));
                    $distanceY = min(abs($y2 - $y1), abs($max - abs($y2 - $y1)));
                    $dist = sqrt(pow($distanceX, 2) + pow($distanceY, 2));

                    return round($dist, 1);
                }

                $distance = getDistance($coor['x'], $coor['y'], $_POST['x'], $_POST['y']);

                $database->editSlotFarm($_GET['eid'], $_POST['lid'], $Wref, $_POST['x'], $_POST['y'], $distance, $_POST['t1'], $_POST['t2'], $_POST['t3'], $_POST['t4'], $_POST['t5'], $_POST['t6'], $_POST['t7'], $_POST['t8'], $_POST['t9'], $_POST['t10']);

                header("Location: build.php?id=39&t=99");
                die;
            }
        }
    }
}
?>

<script type="text/javascript">
    var targets = {};

    function fillTargets() {
        var targetId = $('target_id');

        targetId.empty();

        var option = new Element('option', {
            'html': '<?php echo PL_SELECTVILLAGE; ?>'
        });
        targetId.insert(option);

        $each(targets[lid], function(data) {
            var option = new Element('option', {
                'value': data.did,
                'html': data.name
            });
            targetId.insert(option);
        });
    }

    function getTargetsByLid() {
        var lidSelect = $('lid');
        lid = lidSelect.getSelected()[0].value;

        if (targets[lid]) {
            fillTargets();
        } else {
            Travian.ajax({
                data: {
                    cmd: 'raidListTargets',
                    'lid': lid
                },
                onSuccess: function(data) {
                    targets[data.lid] = data.targets;
                    fillTargets();
                }
            });

        }
    }

    function selectCoordinates() {
        var targetId = $('target_id');
        var did = targetId.getSelected()[0].value;

        if (did === '') {
            $('xCoordInput').value = '';
            $('yCoordInput').value = '';
        } else {
            var array;
            $each(targets[lid], function(data) {
                if (data.did === did) {
                    array = data;
                    return;
                }
            });

            $('xCoordInput').value = array.x;
            $('yCoordInput').value = array.y;
        }
    }

    var lid = <?php echo $_GET['lid']; ?>;
    targets[lid] = {};
</script>

<div id="raidListSlot">
    <h4><?php echo PL_ADDSLOT; ?></h4>
    <font color="#FF0000"><b>
            <?php echo $errormsg; ?>
        </b></font>

    <form id="edit_form" action="build.php?id=39&t=99&action=showSlot&eid=<?php echo $_GET['eid']; ?>" method="post">
        <div class="boxes boxesColor gray">
            <div class="boxes-tl"></div>
            <div class="boxes-tr"></div>
            <div class="boxes-tc"></div>
            <div class="boxes-ml"></div>
            <div class="boxes-mr"></div>
            <div class="boxes-mc"></div>
            <div class="boxes-bl"></div>
            <div class="boxes-br"></div>
            <div class="boxes-bc"></div>
            <div class="boxes-contents cf">

                <?php
                $getlid = $database->getRaidList($_GET["eid"]);
                $lid2 = $getlid['lid'];
                ?>
                <input type="hidden" name="action" value="editSlot">
                <input type="hidden" name="eid" value="<?php echo $_GET['eid']; ?>">
                <input type="hidden" name="lid" value="<?php echo $lid2; ?>">

                <table cellpadding="1" cellspacing="1" class="transparent">
                    <tbody>
                        <tr>
                            <th><?php echo PL_FARMNAME; ?>:</th><?php echo $_GET["lid"]; ?>
                            <td>
                                <select onchange="getTargetsByLid();" id="lid" name="lid">
                                    <?php
                                    $sql = mysql_query("SELECT * FROM farmlist WHERE owner = $session->uid ORDER BY name ASC");
                                    while ($row = mysql_fetch_array($sql)) {
                                        $lid = $row["id"];
                                        $lname = $row["name"];
                                        $lowner = $row["owner"];
                                        $lwref = $row["wref"];
                                        $lvname = $database->getVillageField($row["wref"], 'name');

                                        if ($lid == $lid2) {
                                            $selected = 'selected=""';
                                        } else {
                                            $selected = '';
                                        }
                                        echo '<option value="' . $lid . '" ' . $selected . '>' . $lvname . ' - ' . $lname . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo PL_SELECTTARGET; ?>:</th>
                            <td class="target">

                                <div class="coordinatesInput">
                                    <div class="xCoord">
                                        <label for="xCoordInput">X:</label>
                                        <input value="<?php echo $x; ?>" name="x" id="xCoordInput" class="text coordinates x ">
                                    </div>
                                    <div class="yCoord">
                                        <label for="yCoordInput">Y:</label>
                                        <input value="<?php echo $y; ?>" name="y" id="yCoordInput" class="text coordinates y ">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="targetSelect">
                                    <label class="lastTargets" for="last_targets"><?php echo PL_LASTTARGETS; ?>:</label>
                                    <select id="target_id" name="target_id" onchange="selectCoordinates()">
                                        <option value=""><?php echo PL_SELECTVILLAGE; ?></option>
                                    </select>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php include "templates/goldClub/trooplist.php"; ?>
        <button type="submit" value="save" class="green build" name="s1" id="btn_save">
            <div class="button-container addHoverClick">
                <div class="button-background">
                    <div class="buttonStart">
                        <div class="buttonEnd">
                            <div class="buttonMiddle"></div>
                        </div>
                    </div>
                </div>
                <div class="button-content"><?php echo SI_SAVE; ?></div>
            </div>
        </button>
        &nbsp;
        <button type="button" value="delete" name="delete" id="delete" class="green " onclick="return (function(){
                ('<?php echo PL_CONFIRMDELLIST; ?>').dialog(
                {
                onOkay: function(dialog, contentElement)
                {
                window.location.href = 'build.php?id=39&t=99&action=removeSlot&eid=<?php echo $_GET['eid']; ?>'}
                });
                return false;
                })()">
            <div class="button-container addHoverClick">
                <div class="button-background">
                    <div class="buttonStart">
                        <div class="buttonEnd">
                            <div class="buttonMiddle"></div>
                        </div>
                    </div>
                </div>
                <div class="button-content"><?php echo AL_DELETE; ?></div>
            </div>
        </button>
    </form>
</div>
