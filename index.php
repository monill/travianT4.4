<?php

if (!file_exists('GameEngine/config.php')) {
    header('Location: install/');
    exit();
} else {
    header('Location: login.php');
    exit();
}
