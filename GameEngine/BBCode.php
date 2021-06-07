<?php

$pattern = array();
$pattern[0] = '/\[b\](.*?)\[\/b\]/is';
$pattern[1] = '/\[i\](.*?)\[\/i\]/is';
$pattern[2] = '/\[u\](.*?)\[\/u\]/is';
$pattern[3] = '/\[tid1\]/';
$pattern[4] = '/\[tid2\]/';
$pattern[5] = '/\[tid3\]/';
$pattern[6] = '/\[tid4\]/';
$pattern[7] = '/\[tid5\]/';
$pattern[8] = '/\[tid6\]/';
$pattern[9] = '/\[tid7\]/';
$pattern[10] = '/\[tid8\]/';
$pattern[11] = '/\[tid9\]/';
$pattern[12] = '/\[tid10\]/';
$pattern[13] = '/\[tid11\]/';
$pattern[14] = '/\[tid12\]/';
$pattern[15] = '/\[tid13\]/';
$pattern[16] = '/\[tid14\]/';
$pattern[17] = '/\[tid15\]/';
$pattern[18] = '/\[tid16\]/';
$pattern[19] = '/\[tid17\]/';
$pattern[20] = '/\[tid18\]/';
$pattern[21] = '/\[tid19\]/';
$pattern[22] = '/\[tid20\]/';
$pattern[23] = '/\[tid21\]/';
$pattern[24] = '/\[tid22\]/';
$pattern[25] = '/\[tid23\]/';
$pattern[26] = '/\[tid24\]/';
$pattern[27] = '/\[tid25\]/';
$pattern[28] = '/\[tid26\]/';
$pattern[29] = '/\[tid27\]/';
$pattern[30] = '/\[tid28\]/';
$pattern[31] = '/\[tid29\]/';
$pattern[32] = '/\[tid30\]/';
$pattern[33] = '/\[tid31\]/';
$pattern[34] = '/\[tid32\]/';
$pattern[35] = '/\[tid33\]/';
$pattern[36] = '/\[tid34\]/';
$pattern[37] = '/\[tid35\]/';
$pattern[38] = '/\[tid36\]/';
$pattern[39] = '/\[tid37\]/';
$pattern[40] = '/\[tid38\]/';
$pattern[41] = '/\[tid39\]/';
$pattern[42] = '/\[tid40\]/';
$pattern[43] = '/\[tid41\]/';
$pattern[44] = '/\[tid42\]/';
$pattern[45] = '/\[tid43\]/';
$pattern[46] = '/\[tid44\]/';
$pattern[47] = '/\[tid45\]/';
$pattern[48] = '/\[tid46\]/';
$pattern[49] = '/\[tid47\]/';
$pattern[50] = '/\[tid48\]/';
$pattern[51] = '/\[tid49\]/';
$pattern[52] = '/\[tid50\]/';
$pattern[53] = '/\[hero\]/';
$pattern[54] = '/\[چوب\]/';
$pattern[55] = '/\[خشت\]/';
$pattern[56] = '/\[آهن\]/';
$pattern[57] = '/\[گندم\]/';
$pattern[58] = '/\*aha\*/';
$pattern[59] = '/\*angry\*/';
$pattern[60] = '/\*cool\*/';
$pattern[61] = '/\*cry\*/';
$pattern[62] = '/\*cute\*/';
$pattern[63] = '/\*depressed\*/';
$pattern[64] = '/\*eek\*/';
$pattern[65] = '/\*ehem\*/';
$pattern[66] = '/\*emotional\*/';
$pattern[67] = '/\:D/';
$pattern[68] = '/\:\)/';
$pattern[69] = '/\*hit\*/';
$pattern[70] = '/\*hmm\*/';
$pattern[71] = '/\*hmpf\*/';
$pattern[72] = '/\*hrhr\*/';
$pattern[73] = '/\*huh\*/';
$pattern[74] = '/\*lazy\*/';
$pattern[75] = '/\*love\*/';
$pattern[76] = '/\*nocomment\*/';
$pattern[77] = '/\*noemotion\*/';
$pattern[78] = '/\*notamused\*/';
$pattern[79] = '/\*pout\*/';
$pattern[80] = '/\*redface\*/';
$pattern[81] = '/\*rolleyes\*/';
$pattern[82] = '/\:\(/';
$pattern[83] = '/\*shy\*/';
$pattern[84] = '/\*smile\*/';
$pattern[85] = '/\*tongue\*/';
$pattern[86] = '/\*veryangry\*/';
$pattern[87] = '/\*veryhappy\*/';
$pattern[88] = '/\;\)/';

