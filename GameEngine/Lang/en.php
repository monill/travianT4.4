<?php

include_once("ui/unit.php");
include_once("ui/a2b.php");
include_once("ui/activate.php");
include_once("ui/alliance.php");
include_once("ui/artefacts.php");
include_once("ui/build.php");
include_once("ui/contact.php");
include_once("ui/dorf.php");
include_once("ui/hero.php");
include_once("ui/hero_adv.php");
include_once("ui/hero_auc.php");
include_once("ui/hero_inv.php");
include_once("ui/karte.php");
include_once("ui/login.php");
include_once("ui/logout.php");
include_once("ui/market.php");
include_once("ui/message.php");
include_once("ui/plus.php");
include_once("ui/profile.php");
include_once("ui/report.php");
include_once("ui/user.php");
include_once("ui/village.php");
include_once("ui/warsim.php");

define("DIRECTION", "ltr");
define("TRAVIAN", "Travian");

//Html
define("HTM_REFRESH", "Renewal");
define("HTM_MADECHANGEALERT", "Would you like to close my page?");

//Header
define("HDR_RES", "Resources");
define("HDR_VILCENTER", "Center");
define("HDR_MAP", "Map");
define("HDR_STATIS", "Statistics");
define("HDR_REPORTS", "Reports");
define("HDR_MESSAGES", "Messages");
define("HDR_PROFILE", "Profile");
define("HDR_OPTION", "Settings");
define("HDR_FORUM", "Forum");
define("HDR_CHAT", "Chat");
define("HDR_HELP", "Help");
define("HDR_LOGOUT", "Logout");
define("HDR_GOLD", "Gold");
define("HDR_BUYGOLD", "Get Gold");
define("HDR_SILVER", "Silver");
define("HDR_PROFILE2", "Profile");
define("HDR_OPTION2", "Settings");
define("HDR_FORUM2", "Forum");
define("HDR_HELP2", "Help");
define("HDR_LOGOUT2", "Logout");
define("HDR_CLICKMOREINFO", "Click for more information");
define("HDR_WAREHOUSE", "Warehouse");
define("HDR_GRANARY", "Crop ");
define("HDR_WOOD_PROD", "Wood : ");
define("HDR_CLAY_PROD", "Clay : ");
define("HDR_IRON_PROD", "Iron : ");
define("HDR_CROP_PROD", "Crop : ");
define("HDR_CROP_PROD2", "More wheat: ");

//HERO SIDE
define("HS_ADVENTURE", " Adventure");
define("HS_INHOME", " The hero is in the village");
define("HS_HERODEAD", "The hero is not alive");
define("HS_MOVING", "On the move");
define("HS_RETURN", "Returning");
define("HS_NOTINVIL", "Your hero is not in the village");
define("HS_HEROOVER", "View Hero ||");
define("HS_HEROPOINT", "Hero Scores || Click!");
define("HS_HEROHEALTH", "Health");
define("HS_HEROEXP", "Experience: ");
define("HS_DMINFO", "Show more information");
define("HS_HINFO", "Hide");

//Alliance
define("AL_AUCTION", "Auction || Loading ...");
define("AL_CONSTEMBASY", "Build Alliance.");
define("AL_ALLYFORUM", "Forum Alliance ||");
define("AL_ALLYOVER", "Check Alliance ||");
define("AL_NOALLY", "You're not in alliance");
define("AL_ALLIANCE", "Alliance");
define("AL_TOALLYFORUM", "Alliance Forum");

//infomsg
define("IM_PLUSDEACTIVE", "Check In Plus Account");
define("IM_WOODDEACTIVE", "End more production for wheat at");
define("IM_CLAYDEACTIVE", "Finish producing more for clay in");
define("IM_IRONDEACTIVE", "Finish producing more for iron in");
define("IM_CROPDEACTIVE", "End more production for wheat at");
define("IM_EXTEND", "Extension");

define("IM_STILL", "You're still up");
define("IM_BEGINERPROT", "You are protected");
define("IM_TOTMSG", "Total messages:");

//link list
define("LL_LINKLIST", "Link List || Travian Plus lets you insert links");
define("LL_LINKLIST2", "List of links");
define("LL_TPLUS", "Travian Plus lets you insert links");
define("LL_DELETEIN", "Delete on");

//movement
define("MV_TROOPMOVEMENT", "Moving troops:");
define("MV_ARIREINTROOP", "Backup Receipt");
define("MV_ARIATTTROOP", "Reaching Forces");
define("MV_ATTACK", "Attack");
define("MV_HOUR", "Hour");
define("MV_OWNREINTROOP", "Self Support Force");
define("MV_REINF_SHORT", "Support");
define("MV_OWNATTTROOP", "Insider Attack Forces");
define("MV_NEWVILLAGE", "New Village");
define("MV_ADVENTURE", "Adventure");

//production
define("PD_PRODPERHR", "Products per hour:");
define("PD_LUMBER", "Wood");
define("PD_CLAY", "clay");
define("PD_IRON", "Iron");
define("PD_CROP", "Crop");
define("PD_WOODBONUS", "Increase wood production");
define("PD_CLAYBONUS", "Increased clay production");
define("PD_IRONBONUS", "Increase iron production");
define("PD_CROPBONUS", "Increase crop production");
define("PD_MINFO", "Learn more about increasing productivity");

//troops
define("TR_TROOPS", "Army:");
define("TR_NOTROOPS", "any");

//sideinfo
define("SI_DIRECTLINK", "Links || This feature requires Travian Plus");
define("SI_BUILDWORKSHOP", "Build a workshop.");
define("SI_BUILDSTABLEP", "Build stables.");
define("SI_BUILDBARRACKS", "Build a house.");
define("SI_BUILDMARKET", "Build a market.");
define("SI_LOYALTY", "loyalty");
define("SI_CHANGEVILNAME", "Rename the village");
define("SI_NEWVILNAME", "New name:");
define("SI_SAVE", "Save");

//multivillage
define("MV_PLUS", "Travian Plus || Plus Features");
define("MV_PLUS2", "Plus");

define("MV_BANK", "Gold Bank || Travian Gold Storage");
define("MV_BANK2", "Gold Bank");
define("MV_VILL", "Villages: ");
define("MV_CULTURE", "Cultural points to build the next village:");
define("MV_VILL2", "Villages");

define("MV_SHOWCORD", "Show coordinates");
define("MV_HIDECORD", "Hide coordinates");

//quest
define("QS_DISPTASK", "View task list");
define("QS_DISPINTERF", "Show help");

define("QS_TASKHELP", "Duties");
define("QS_DISWELCOME", "Welcome");
define("QS_STARTTUT", "Starting tasks");
define("QS_ACCINFO", "Account Information");

//footer
define("FT_SERVERTIME", "Server Time");
define("FT_HOMEPAGE", "Home page");
define("FT_FORUM", "Forum");
define("FT_LINKS", "Links");
define("FT_FAQANS", "FAQ");
define("FT_TERMS", "Terms of service");
define("FT_RULES", "Rules");

define('MAXSELL', '5');
define('MAXSELLDESC', 'You can have %s sell at the same time!');
define("CUR_PROD", "Current production");
define("NEXT_PROD", "Next level production");
define("CUR_INC_PROD", "Increase in production");
define("NEXT_INC_PROD", "Increase in level");
define("CUR_CAP", "Current capacity");
define("NEXT_CAP", "Next level capacity");
define("CUR_SPEEDUP", "Increase current speed (percent)");
define("NEXT_SPEEDUP", "Increase speed on the surface");
define("CUR_CTIME", "Current construction time");
define("NEXT_CTIME", "Construction time at the next level");
