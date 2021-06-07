<h1 class="titleInHeader"><?php echo B25; ?> <span class="level"> <?php echo BL_LVL . ' ' . $village->resarray['f' . $id]; ?></span></h1>
<div id="build" class="gid25">
    <div class="build_desc">
        <a href="#" onClick="return Travian.Game.iPopup(25,4, 'gid');" class="build_logo">
            <img class="building big white g25" src="/assets/images/x.gif" alt="<?php echo B25; ?>" title="<?php echo B25; ?>" />
        </a>
        <?php echo B25_DESC; ?>
    </div>

    <?php
    include("upgrade.php");
    include("25_menu.php");
    if ($village->capital == 1) {
        echo "<p class=\"none\">" . BL_CAPITAL . "</p>";
    }
    if ($village->resarray['f' . $id] >= 10) {
        include("25_train.php");
    } else {
        echo '<div class="c">' . BL_TOFINDNEWVIL . '</div>';
    }
    ?>
</div>
<div class="clear">&nbsp;</div>
<div class="clear"></div>