$replace = array();
$replace[0] = "<b>$1</b>";
$replace[1] = "<i>$1</i>";
$replace[2] = "<u>$1</u>";
$replace[3] = "<img class='unit u1' src='/assets/images/x.gif' title='" . U1 . "' alt='" . U1 . "'>";
$replace[4] = "<img class='unit u2' src='/assets/images/x.gif' title='" . U2 . "' alt='" . U2 . "'>";
$replace[5] = "<img class='unit u3' src='/assets/images/x.gif' title='" . U3 . "' alt='" . U3 . "'>";
$replace[6] = "<img class='unit u4' src='/assets/images/x.gif' title='" . U4 . "' alt='" . U4 . "'>";
$replace[7] = "<img class='unit u5' src='/assets/images/x.gif' title='" . U5 . "' alt='" . U5 . "'>";
$replace[8] = "<img class='unit u6' src='/assets/images/x.gif' title='" . U6 . "' alt='" . U6 . "'>";
$replace[9] = "<img class='unit u7' src='/assets/images/x.gif' title='" . U7 . "' alt='" . U7 . "'>";
$replace[10] = "<img class='unit u8' src='/assets/images/x.gif' title='" . U8 . "' alt='" . U8 . "'>";
$replace[11] = "<img class='unit u9' src='/assets/images/x.gif' title='" . U9 . "' alt='" . U9 . "'>";
$replace[12] = "<img class='unit u10' src='/assets/images/x.gif' title='" . U10 . "' alt='" . U10 . "'>";
$replace[13] = "<img class='unit u11' src='/assets/images/x.gif' title='" . U11 . "' alt='" . U11 . "'>";
$replace[14] = "<img class='unit u12' src='/assets/images/x.gif' title='" . U12 . "' alt='" . U12 . "'>";
$replace[15] = "<img class='unit u13' src='/assets/images/x.gif' title='" . U13 . "' alt='" . U13 . "'>";
$replace[16] = "<img class='unit u14' src='/assets/images/x.gif' title='" . U14 . "' alt='" . U14 . "'>";
$replace[17] = "<img class='unit u15' src='/assets/images/x.gif' title='" . U15 . "' alt='" . U15 . "'>";
$replace[18] = "<img class='unit u16' src='/assets/images/x.gif' title='" . U16 . "' alt='" . U16 . "'>";
$replace[19] = "<img class='unit u17' src='/assets/images/x.gif' title='" . U17 . "' alt='" . U17 . "'>";
$replace[20] = "<img class='unit u18' src='/assets/images/x.gif' title='" . U18 . "' alt='" . U18 . "'>";
$replace[21] = "<img class='unit u19' src='/assets/images/x.gif' title='" . U19 . "' alt='" . U19 . "'>";
$replace[22] = "<img class='unit u20' src='/assets/images/x.gif' title='" . U20 . "' alt='" . U20 . "'>";
$replace[23] = "<img class='unit u21' src='/assets/images/x.gif' title='" . U21 . "' alt='" . U21 . "'>";
$replace[24] = "<img class='unit u22' src='/assets/images/x.gif' title='" . U22 . "' alt='" . U22 . "'>";
$replace[25] = "<img class='unit u23' src='/assets/images/x.gif' title='" . U23 . "' alt='" . U23 . "'>";
$replace[26] = "<img class='unit u24' src='/assets/images/x.gif' title='" . U24 . "' alt='" . U24 . "'>";
$replace[27] = "<img class='unit u25' src='/assets/images/x.gif' title='" . U25 . "' alt='" . U25 . "'>";
$replace[28] = "<img class='unit u26' src='/assets/images/x.gif' title='" . U26 . "' alt='" . U26 . "'>";
$replace[29] = "<img class='unit u27' src='/assets/images/x.gif' title='" . U27 . "' alt='" . U27 . "'>";
$replace[30] = "<img class='unit u28' src='/assets/images/x.gif' title='" . U28 . "' alt='" . U28 . "'>";
$replace[31] = "<img class='unit u29' src='/assets/images/x.gif' title='" . U29 . "' alt='" . U29 . "'>";
$replace[32] = "<img class='unit u30' src='/assets/images/x.gif' title='" . U30 . "' alt='" . U30 . "'>";
$replace[33] = "<img class='unit u31' src='/assets/images/x.gif' title='" . U31 . "' alt='" . U31 . "'>";
$replace[34] = "<img class='unit u32' src='/assets/images/x.gif' title='" . U32 . "' alt='" . U32 . "'>";
$replace[35] = "<img class='unit u33' src='/assets/images/x.gif' title='" . U33 . "' alt='" . U33 . "'>";
$replace[36] = "<img class='unit u34' src='/assets/images/x.gif' title='" . U34 . "' alt='" . U34 . "'>";
$replace[37] = "<img class='unit u35' src='/assets/images/x.gif' title='" . U35 . "' alt='" . U35 . "'>";
$replace[38] = "<img class='unit u36' src='/assets/images/x.gif' title='" . U36 . "' alt='" . U36 . "'>";
$replace[39] = "<img class='unit u37' src='/assets/images/x.gif' title='" . U37 . "' alt='" . U37 . "'>";
$replace[40] = "<img class='unit u38' src='/assets/images/x.gif' title='" . U38 . "' alt='" . U38 . "'>";
$replace[41] = "<img class='unit u39' src='/assets/images/x.gif' title='" . U39 . "' alt='" . U39 . "'>";
$replace[42] = "<img class='unit u40' src='/assets/images/x.gif' title='" . U40 . "' alt='" . U40 . "'>";
$replace[43] = "<img class='unit u41' src='/assets/images/x.gif' title='" . U41 . "' alt='" . U41 . "'>";
$replace[44] = "<img class='unit u42' src='/assets/images/x.gif' title='" . U42 . "' alt='" . U42 . "'>";
$replace[45] = "<img class='unit u43' src='/assets/images/x.gif' title='" . U43 . "' alt='" . U43 . "'>";
$replace[46] = "<img class='unit u44' src='/assets/images/x.gif' title='" . U44 . "' alt='" . U44 . "'>";
$replace[47] = "<img class='unit u45' src='/assets/images/x.gif' title='" . U45 . "' alt='" . U45 . "'>";
$replace[48] = "<img class='unit u46' src='/assets/images/x.gif' title='" . U46 . "' alt='" . U46 . "'>";
$replace[49] = "<img class='unit u47' src='/assets/images/x.gif' title='" . U47 . "' alt='" . U47 . "'>";
$replace[50] = "<img class='unit u48' src='/assets/images/x.gif' title='" . U48 . "' alt='" . U48 . "'>";
$replace[51] = "<img class='unit u49' src='/assets/images/x.gif' title='" . U49 . "' alt='" . U49 . "'>";
$replace[52] = "<img class='unit u50' src='/assets/images/x.gif' title='" . U50 . "' alt='" . U50 . "'>";
$replace[53] = "<img class='unit uhero' src='/assets/images/x.gif' title='" . U0 . "' alt='" . U0 . "'>";
$replace[54] = "<img src='/assets/images/x.gif' class='r1' title='" . VL_LUMBER . "' alt='" . VL_LUMBER . "'>";
$replace[55] = "<img src='/assets/images/x.gif' class='r2' title='" . VL_CLAY . "' alt='" . VL_CLAY . "'>";
$replace[56] = "<img src='/assets/images/x.gif' class='r3' title='" . VL_IRON . "' alt='" . VL_IRON . "'>";
$replace[57] = "<img src='/assets/images/x.gif' class='r4' title='" . VL_CROP . "' alt='" . VL_CROP . "'>";
$replace[58] = "<img class='smiley aha' src='/assets/images/x.gif' alt='*aha*' title='*aha*'>";
$replace[59] = "<img class='smiley angry' src='/assets/images/x.gif' alt='*angry*' title='*angry*'>";
$replace[60] = "<img class='smiley cool' src='/assets/images/x.gif' alt='*cool*' title='*cool*'>";
$replace[61] = "<img class='smiley cry' src='/assets/images/x.gif' alt='*cry*' title='*cry*'>";
$replace[62] = "<img class='smiley cute' src='/assets/images/x.gif' alt='*cute*' title='*cute*'>";
$replace[63] = "<img class='smiley depressed' src='/assets/images/x.gif' alt='*depressed*' title='*depressed*'>";
$replace[64] = "<img class='smiley eek' src='/assets/images/x.gif' alt='*eek*' title='*eek*'>";
$replace[65] = "<img class='smiley ehem' src='/assets/images/x.gif' alt='*ehem*' title='*ehem*'>";
$replace[66] = "<img class='smiley emotional' src='/assets/images/x.gif' alt='*emotional*' title='*emotional*'>";
$replace[67] = "<img class='smiley grin' src='/assets/images/x.gif' alt=':D' title=':D'>";
$replace[68] = "<img class='smiley happy' src='/assets/images/x.gif' alt=':)' title=':)'>";
$replace[69] = "<img class='smiley hit' src='/assets/images/x.gif' alt='*hit*' title='*hit*'>";
$replace[70] = "<img class='smiley hmm' src='/assets/images/x.gif' alt='*hmm*' title='*hmm*'>";
$replace[71] = "<img class='smiley hmpf' src='/assets/images/x.gif' alt='*hmpf*' title='*hmpf*'>";
$replace[72] = "<img class='smiley hrhr' src='/assets/images/x.gif' alt='*hrhr*' title='*hrhr*'>";
$replace[73] = "<img class='smiley huh' src='/assets/images/x.gif' alt='*huh*' title='*huh*'>";
$replace[74] = "<img class='smiley lazy' src='/assets/images/x.gif' alt='*lazy*' title='*lazy*'>";
$replace[75] = "<img class='smiley love' src='/assets/images/x.gif' alt='*love*' title='*love*'>";
$replace[76] = "<img class='smiley nocomment' src='/assets/images/x.gif' alt='*nocomment*' title='*nocomment*'>";
$replace[77] = "<img class='smiley noemotion' src='/assets/images/x.gif' alt='*noemotion*' title='*noemotion*'>";
$replace[78] = "<img class='smiley notamused' src='/assets/images/x.gif' alt='*notamused*' title='*notamused*'>";
$replace[79] = "<img class='smiley pout' src='/assets/images/x.gif' alt='*pout*' title='*pout*'>";
$replace[80] = "<img class='smiley redface' src='/assets/images/x.gif' alt='*redface*' title='*redface*'>";
$replace[81] = "<img class='smiley rolleyes' src='/assets/images/x.gif' alt='*rolleyes*' title='*rolleyes*'>";
$replace[82] = "<img class='smiley sad' src='/assets/images/x.gif' alt=':(' title=':('>";
$replace[83] = "<img class='smiley shy' src='/assets/images/x.gif' alt='*shy*' title='*shy*'>";
$replace[84] = "<img class='smiley smile' src='/assets/images/x.gif' alt='*smile*' title='*smile*'>";
$replace[85] = "<img class='smiley tongue' src='/assets/images/x.gif' alt='*tongue*' title='*tongue*'>";
$replace[86] = "<img class='smiley veryangry' src='/assets/images/x.gif' alt='*veryangry*' title='*veryangry*'>";
$replace[87] = "<img class='smiley veryhappy' src='/assets/images/x.gif' alt='*veryhappy*' title='*veryhappy*'>";
$replace[88] = "<img class='smiley wink' src='/assets/images/x.gif' alt=';)' title=';)'>";

