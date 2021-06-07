<?php

if ($session->logged_in) { ?>
    </div>
    <div id="servertime" class="stime">
        <?php echo FT_SERVERTIME; ?>&nbsp;
        <span id="tp1"><?php echo date('H:i:s'); ?></span>
    </div>
<?php
}
?>

<div id='footer'>
    <div id='pageLinks'>
        <a href='#'><?php echo FT_HOMEPAGE; ?></a>
        <a href='#'><?php echo FT_FORUM; ?></a>
        <a href='#'><?php echo FT_LINKS; ?></a>
        <a href='#'><?php echo FT_FAQANS; ?></a>
        <a href='#'><?php echo FT_TERMS; ?></a>
        <a class='flink' href='#'><?php echo FT_RULES; ?></a>
    </div>
    <p class="copyright">&nbsp;</p>
</div>