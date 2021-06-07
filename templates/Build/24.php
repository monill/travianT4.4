<h1 class="titleInHeader"><?php echo B24; ?> <span
            class="level"><?php echo BL_LVL . $village->resarray['f' . $id]; ?></span></h1>
<div id="build" class="gid24">
    <div class="build_desc">
        <a href="#" onClick="return Travian.Game.iPopup(24,4);" class="build_logo">
            <img class="building big white g24" src="/assets/images/x.gif" alt="<?php echo B24; ?>" title="<?php echo B24; ?>"/>
        </a>
        <?php echo B24_DESC; ?>
    </div>

    <?php
    include("upgrade.php");
    if ($building->getTypeLevel(24) > 0) {
        include("templates/Build/24_1.php");
        include("templates/Build/24_2.php");
    }
    ?>
</div>