for ($i = 0; $i <= $alliance; $i++) {
    $pattern[89 + $i] = "/\[alliance" . $i . "\](.*?)\[\/alliance" . $i . "\]/is";
    ${'bbcoded1_' . $i} = preg_replace($pattern[89 + $i], "$1", $input);
    ${'bbcoded1_' . $i} = preg_replace('/\[\/alliance' . $i . '\](.*?)\[\/message\]/is', '', $input);
    ${'bbcoded1_' . $i} = preg_replace('/\[message\](.*?)\[alliance' . $i . '\]/is', '', ${'bbcoded1_' . $i});
    $aname = $database->getAllianceID(${'bbcoded1_' . $i});
    if ($aname != "") {
        $replace[89 + $i] = "<a href=allianz.php?aid=$aname>$1</a>";
    } else {
        $replace[89 + $i] = "alliance not exist";
    }
    $rep1 = 90 + $i;
}


for ($i = 0; $i <= $player; $i++) {
    $pattern[$rep1 + $i] = "/\[player" . $i . "\](.*?)\[\/player" . $i . "\]/is";

    ${'bbcoded2_' . $i} = preg_replace($pattern[$rep1 + $i], "$1", $input);
    ${'bbcoded2_' . $i} = preg_replace('/\[\/player' . $i . '\](.*?)\[\/message\]/is', '', $input);
    ${'bbcoded2_' . $i} = preg_replace('/\[message\](.*?)\[player' . $i . '\]/is', '', ${'bbcoded2_' . $i});


    $uname = $database->getUserField(${'bbcoded2_' . $i}, "id", 1);
    if ($uname != "") {
        $replace[$rep1 + $i] = "<a href=spieler.php?uid=$uname>$1</a>";
    } else {
        $replace[$rep1 + $i] = "player not exist";
    }
    $rep2 = $rep1 + $i + 1;
}
for ($i = 0; $i <= $report; $i++) {
    $pattern[$rep2 + $i] = "/\[report" . $i . "\](.*?)\[\/report" . $i . "\]/is";
    ${'bbcoded3_' . $i} = preg_replace($pattern[$rep2 + $i], "$1", $input);
    ${'bbcoded3_' . $i} = preg_replace('/\[\/report' . $i . '\](.*?)\[\/message\]/is', '', $input);
    ${'bbcoded3_' . $i} = preg_replace('/\[message\](.*?)\[report' . $i . '\]/is', '', ${'bbcoded3_' . $i});
    $report = count($database->getNotice2(`id`, ${'bbcoded3_' . $i}));
    if ($report > 0) {
        $topic = $database->getNotice2(${'bbcoded3_' . $i}, "topic");
        $ally = $database->getNotice2(${'bbcoded3_' . $i}, "ally");
        $replace[$rep2 + $i] = "<a href=berichte.php?id=$1&aid=$ally>$topic</a>";
    } else {
        $replace[$rep2 + $i] = "report not exist";
    }
    $rep3 = $rep2 + $i + 1;
}
for ($i = 0; $i <= $coor; $i++) {
    $pattern[$rep3 + $i] = "/\[coor" . $i . "\](.*?)\[\/coor" . $i . "\]/is";
    ${'bbcoded4_' . $i} = preg_replace($pattern[$rep3 + $i], "$1", $input);
    ${'bbcoded4_' . $i} = preg_replace('/\[\/coor' . $i . '\](.*?)\[\/message\]/is', '', $input);
    ${'bbcoded4_' . $i} = preg_replace('/\[message\](.*?)\[coor' . $i . '\]/is', '', ${'bbcoded4_' . $i});
    for ($x = 0; $x < 401; $x++) {
        if (preg_match('/^' . $x . '/', ${'bbcoded4_' . $i})) {
            $xx = 1;
            $cx = $x;
        }
    }
    for ($x = 0; $x > -401; $x--) {
        if (preg_match('/^' . $x . '/', ${'bbcoded4_' . $i})) {
            $xx = 1;
            $cx = $x;
        }
    }
    for ($y = 0; $y < 401; $y++) {
        if (preg_match('/-' . $y . '$/', ${'bbcoded4_' . $i})) {
            $yy = 1;
            $cy = $y * (-1);
        } else if (preg_match('/' . $y . '$/', ${'bbcoded4_' . $i})) {
            $yy = 1;
            $cy = $y;
        }
    }
    if (preg_match('/|/', ${'bbcoded4_' . $i}) && $xx == 1 && $yy == 1) {
        $wref = $database->getVilWref($cx, $cy);
        $cwref = $generator->getMapCheck($wref);
        if ($wref != "") {
            $wref1 = $database->getVillageType3($wref);
            if ($wref1['oasistype'] == 0 && $wref1['occupied'] == 1) {
                $vname = $database->getVillageField($wref, "name");
            } else if ($wref1['oasistype'] == 0 && $wref1['occupied'] == 0) {
                $vname = "Abandoned valley";
            } else if ($wref1['oasistype'] != 0 && $wref1['occupied'] == 1) {
                $vname = "Occupied Oasis";
            } else if ($wref1['oasistype'] != 0 && $wref1['occupied'] == 0) {
                $vname = "Unoccupied Oasis";
            }
            $replace[$rep3 + $i] = "<a href=karte.php?d=$wref&c=$cwref>$vname($cx|$cy)</a>";
        }
    }
}
$input = preg_replace('/\[message\]/', '', $input);
$input = preg_replace('/\[\/message\]/', '', $input);
$bbcoded = preg_replace($pattern, $replace, $input);
?>