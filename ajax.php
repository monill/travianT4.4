<?php
include("GameEngine/Protection.php");
include("GameEngine/Village.php");

if ($_GET['buyAdventure'] == "yes") {
    if ($session->gold >= 1) {
        $herodetail = $database->getHero($session->uid);
        $aday = max(86400 / SPEED, 1800);
        $tenday = max(432000 / SPEED, 18000);
        $endat = $herodetail['lastadv'] + $tenday;

        $dif = rand(0, 10) > 8;
        $database->addAdventure($database->getVFH($herodetail['uid']), $herodetail['uid'], $endat, $dif);
        $herodetail['lastadv'] += $aday;
        $endat += $aday;
        mysql_query("UPDATE users SET gold = gold - 1, usedgold=usedgold+1 WHERE `username`='" . $session->username . "'");
        header("Location: hero_adventure.php");
        echo "Why you have not redirected!?";
        exit();
    }
}

if (isset($_GET['qact'])) {
    $qact = $_GET['qact'];
} else {
    $qact = null;
}
if (isset($_GET['qact2'])) {
    $qact2 = $_GET['qact2'];
} else {
    $qact2 = null;
}

if ($_GET['f'] == "qst") {
    include "templates/Ajax/quest_core.php";
    exit();
}
if ($_GET) {
    if (isset($_GET['cmd'])) {
        switch ($_GET['cmd']) {
            case 'productionBoostPopup':
                $choosepack = 0;
                $advantages = 0;
                $earngold = 0;
                $plusSupport = 0;
                $choosepayment = 1;
                $userData = $database->getUser($session->username);
                $golds = $database->getUser($session->username, 0);
                $datetimep = $userData['plus'];
                $datetime1 = $userData['b1'];
                $datetime2 = $userData['b2'];
                $datetime3 = $userData['b3'];
                $datetime4 = $userData['b4'];
                $datetimeap = $userData['ap'];
                $datetimedp = $userData['dp'];
                $now = strtotime("NOW");

                include "templates/Plus/25dialog.php";
                break;
            case 'viewTileDetails':
                $x = $_POST['x'];
                $y = $_POST['y'];
                ob_start(); // begin collecting output

                include 'templates/Map/vildialog.php';
                $html = ob_get_clean(); // retrieve output from myfile.php, stop buffering
                echo json_encode(array('response' => array('data' => array('html' => $html))));
                break;

            case 'changeVillageName':
                $_POST['name'] = addslashes($_POST['name']);
                if ($_POST['name'] == '') return;
                $result = mysql_query("SELECT * FROM vdata WHERE `wref` = '" . $_POST['did'] . "'");
                $row = mysql_fetch_array($result);
                $_POST['name'] = str_replace('[=]', '', $_POST['name']);
                $_POST['name'] = str_replace('[|]', '', $_POST['name']);
                $q = "UPDATE vdata SET `name` = '" . $_POST['name'] . "' where `wref` = '" . $_POST['did'] . "'";
                mysql_query($q);
                echo json_encode(array('response' => array('data' => array('name' => $_POST['name'], 'bname' => $row['name']))));
                break;

            case 'paymentWizard':
                include("templates/Plus/price.php");
                $choosepack = 0;
                $advantages = 0;
                $earngold = 0;
                $plusSupport = 0;
                $choosepayment = 1;
                $userData = $database->getUser($session->username);
                $golds = $database->getUser($session->username, 0);
                $datetimep = $userData['plus'];
                $datetime1 = $userData['b1'];
                $datetime2 = $userData['b2'];
                $datetime3 = $userData['b3'];
                $datetime4 = $userData['b4'];
                $datetimeap = $userData['ap'];
                $datetimedp = $userData['dp'];
                $now = strtotime("NOW");
                if ((!isset($_POST['goldProductId']) || $_POST['goldProductId'] == '') && (!isset($_POST['goldProductLocation']) || $_POST['goldProductLocation'] == '') && (!isset($_POST['location']) || $_POST['location'] == '') && isset($_POST['activeTab']) && $_POST['activeTab'] == 'buyGold') {
                    $choosepack = 1;
                } elseif ((!isset($_POST['goldProductId']) || $_POST['goldProductId'] == '') && (!isset($_POST['goldProductLocation']) || $_POST['goldProductLocation'] == '') && (!isset($_POST['location']) || $_POST['location'] == '') && isset($_POST['activeTab']) && $_POST['activeTab'] == 'pros') {
                    $advantages = 1;
                } elseif ((!isset($_POST['goldProductId']) || $_POST['goldProductId'] == '') && (!isset($_POST['goldProductLocation']) || $_POST['goldProductLocation'] == '') && (!isset($_POST['location']) || $_POST['location'] == '') && isset($_POST['activeTab']) && $_POST['activeTab'] == 'earnGold') {
                    $earngold = 1;
                } elseif ((!isset($_POST['goldProductId']) || $_POST['goldProductId'] == '') && (!isset($_POST['goldProductLocation']) || $_POST['goldProductLocation'] == '') && (!isset($_POST['location']) || $_POST['location'] == '') && isset($_POST['activeTab']) && $_POST['activeTab'] == 'plusSupport') {
                    $plusSupport = 1;
                } elseif ((!isset($_POST['goldProductId']) || $_POST['goldProductId'] < count($AppConfig['plus']['packages'])) && (!isset($_POST['goldProductLocation']) || $_POST['goldProductLocation'] == '') && (!isset($_POST['location']) || $_POST['location'] == '') && isset($_POST['activeTab']) && $_POST['activeTab'] == 'buyGold') {
                    $choosepayment = 1;
                    $package = $_POST['goldProductId'];
                }
                include("templates/Ajax/payment.php");
                break;

            case 'premiumFeature':
                $fail = true;
                $success = false;
                $finishnowsuccess = false;
                $golds = $database->getUser($session->username, 0);
                include("templates/Plus/price.php");
                if (isset($_POST['context']) && $_POST['context'] == 'paymentWizard' || $_POST['context'] == 'production' || $_POST['context'] == 'productionBoost') {
                    if (isset($_POST['featureKey']) && $_POST['featureKey'] == 'goldclub') {
                        if ($session->gold >= 100 && $golds['goldclub'] == 0) {
                            mysql_query("UPDATE users set goldclub = 1, gold = gold - 100 where `username`='" . $session->username . "'");
                            $success = true;
                        } elseif ($golds['goldclub'] == 1) {
                            $success = true;
                        }
                    } elseif (isset($_POST['featureKey']) && $_POST['featureKey'] == 'plus') {
                        if ($session->gold >= 10) {
                            if ($golds['plus'] <= time()) {
                                mysql_query("UPDATE users set plus = '0' where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            if ($golds['plus'] == 0) {
                                mysql_query("UPDATE users set plus = " . time() . "+" . PLUS_TIME . " where `username`='" . $session->username . "'") or die(mysql_error());
                            } else {
                                mysql_query("UPDATE users set plus = plus + " . PLUS_TIME . " where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            mysql_query("UPDATE users set gold = gold-10, usedgold=usedgold+10 where `username`='" . $session->username . "'") or die(mysql_error());
                            $success = true;
                        }
                    } elseif (isset($_POST['featureKey']) && $_POST['featureKey'] == 'productionboostWood') {
                        if ($session->gold >= 5) {
                            /*
                            if($golds['b1'] <= time()) {
                                mysql_query("UPDATE users set b1 = '0' where `username`='".$session->username."'") or die(mysql_error());
                            }
                            */
                            if ($golds['b1'] < time()) {
                                mysql_query("UPDATE users set b1 = " . time() . "+" . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            } else {
                                mysql_query("UPDATE users set b1 = b1 + " . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            mysql_query("UPDATE users set gold = gold-5, usedgold=usedgold+5 where `username`='" . $session->username . "'") or die(mysql_error());
                            $success = true;
                        }
                    } elseif (isset($_POST['featureKey']) && $_POST['featureKey'] == 'productionboostClay') {
                        if ($session->gold >= 5) {
                            /*
                            if($golds['b2'] <= time()) {
                                mysql_query("UPDATE users set b2 = '0' where `username`='".$session->username."'") or die(mysql_error());
                            }
                            */
                            if ($golds['b2'] < time()) {
                                mysql_query("UPDATE users set b2 = " . time() . "+" . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            } else {
                                mysql_query("UPDATE users set b2 = b2 + " . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            mysql_query("UPDATE users set gold = gold-5, usedgold=usedgold+5 where `username`='" . $session->username . "'") or die(mysql_error());
                            $success = true;
                        }
                    } elseif (isset($_POST['featureKey']) && $_POST['featureKey'] == 'productionboostIron') {
                        if ($session->gold >= 5) {
                            /*
                            if($golds['b3'] <= time()) {
                                mysql_query("UPDATE users set b3 = '0' where `username`='".$session->username."'") or die(mysql_error());
                            }
                            */
                            if ($golds['b3'] < time()) {
                                mysql_query("UPDATE users set b3 = " . time() . "+" . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            } else {
                                mysql_query("UPDATE users set b3 = b3 + " . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            mysql_query("UPDATE users set gold = gold-5,usedgold=usedgold+5 where `username`='" . $session->username . "'") or die(mysql_error());
                            $success = true;
                        }
                    } elseif (isset($_POST['featureKey']) && $_POST['featureKey'] == 'productionboostCrop') {
                        if ($session->gold >= 5) {
                            /*
                            if($golds['b4'] <= time()) {
                                mysql_query("UPDATE users set b4 = '0' where `username`='".$session->username."'") or die(mysql_error());
                            }
                            */
                            if ($golds['b4'] < time()) {
                                mysql_query("UPDATE users set b4 = " . time() . "+" . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            } else {
                                mysql_query("UPDATE users set b4 = b4 + " . PLUS_PRODUCTION . " where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            mysql_query("UPDATE users set gold = gold-5,usedgold=usedgold+5 where `username`='" . $session->username . "'") or die(mysql_error());
                            $success = true;
                        }
                    }
                } elseif (isset($_POST['context']) && $_POST['context'] == 'infobox') {
                    if (isset($_POST['featureKey']) && $_POST['featureKey'] == 'Plus') {
                        if ($session->gold >= 10) {
                            if ($golds['plus'] <= time()) {
                                mysql_query("UPDATE users set plus = '0' where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            if ($golds['plus'] == 0) {
                                mysql_query("UPDATE users set plus = " . time() . "+" . PLUS_TIME . " where `username`='" . $session->username . "'") or die(mysql_error());
                            } else {
                                mysql_query("UPDATE users set plus = plus + " . PLUS_TIME . " where `username`='" . $session->username . "'") or die(mysql_error());
                            }
                            mysql_query("UPDATE users set gold = gold-10, usedgold=usedgold+10 where `username`='" . $session->username . "'") or die(mysql_error());
                            $success = true;
                        }
                    }
                } elseif (isset($_POST['featureKey']) && $_POST['featureKey'] == 'finishNow') {
                    if ($session->gold >= 2) {
                        foreach ($building->buildArray as $jobs) {
                            $level = $database->getFieldLevel($jobs['wid'], $jobs['field']);
                            $level = ($level == -1) ? 0 : $level;
                            if ($jobs['type'] != 25 and $jobs['type'] != 26 and $jobs['type'] != 40) {
                                $resource = $building->resourceRequired($jobs['field'], $jobs['type']);
                                $q = "UPDATE fdata set f" . $jobs['field'] . " = f" . $jobs['field'] . " + 1, f" . $jobs['field'] . "t = " . $jobs['type'] . " where vref = " . $jobs['wid'];
                                if ($database->query($q)) {

                                    $database->modifyPop($jobs['wid'], $resource['pop'], 0);
                                    $database->addCP($jobs['wid'], $resource['cp']);
                                    $database->finishDemolition($village->wid);
                                    $database->addCLP($session->uid, 7);

                                    $q = "DELETE FROM bdata where id = " . $jobs['id'];
                                    $database->query($q);
                                    if ($jobs['type'] == 18) {
                                        $owner = $database->getVillageField($jobs['wid'], "owner");
                                        $max = $bid18[$level]['attri'];
                                        $q = "UPDATE alidata set max = $max where leader = $owner";
                                        $database->query($q);
                                    }
                                    if ($jobs['type'] == 10) {
                                        $max = $database->getVillageField($jobs['wid'], "maxstore");
                                        if ($level == '0' && $building->getTypeLevel(10) != 20) {
                                            $max -= STORAGE_BASE;
                                        }
                                        $max -= $bid10[$level]['attri'] * STORAGE_MULTIPLIER;
                                        $max += $bid10[$level + 1]['attri'] * STORAGE_MULTIPLIER;
                                        $database->setVillageField($jobs['wid'], "maxstore", $max);
                                    }

                                    if ($jobs['type'] == 11) {
                                        $max = $database->getVillageField($jobs['wid'], "maxcrop");
                                        if ($level == '0' && $building->getTypeLevel(11) != 20) {
                                            $max -= STORAGE_BASE;
                                        }
                                        $max -= $bid11[$level]['attri'] * STORAGE_MULTIPLIER;
                                        $max += $bid11[$level + 1]['attri'] * STORAGE_MULTIPLIER;
                                        $database->setVillageField($jobs['wid'], "maxcrop", $max);
                                    }
                                    if ($jobs['type'] == 38) {
                                        $max = $database->getVillageField($jobs['wid'], "maxstore");
                                        if ($level == '0' && $building->getTypeLevel(38) != 20) {
                                            $max -= STORAGE_BASE;
                                        }
                                        $max -= $bid38[$level]['attri'] * STORAGE_MULTIPLIER;
                                        $max += $bid38[$level + 1]['attri'] * STORAGE_MULTIPLIER;
                                        $database->setVillageField($jobs['wid'], "maxstore", $max);
                                    }

                                    if ($jobs['type'] == 39) {
                                        $max = $database->getVillageField($jobs['wid'], "maxcrop");
                                        if ($level == '0' && $building->getTypeLevel(39) != 20) {
                                            $max -= STORAGE_BASE;
                                        }
                                        $max -= $bid39[$level]['attri'] * STORAGE_MULTIPLIER;
                                        $max += $bid39[$level + 1]['attri'] * STORAGE_MULTIPLIER;
                                        $database->setVillageField($jobs['wid'], "maxcrop", $max);
                                    }
                                }
                            }
                        }
                        $technology->finishTech();
                        $logging->goldFinLog($village->wid);
                        $database->modifyGold($session->uid, 2, 0);
                        $success = true;
                        $finishnowsuccess = true;
                    }
                }
                include("templates/Ajax/permium.php");
                break;
            case 'quest':
                $quest = "";
                $action = "";
                if (isset($_POST['questTutorialId'])) {
                    $quest = $_POST['questTutorialId'];
                    if (isset($_POST['action'])) {
                        $action = $_POST['action'];
                    }
                    include("templates/Ajax/quest_core.php");
                }
                break;
            case 'heroEditor':
                $herodetail = $database->getHeroFace($session->uid);
                $getcolor = $herodetail['color'];
                if ($herodetail['gender'] == 0) {
                    $gstr = 'male';
                } else {
                    $gstr = 'female';
                }
                $gender = $herodetail['gender'];
                $geteye = $herodetail['eye'];
                if ($gender == 0) $geteye %= 5;
                $geteyebrow = $herodetail['eyebrow'];
                if ($gender == 0) $geteyebrow %= 5;
                $getnose = $herodetail['nose'];
                if ($gender == 0) $getnose %= 5;
                $getear = $herodetail['ear'];
                if ($gender == 0) $getear %= 5;
                $getmouth = $herodetail['mouth'];
                if ($gender == 0) $getmouth %= 4;
                $getbeard = $herodetail['beard'];
                if ($gender == 1) $getbeard = 5;
                $gethair = $herodetail['hair'];
                if ($gender == 0) $gethair %= 5;
                $getface = $herodetail['face'];
                if ($gender == 0) $getface %= 5;
                $head = $_POST['attribs']['headProfile'];
                $color = $_POST['attribs']['hairColor'];
                $hair = $_POST['attribs']['hairStyle'];
                $ear = $_POST['attribs']['ears'];
                $eyebrow = $_POST['attribs']['eyebrow'];
                $eye = $_POST['attribs']['eyes'];
                $nose = $_POST['attribs']['nose'];
                $mouth = $_POST['attribs']['mouth'];
                $beard = $_POST['attribs']['beard'];
                if ($beard == 5999) $beard = -1; // some fix
                if ($head != $getface) {
                    $atrface = $head;
                    if ($gender == 0) $atrface %= 5;
                } else {
                    $atrface = $getface;
                }
                if ($hair != $gethair) {
                    $atrhair = $hair;
                    if ($gender == 0) $atrhair %= 5;
                } else {
                    $atrhair = $gethair;
                }
                if ($ear != $getear) {
                    $atrear = $ear;
                    if ($gender == 0) $atrear %= 5;
                } else {
                    $atrear = $getear;
                }
                if ($eye != $geteye) {
                    $atreye = $eye;
                    if ($gender == 0) $atreye %= 5;
                } else {
                    $atreye = $geteye;
                }
                if ($mouth != $getmouth) {
                    $atrmouth = $mouth;
                    if ($gender == 0) $atrmouth %= 5;
                } else {
                    $atrmouth = $getmouth;
                }
                if ($beard != $getbeard) {
                    $atrbeard = $beard;
                    if ($gender == 0) $atrbeard %= 5;
                } else {
                    $atrbeard = $getbeard;
                }
                if ($nose != $getnose) {
                    $atrnose = $nose;
                    if ($gender == 0) $atrnose %= 5;
                } else {
                    $atrnose = $getnose;
                }
                if ($eyebrow != $geteyebrow) {
                    $atreyebrow = $eyebrow;
                    if ($gender == 0) $atreyebrow %= 5;
                } else {
                    $atreyebrow = $geteyebrow;
                }
                if ($color != $getcolor) {
                    $atrcolor = $color;
                } else {
                    $atrcolor = $getcolor;
                }
                if ($atrcolor == 0) {
                    $color = "black";
                }
                if ($atrcolor == 1) {
                    $color = "brown";
                }
                if ($atrcolor == 2) {
                    $color = "darkbrown";
                }
                if ($atrcolor == 3) {
                    $color = "yellow";
                }
                if ($atrcolor == 4) {
                    $color = "red";
                }
                include("templates/Ajax/heroeditor.php");
                break;
            case 'overlay':
                include("templates/Ajax/overlay.php");
                break;
                // soheil
            case 'quest':
                include("templates/Ajax/quest_core.php");
            case 'finishNowPopup':
                include("templates/Ajax/finishNowPopup.php");
                break;
            case 'mapLowRes':
                $x = $_POST['x'];
                $y = $_POST['y'];
                $xx = $_POST['width'];
                $yy = $_POST['height'];
                include('templates/Ajax/mapscroll.php');
                break;
            case 'mapSetting':
                echo '{
					response: {"error":false,"errorMsg":null,"data":{"result":false}}
				}';
                break;
            case 'mapFlagAdd':
                $inputs = $_POST['data'];
                $x = $inputs['x'];
                $y = $inputs['y'];
                $color = $inputs['color'];
                $owner = $inputs['owner'];
                $text = $inputs['text'];
                $uid = $session->uid;
                mysql_query("INSERT INTO map_marks (`id`,`uid`,`x`,`y`,`index`,`text`) VALUES ('','" . $uid . "','" . $x . "','" . $y . "','" . $color . "','" . $text . "')") or die(mysql_error());
                $row = mysql_insert_id();
                $q = "UPDATE map_marks SET `dataId`='" . $row . "' WHERE id=" . $row;
                mysql_query($q) or die(mysql_error());
                echo '{
					response: {"error":false,"errorMsg":null,"data":{"text":"' . $text . '","index":' . $color . ',"kid":1,"position":{"x":' . $x . ',"y":' . $y . '},"dataId":' . $row . '}}
				}';
                break;
            case 'mapMultiMarkAdd':
                $inputs = $_POST['data'];
                $x = $inputs['x'];
                $y = $inputs['y'];
                $color = $inputs['color'];
                $owner = $inputs['owner'];
                $text = $inputs['text'];
                $uid = $session->uid;
                $query = mysql_fetch_assoc(mysql_query("SELECT `id` FROM wdata WHERE x=" . $x . " AND y=" . $y . " LIMIT 1"));
                $query2 = mysql_fetch_assoc(mysql_query("SELECT `owner` FROM vdata WHERE wref =" . $query['id'] . " LIMIT 1"));
                $query3 = mysql_fetch_assoc(mysql_query("SELECT `username` FROM users WHERE id =" . $query2['owner'] . " LIMIT 1"));
                mysql_query("INSERT INTO map_marks (`id`,`uid`,`x`,`y`,`index`,`type`,`text`,`dataId`) VALUES ('','" . $uid . "','" . $x . "','" . $y . "','" . $color . "','player','" . $query3['username'] . "','" . $query2['owner'] . "')") or die(mysql_error());
                $row = mysql_insert_id();
                // $q = "UPDATE map_marks SET `dataId`='".$row."' WHERE id=".$row;
                // mysql_query($q)or die(mysql_error());
                echo '{
					response: {"error":false,"errorMsg":null,"data":{"owner":"player","color":' . $color . ',"text":"' . $query3['username'] . '","position":{"x":' . $x . ',"y":' . $y . '},"markId":' . $query2['owner'] . ',"dataId":' . $row . '}}
				}';
                break;
            case 'mapFlagOrMultiMark':
                $data = $_POST['data'];
                mysql_query("DELETE from map_marks where id=" . $data['dataId'] . "") or die(mysql_error());
                $str = '{
					response: {"error":false,"errorMsg":null,"data":{"result":true}}
				}';
                echo $str;
                break;
            case 'mapFlagUpdate':
                $data = $_POST['data'];
                $id = $data['dataId'];
                $q = "UPDATE map_marks SET `index`='" . $data['index'] . "',`text`='" . $data['text'] . "' WHERE id=" . $id;
                mysql_query($q) or die(mysql_error());
                $str = '{
					response: {"error":false,"errorMsg":null,"data":{"result":true}}
				}';
                echo $str;
                break;
            case 'mapInfo':
                $str = '{
					response: {"error":false,"errorMsg":null,"data":{"blocks":"' . $_POST['data'] . '"}}
				}';
                echo $str;
                break;
            case 'viewTileDetails':
                include('templates/Ajax/viewTileDetails.php');
                break;
            case 'mapPositionData':
                include('templates/Ajax/mapPositionData.php');
                break;
            default:
                echo '{
					response: {"error":true,"errorMsg":"Parameter "' . $_GET['cmd'] . '" (ajax.php) is not valid in "cmd".","data":[]","data":{"blocks":"' . $_POST['data'] . '"}}
				}';
                break;
        }
    } else {
        echo json_encode(array('error' => true, 'errorMsg' => 'Parameter "cmd" can not be empty or null.","data":[]'));
    }
}
