<?php
$count = "0";
include("GameEngine/Config.php");

$connection = mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS) or die(mysql_error());
mysql_select_db(SQL_DB, $connection) or die(mysql_error());

$q = "SELECT * FROM movement where endtime < " . time() . " and proc = 0";
$result = mysql_query($q, $connection);
$count = mysql_num_rows($result);

?>

<h1><img class="point" src="/assets/images/x.gif" alt="" title="" /> event jam (00:00:0?)</h1>

<p>
    All events occurring at a later time get calculated by an event system. In case the server is overloaded or the
    connection between webserver and database is bad an event jam can result of this.
    <br />
    As soon as more events are received per second than can be calculated per second, events (e.g."construction
    finished" or "troops arrived") will be put into a waiting loop.
    <br />
    The chronological order of troop movements stays the same, even in an event jam, so troops that would normally
    arrive somewhere first would still arrive there first.
    <br />

    As player, nothing can be done against an event jam except waiting. Normally these problems get resolved after a
    few minutes automatically. At the moment <b><?php echo $count; ?></b> events await
</p>

<map id="nav" name="nav">
    <?php include('pager.php'); ?>
