<?php
$_GET['bid'] = 41;
$bid = $_GET['bid'];
$uprequire = $building->resourceRequired($id, $bid);
?>
<h2><?php echo B41; ?></h2>
<div class="build_desc">
    <a href="#" onclick="return Travian.Game.iPopup(41,4);" class="build_logo">
        <img class="building big white g41" src="/assets/images/x.gif" alt="<?php echo B41; ?>">
    </a>
    <?php echo B41_DESC; ?></div>
<div id="contract" class="contract contractNew contractWrapper">
    <div class="contractText"><?php echo COSTS; ?>:</div>
    <div class="contractCosts">
        <div class="showCosts">
            <span class="resources r1 little_res"><img class="r1" src="/assets/images/x.gif"
                                                       alt="<?php echo VL_WOOD; ?>"><?php echo $uprequire['wood']; ?></span>
            <span class="resources r2 little_res"><img class="r2" src="/assets/images/x.gif"
                                                       alt="<?php echo VL_CLAY; ?>"><?php echo $uprequire['clay']; ?></span>
            <span class="resources r3 little_res"><img class="r3" src="/assets/images/x.gif"
                                                       alt="<?php echo VL_IRON; ?>"><?php echo $uprequire['iron']; ?></span>
            <span class="resources r4"><img class="r4" src="/assets/images/x.gif"
                                            alt="<?php echo VL_CROP; ?>"><?php echo $uprequire['crop']; ?></span>
            <span class="resources r5"><img class="r5" src="/assets/images/x.gif"
                                            alt="<?php echo VL_UPKEEP; ?>"><?php echo $uprequire['pop']; ?></span>
            <div class="clear"></div>
            <span class="clocks"><img class="clock" src="/assets/images/x.gif" alt="<?php echo AT_DURATION; ?>">
    <?php echo $generator->getTimeFormat($uprequire['time']); ?>
	</span>
            <div class="clear"></div>
        </div>
    </div>
    <div class="contractLink">
        <div class="contractText">یش نیازها:</div>
        <span class="buildingCondition">
    <a href="#"
       onclick="return Travian.Game.iPopup(20,4, 'gid');"><?php echo B20; ?></a> <span><?php echo BL_LVL; ?> 20</span></span>,
        <span class="buildingCondition"><a href="#"
                                           onclick="return Travian.Game.iPopup(16,4, 'gid');"><?php echo B16; ?></a> <span><?php echo BL_LVL; ?> 10</span></span>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<hr>
