<h1 class="titleInHeader"><?php echo B4; ?> <span
            class="level"> <?php echo BL_LVL . ' ' . $village->resarray['f' . $id]; ?></span></h1>
<div id="build" class="gid4">
    <div class="build_desc">
        <a href="#" onClick="return Travian.Game.iPopup(4,4);" class="build_logo">
            <img class="building big white g4" src="/assets/images/x.gif" alt="<?php echo B4; ?>" title="<?php echo B4; ?>"/>
        </a>
        <?php echo B4_DESC; ?></div>

    <table cellpadding="1" cellspacing="1" id="build_value">
        <tr>
            <th><?php echo CUR_PROD; ?>:</th>
            <td><b><?php echo $bid4[$village->resarray['f' . $id]]['prod'] * SPEED; ?></b> <?php echo PER_HR; ?></td>
        </tr>
        <tr>
            <?php
            if (!$building->isMax($village->resarray['f' . $id . 't'], $id)) {
            $loopsame = $building->isCurrent($id) ? 1 : 0;
            $doublebuild = 0;
            if ($loopsame > 0 && $building->isLoop($id)) {
                $doublebuild = 1;
            }
            $nli = ($loopsame > 0 ? 2 : 1) + $doublebuild;
            $bindicate = $building->canBuild($id, $village->resarray['f' . $id . 't']);
            $ll = $nli;
            if ($bindicate == 10 || $bindicate == 1) $ll -= 1;
            for ($nc = 1;
            $nc <= $ll;
            $nc++){
            ?>
        <tr <?php if ($nc < $nli) echo 'style="color:#F88C1F;"'; ?>>
            <th><?php echo NEXT_PROD;
                echo $village->resarray['f' . $id] + $nc; ?>:
            </th>
            <td><b><?php echo $bid4[$village->resarray['f' . $id] + $nc]['prod'] * SPEED; ?></b> <?php echo PER_HR; ?>
            </td>
        </tr>
        <?php
        }
        } ?>
        </tr>
    </table>

    <?php
    include("upgrade.php");
    ?></p></div>
