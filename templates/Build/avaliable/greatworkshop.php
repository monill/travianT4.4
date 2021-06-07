<h2><?php echo B42; ?></h2>
<div class="build_desc">
    <a href="#" onclick="return Travian.Game.iPopup(42,4);" class="build_logo">
        <img class="building big white g42" src="/assets/images/x.gif" alt="<?php echo B42; ?>">
    </a>
    <?php echo B42_DESC; ?></div></div>
<?php
$_GET['bid'] = 42;
include("availupgrade.php");
?>
<div class="clear"></div>
<hr>
