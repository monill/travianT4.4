<?php
include("GameEngine/Session.php");
?>
<html>

<head>
    <title><?php echo SERVER_NAME; ?></title>
    <link REL="shortcut icon" HREF="favicon.ico" />
    <meta name="content-language" content="<?php echo $_SESSION['lang']; ?>" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link href="/assets/css/compact.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/lang.css" rel="stylesheet" type="text/css" />
</head>

<body class="manual">
    <?php

    $S = filter_var($_GET['s'], FILTER_SANITIZE_NUMBER_INT);
    $TYPE = filter_var($_GET['typ'], FILTER_SANITIZE_NUMBER_INT);

    if (!ctype_digit($S)) {
        $S = '0';
    }

    if (!ctype_digit($TYPE)) {
        $TYPE = null;
    }

    if (!isset($TYPE) && !isset($S)) {
        include('templates/Manual/0.php');
    } else if (!isset($TYPE) && $S == 1) {
        include('templates/Manual/00.php');
    } elseif (!isset($TYPE) && $S == 2) {
        include('templates/Manual/0.php');
    } elseif (isset($TYPE) && $TYPE == 5 && $S == 3) {
        include('templates/Manual/0.php');
    } else {
        if (isset($_GET['gid'])) {
            include('templates/Manual/' . $TYPE . (preg_replace("/[^a-zA-Z0-9_-]/", '', $_GET['gid'])) . '.php');
        } else {
            if ($TYPE == 4 && $S == 0) {
                $S = 1;
            }
            include('templates/Manual/' . $TYPE . preg_replace("/[^a-zA-Z0-9_-]/", '', $S) . '.php');
        }
    }
    ?>
</body>

</html>