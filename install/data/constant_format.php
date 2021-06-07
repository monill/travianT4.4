<?php
ignore_user_abort(true);
//////////////////////////////////
//   ERROR REPORTING
//////////////////////////////////
// (E_ALL ^ E_NOTICE) = enabled
// (0) = disabled
//ini_set('display_errors', 1);

if (file_exists(dirname(__FILE__) . '/Database/connection.php')) {
    include_once(dirname(__FILE__) . "/Database/connection.php");
} elseif (file_exists(dirname(__FILE__) . '/connection.php')) {
    include_once(dirname(__FILE__) . "/connection.php");
} else {
    die('Server is not ready to connect, wait...');
}
mysql_connect(SQL_SERVER, SQL_USER, SQL_PASS);
mysql_select_db(SQL_DB);
$sql = mysql_query("SELECT * FROM " . TB_PREFIX . "config");
$result = mysql_fetch_array($sql);

define('DEVEL_MODE', '0');

$AppConfig['md']['user'] = 'admin_medals';
$AppConfig['md']['password'] = 'YwVx2KToYG';
$AppConfig['md']['database'] = 'admin_medals';

//////////////////////////////////
//     GOLD VALUES
//////////////////////////////////
define("g_club", 100);
define("g_finish", 2);
define("g_plus", 30);
define("g_wood", 10);
define("g_clay", 10);
define("g_iron", 10);
define("g_crop", 10);
define("g_level20", 5);
define("TRADE_TIME", 10800);
define("Activate_Plus", 40);

define("CAGE_MULTIPLIER", 50);
define("EXTRAPLUS", 21600);
define("FREEGOLD_VALUE", 0);
define("ADMIN_PASS", 12500);

define("INVITEPOP", 700);
define("INVITEGOLD", 50);

define("freegold_time", $result['freegold_time']);
define("freegold_lasttime", $result['freegold_lasttime']);

//////////////////////////////////
//  SERVER SETTINGS
//////////////////////////////////

// URL
define('sv_', 'tx500');

// Name
define("SERVER_NAME", $result['server_name']);

// Security signature
define("SECSIG", $result['secsig']);

// Started
// Defines when has server started.
define("COMMENCE", $result['commence']);

// Language
// Choose your server language.
define("LANG", $result['lang']);

// Speed
// Choose your server speed. NOTICE: Higher speed, more likely
// to have some bugs. Lower speed, most likely no major bugs.
// Values: 1 (normal), 3 (3x speed) etc...
define("SPEED", $result['speed']);

// Round lenght
// Choose your server Round lenght.
// Values: 365 (normal), 122 (3x speed) etc...
define("ROUNDLENGHT", $result['roundlenght']);

// World Wonder done moment
define("WINMOMENT", $result['winmoment']);

// World size
// Defines world size. NOTICE: DO NOT EDIT!!
define("WORLD_MAX", "%MAX%");
define("NATARS_MAX", $result['natars_max']);

// Graphic Pack
// True = enabled, false = disabled
//!!!!!!!!!!!! DO NOT ENABLE !!!!!!!!!!!!
define("GP_ENABLE", false);
// Graphic pack location (default: gpack/travian_basic/)
define("GP_LOCATE", $result['gp_locate']);

// Troop Speed
// Values: 1 (normal), 3 (3x speed) etc...
define("INCREASE_SPEED", $result['increase']);

// Hero Power Speed
// Values: 1 (normal), 3 (3x speed) etc...
define("HEROATTRSPEED", $result['heroattrspeed']);

// Item Power Speed
// Values: 1 (normal), 3 (3x speed) etc...
define("ITEMATTRSPEED", $result['itemattrspeed']);

// Change storage capacity
define("STORAGE_MULTIPLIER", $result['storagemultiplier']);
define("STORAGE_BASE", 800 * STORAGE_MULTIPLIER);
define("REWARD_MULTIPLIER", max(1, STORAGE_MULTIPLIER / 2));
// Village Expand
// 1 = slow village expanding - more Cultural Points needed for every new village
// 0 = fast village expanding - less Cultural Points needed for every new village
define("CP", "%VILLAGE_EXPAND%");

// Demolish Level Required
// Defines which level of Main building is required to be able to
// demolish. Min value = 1, max value = 20
// Default: 10
define("DEMOLISH_LEVEL_REQ", $result['demolish_lvl']);

// Ingame quest enabled/disabled.
$quest = $result['taskmaster'] == 1 ? true : false;
define("QUEST", $quest);

// Beginners Protection
// 3600 = 1 hour
// 3600*12 = 12 hours
// 3600*24 = 1 day
// 3600*24*3 = 3 days
// You can choose any value you want!
define("MINPROTECTION", $result['minprotecttime']);
define("MAXPROTECTION", $result['maxprotecttime']);
define("AUCTIONTIME", $result['auctiontime']);
define("MEDALINTERVAL", $result['medalinterval']);

// Enable WW Statistics
$ww = $result['ww'] == 1 ? true : false;
define("SHOWWW2", $ww);

