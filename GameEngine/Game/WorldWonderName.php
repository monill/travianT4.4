<?php

include("../Village.php");
$database->submitWWname($_POST['vref'], $_POST['wwname']);
header("Location: ../../build.php?id=99&n");
?>