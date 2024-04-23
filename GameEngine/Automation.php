<?php

//Include needed files
include_once(dirname(__FILE__) . "/" . "Automation.funcs/checkDB.php");
include_once(dirname(__FILE__) . "/" . "Automation.funcs/updatestats.php");
include_once(dirname(__FILE__) . "/" . "Automation.funcs/minimapupdate.php");
require_once(__DIR__ . DIRECTORY_SEPARATOR . "mutex.php");

//Do Process & locks
function do_process($name, $interval = 0)
{
    //echo $name;
    global $gameFinished;
    $mutex = new Mutex($name);
    if (!$gameFinished && $mutex->ControlLock($interval)) {
        try {
            $name();
        } catch (AutomationException $e) {
            saveLog($name . ' --> ' . eEE($e));
        }
        $mutex->releaseLock();
    }
}

// Set contents tu log file
function saveLog($l = "")
{
    file_put_contents(dirname(__FILE__) . "/Prevention/Automation.log", "[" . date("Y-M-D H:i:s") . "] " . $l . PHP_EOL, FILE_APPEND);
}

// Set Exception Message
function eEE(Exception $e)
{
    return 'Message(' . $e->getMessage() . ') File(' . $e->getFile() . ') Line(' . $e->getLine() . ')';
}

$winner = $database->hasWinner();
if (!$winner && COMMENCE < time()) {
    // processes And Lock intervals
    $processes = array(
        "MasterBuilder" => 0,
        "TradeRoute" => 500,
        "auctionComplete" => 1,
        "autoauction" => 300,
        "buildComplete" => 0,
        "celebrationComplete" => 5,
        "checkDB" => 0,
        "clearAndDeleting" => 0,
        "culturePoints" => 1800,
        "demolitionComplete" => 8,
        "invitechecker" => 300,
        "loyaltyRegeneration" => 3600,
        "marketComplete" => 0,
        "medals" => 120,
        "natarsJobs" => 10,
        "reportbox" => 0,
        "researchComplete" => 5,
        "returnunitsComplete" => 0,
        "sendAdventuresComplete" => 0,
        "sendSettlersComplete" => 0,
        "sendreinfunitsComplete" => 0,
        "sendunitsComplete" => 0,
        "trainingComplete" => 0,
        "updateHero" => 3,
        "zeroPopedVillages" => 0
    );

    // check if process exist
    foreach ($processes as $processName => $interval) {
        if (!function_exists($processName)) {
            require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Automation.funcs' . DIRECTORY_SEPARATOR . $processName . '.php');
        }
    }

    // Do process
    foreach ($processes as $processName => $interval) {
        do_process($processName, $interval);
    }
}