// Activation Mail
// true = activation mail will be sent, users will have to finish registration
//        by clicking on link recieved in mail.
// false =  users can register with any mail. Not needed to be real one.
$auth_email = $result['auth_email'] == 1 ? true : false;
define("AUTH_EMAIL", $auth_email);

// PLUS
//Plus account lenght
define("PLUS_TIME", $result['plus_time']);
//+25% production lenght
define("PLUS_PRODUCTION", $result['plus_prodtime']);
// Great Workshop
define("GREAT_WKS", $result['great_wks']);
// Tourn threshold
define("TS_THRESHOLD", $result['ts_threshold']);


//////////////////////////////////
//     LOG SETTINGS
//////////////////////////////////
// LOG BUILDING/UPGRADING
$log_build = $result['log_build'] == 1 ? true : false;
define("LOG_BUILD", $log_build);

// LOG RESEARCHES
$log_tech = $result['log_tech'] == 1 ? true : false;
define("LOG_TECH", $log_tech);

// LOG USER LOGIN (IP's)
$log_login = $result['log_login'] == 1 ? true : false;
define("LOG_LOGIN", $log_login);

// LOG GOLD
$log_gold = $result['log_gold'] == 1 ? true : false;
define("LOG_GOLD_FIN", $log_gold);

// LOG ADMIN
$log_admin = $result['log_admin'] == 1 ? true : false;
define("LOG_ADMIN", $log_admin);

// LOG ATTACK REPORTS
$log_war = $result['log_war'] == 1 ? true : false;
define("LOG_WAR", $log_war);

// LOG MARKET REPORTS
$log_market = $result['log_market'] == 1 ? true : false;
define("LOG_MARKET", $log_market);

// LOG ILLEGAL ACTIONS
$log_illegal = $result['log_illegal'] == 1 ? true : false;
define("LOG_ILLEGAL", $log_illegal);

//////////////////////////////////
//   NEWSBOX SETTINGS
//////////////////////////////////
//true = enabled
//false = disabled
$newsbox1 = $result['newsbox1'] == 1 ? true : false;
$newsbox2 = $result['newsbox2'] == 1 ? true : false;
$newsbox3 = $result['newsbox3'] == 1 ? true : false;

define("NEWSBOX1", $newsbox1);
define("NEWSBOX2", $newsbox2);
define("NEWSBOX3", $newsbox3);


////////////////////////////////////
//     EXTRA SETTINGS
////////////////////////////////////

// Censore words
//define("WORD_CENSOR", "%ACTCEN%");

// Words (censore)
// Choose which words do you want to be censored
//define("CENSORED","%CENWORDS%");


// Limit Mailbox
// Limits mailbox to defined number of mails. (IGM's)
define("LIMIT_MAILBOX", "%LIMIT_MAILBOX%");
// If enabled, define number of maximum mails.
define("MAX_MAIL", "%MAX_MAILS%");

// Include administrator in statistics/rank
define("INCLUDE_ADMIN", "%ARANK%");

// Register Open/Close
define("REG_OPEN", "%REG_OPEN%");

// Peace system
// 0 = None
// 1 = Normal
// 2 = Christmas
// 3 = New Year
// 4 = Easter
define("PEACE", "%PEACE%");

////////////////////////////////////
//     ADMIN SETTINGS
////////////////////////////////////

// Admin Email
define("ADMIN_EMAIL", $result['admin_email']);

// Admin Name
define("ADMIN_NAME", "%ANAME%");


//////////////////////////////////////////
//     DO NOT EDIT SETTINGS
//////////////////////////////////////////
define("TRACK_USR", "%UTRACK%");
define("USER_TIMEOUT", "%UTOUT%");
define("ALLOW_BURST", false);
define("BASIC_MAX", 1);
define("INNER_MAX", 1);
define("PLUS_MAX", 1);
define("ALLOW_ALL_TRIBE", false);
define("CFM_ADMIN_ACT", true);
define("SERVER_WEB_ROOT", false);
define("USRNM_SPECIAL", false);
define("USRNM_MIN_LENGTH", 3);
define("PW_MIN_LENGTH", 4);
define("BANNED", 0);
define("AUTH", 1);
define("USER", 2);
define("MULTIHUNTER", 8);
define("ADMIN", 9);
define("COOKIE_EXPIRE", 60 * 60 * 24 * 7);
define("COOKIE_PATH", "/");

////////////////////////////////////////////
//     DOMAIN/SERVER SETTINGS
////////////////////////////////////////////
define("DOMAIN", $result['domain_url']);
define("HOMEPAGE", $result['homepage_url']);
define("SERVER", $result['server_url']);

define('TRAPPED_FREEKILL_PORTION', 1 / 3);
define('TRAP_MIN_EFFECT', 0.6);
define('TRAP_MAX_EFFECT', 1.0);

define('ANCINVITEPOP', 250);
define('ANCINVITEGOLD', 60);
define('ANCINVITEMAXCOUNT', 15);
