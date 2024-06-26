<?php

function zeroPopedVillages()
{
    global $database, $generator;
    if (!$database->checkConnection()) {
        throw new Exception(__FILE__ . ' cant connect to database.');
    }
    $q = "SELECT * FROM vdata WHERE pop <= 0 AND owner > 4 AND natar = 0 LIMIT 5";
    $array = $database->query_return($q);
    if (!empty($array)) {
        foreach ($array as $zp) {
            if ($zp['capital'] == 1) {
                //delete bdata, research, training, market, artefacts, demolition
                //$q = "DELETE FROM bdata WHERE wid=" . ($zp['wref']);
                //$database->query($q);
                //$q = "DELETE FROM research WHERE vref=" . ($zp['wref']);
                //$database->query($q);
                //$q = "DELETE FROM training WHERE vref=" . ($zp['wref']);
                //$database->query($q);
                //$q = "DELETE FROM market WHERE vref=" . ($zp['wref']);
                //$database->query($q);

                //-------------

                $q = "DELETE FROM demolition WHERE vref=" . ($zp['wref']);
                $database->query($q);
                //$ownerVillages = $database->getArrayMemberVillage($zp['owner']);
                // if(!empty($ownerVillages)){
                // if ($ownerVillages[0]['wref']!=$zp['wref']){
                // $bigestV = $ownerVillages[0];
                // } else if(!empty($ownerVillages[1])){
                // $bigestV = $ownerVillages[1];
                // }
                // if (!empty($bigestV) && $bigestV['capital']!=1){
                // $q = "UPDATE vdata SET capital=1 WHERE wref=".($bigestV['wref']); $database->query($q);
                // }
                // }
            } else {
                //delete fdata, abdata, bdata, research, tdata, training, vdata, market, artefacts, demolition, farm list and raidlist
                $q = "DELETE FROM demolition WHERE vref=" . ($zp['wref']);
                $database->query($q);
                $q = "DELETE FROM vdata WHERE wref=" . ($zp['wref']);
                $database->query($q);
                $q = "DELETE FROM bdata WHERE wid=" . ($zp['wref']);
                $database->query($q);
                $q = "DELETE FROM fdata WHERE vref=" . ($zp['wref']);
                $database->query($q);
                $q = "DELETE FROM abdata WHERE vref=" . ($zp['wref']);
                $database->query($q);
                $q = "DELETE FROM tdata WHERE vref=" . ($zp['wref']);
                $database->query($q);
                $q = "DELETE FROM training WHERE vref=" . ($zp['wref']);
                $database->query($q);
                //update wdata for capture release
                $q = "UPDATE wdata SET occupied=0 WHERE id=" . ($zp['wref']);
                $database->query($q);
                //release exp, and change expandedfrom
                $q = "SELECT * FROM vdata WHERE wref=" . ($zp['expandedfrom']);
                $expfrom = $database->query_return($q);
                if (!empty($expfrom)) {
                    for ($i = 1; $i <= 3; $i++) {
                        if ($expfrom[0]['exp' . $i] == $zp['wref']) {
                            $q = "UPDATE vdata SET exp" . $i . "=0 WHERE wref=" . ($expfrom[0]['wref']);
                            $database->query($q);
                        }
                    }
                }
                $q = "UPDATE vdata SET expandedfrom=0 WHERE expandedfrom=" . ($zp['wref']);
                $database->query($q);

                //send kill home troops, kill sent reinf
                //$q = "DELETE FROM units WHERE vref=" . ($zp['wref']);
                //$database->query($q);
                $q = "DELETE FROM enforcement WHERE `from`=" . ($zp['wref']);
                $database->query($q);

                $q = "SELECT * FROM farmlist WHERE wref=" . ($zp['wref']);
                $flist = $database->query_return($q);
                if (!empty($flist)) {
                    foreach ($flist as $fl) {
                        $q = "DELETE FROM raidlist WHERE lid=" . ($fl['id']);
                        $database->query($q);
                    }
                }

                $q = "DELETE FROM farmlist WHERE wref=" . ($zp['wref']);
                $database->query($q);

                //die hero set hero dead
                $zpHero = $database->getHero($zp['owner']);
                if ($zpHero['wref'] == $zp['wref']) {
                    $ownerVillages = $database->getArrayMemberVillage($zp['owner']);
                    if (!empty($ownerVillages)) {
                        if ($ownerVillages[0]['wref'] != $zp['wref']) {
                            $bigestV = $ownerVillages[0];
                        } else if (!empty($ownerVillages[1])) {
                            $bigestV = $ownerVillages[1];
                        }
                        if (!empty($bigestV)) {
                            $q = "UPDATE hero SET `health`=0,`dead`=1,`wref`=" . $bigestV['wref'] . " WHERE `uid`=" . ($zpHero['uid']);
                            $database->query($q);
                        }
                    }
                }

                //release oases odata/wdata
                $q = "SELECT * FROM odata WHERE conqured=" . ($zp['wref']);
                $olist = $database->query_return($q);
                if (!empty($olist)) {
                    foreach ($olist as $o) {
                        $q = "UPDATE wdata SET occupied=0 WHERE id=" . ($o['wref']);
                        $database->query($q);
                    }
                }
                $q = "UPDATE odata SET conqured=0,owner=3 WHERE conqured=" . ($zp['wref']);
                $database->query($q);

                $q = "SELECT `vref` FROM artefacts WHERE owner = 2 LIMIT 1";
                $get = $database->query($q);
                $get = mysql_fetch_assoc($get);

                $newvill = $get['vref'];

                $q = "UPDATE artefacts SET owner = 2,vref = $newvill, conquered = 0, lastupdate = 0 WHERE vref=" . ($zp['wref']);
                $database->query($q);
            }
            /*
                            //delete movement,attack
                            $q = "SELECT * FROM attacks WHERE vref=" . ($zp['wref']);
                            $attacks = $database->query_return($q);
                            if (!empty($attacks)) {
                                foreach ($attacks as $a) {
                                    $q = "DELETE FROM movement WHERE ref=" . ($a['id']);
                                    $database->query($q);
                                }
                            }
                            $q = "DELETE FROM attacks WHERE vref=" . ($zp['wref']);
                            $database->query($q);
            */
            //delete movement,send
            /*$q = "SELECT * FROM send, movement WHERE send.id = movement.ref AND movement.proc = 0 AND ((movement.from = " . $zp['wref'] . " AND sort_type = 0) OR (movement.to = " . $zp['wref'] . " AND sort_type = 1))";
            $sent = $database->query_return($q);
            if (!empty($sent)) {
                foreach ($sent as $s) {
                    $q = "DELETE FROM movement WHERE moveid=" . ($s['moveid']);
                    $database->query($q);
                    $q = "DELETE FROM send WHERE id=" . ($s['id']);
                    $database->query($q);
                }
            }*/

            //send back trapped
            $q = "SELECT * FROM trapped WHERE vref=" . ($zp['wref']);
            $trappeds = $database->query_return($q);
            if (!empty($trappeds)) {
                foreach ($trappeds as $trp) {
                    $isoasis = $database->isVillageOases($trp['from']);
                    if ($isoasis) {
                        $to = $database->getOMInfo($trp['from']);
                        if ($to['conqured']) {
                            $to['name'] = VL_OCCUPIEDOASIS;
                        } else {
                            $to['name'] = VL_UNOCCUPIEDOASIS;
                        }
                        $to['name'] .= ' (' . $to['x'] . '|' . $to['y'] . ')';
                    } else {
                        $to = $database->getMInfo($trp['from']);
                    }

                    $ownertribe = $database->getUserField($to['owner'], 'tribe', 0);
                    $tStarter = ($ownertribe - 1) * 10;
                    $toReturn = array();
                    for ($i = 1; $i <= 10; $i++) {
                        $toReturn[$i] = $trp['u' . ($tStarter + $i)];
                    }
                    if (!isset($to['x'])) $to['x'] = 0;
                    if (!isset($to['y'])) $to['y'] = 0;

                    $toReturn[11] = $trp['hero'];
                    $speeds = array();
                    for ($i = 1; $i <= 50; $i++) {
                        if ($trp['u' . $i] > 0) {
                            global ${'u' . $i};
                            $speeds[] = ${'u' . $i}['speed'];
                        }
                    }
                    if ($trp['hero'] > 0) {
                        $hero = $database->getHero($to['owner']);
                        $speeds[] = $hero['speed'] + $hero['itemspeed'];
                        $pdtHero = $hero;
                    } else {
                        $pdtHero = array();
                    }
                    $returntime = $generator->procDistanceTime($to, $to, min($speeds), 1, $pdtHero, TRUE) + time();
                    $attRef = $database->addAttack($trp['from'], $toReturn[1], $toReturn[2], $toReturn[3], $toReturn[4], $toReturn[5], $toReturn[6], $toReturn[7], $toReturn[8], $toReturn[9], $toReturn[10], $toReturn[11], 3, 0, 0, 0);
                    $database->addMovement(4, $zp['wref'], $to['wref'], $attRef, '', $returntime);
                }
            }

            $q = "DELETE FROM trapped WHERE vref=" . ($zp['wref']);
            $database->query($q);

            //send back reinf
            /*$q = "SELECT * FROM enforcement WHERE vref=" . ($zp['wref']);
            $enforces = $database->query_return($q);
            if (!empty($enforces)) {
                foreach ($enforces as $enf) {
                    $isoasis = $database->isVillageOases($enf['from']);
                    if ($isoasis) {
                        $to = $database->getOMInfo($enf['from']);
                        if ($to['conqured']) {
                            $to['name'] = VL_OCCUPIEDOASIS;
                        } else {
                            $to['name'] = VL_UNOCCUPIEDOASIS;
                        }
                        $to['name'] .= ' (' . $to['x'] . '|' . $to['y'] . ')';
                    } else {
                        $to = $database->getMInfo($enf['from']);
                    }
                    if (!isset($to['x'])) $to['x'] = 0;
                    if (!isset($to['y'])) $to['y'] = 0;
                    $ownertribe = $database->getUserField($to['owner'], 'tribe', 0);
                    $tStarter = ($ownertribe - 1) * 10;
                    $toReturn = array();
                    for ($i = 1; $i <= 10; $i++) {
                        $toReturn[$i] = $enf['u' . ($tStarter + $i)];
                    }

                    $toReturn[11] = $enf['hero'];
                    $speeds = array();
                    for ($i = 1; $i <= 50; $i++) {
                        if ($enf['u' . $i] > 0) {
                            global ${'u' . $i};
                            $speeds[] = ${'u' . $i}['speed'];
                        }
                    }
                    if ($enf['hero'] > 0) {
                        $hero = $database->getHero($to['owner']);
                        $speeds[] = $hero['speed'] + $hero['itemspeed'];
                        $pdtHero = $hero;
                    } else {
                        $pdtHero = array();
                    }
                    $returntime = $generator->procDistanceTime($to, $zp, min($speeds), 1, $pdtHero, TRUE) + time();
                    $attRef = $database->addAttack($enf['from'], $toReturn[1], $toReturn[2], $toReturn[3], $toReturn[4], $toReturn[5], $toReturn[6], $toReturn[7], $toReturn[8], $toReturn[9], $toReturn[10], $toReturn[11], 3, 0, 0, 0);
                    $database->addMovement(4, $zp['wref'], $to['wref'], $attRef, '', $returntime);
                }
            }
            $q = "DELETE FROM enforcement WHERE vref=" . ($zp['wref']);
            $database->query($q);
            */
            //$owner = $zp['owner'];
            //$pVs = $database->getProfileVillages($owner);
            //if (count($pVs) <= 1) {
            $database->setVillageField($zp['wref'], 'pop', '2');
            // continue;
            //}
        }
    }
}
