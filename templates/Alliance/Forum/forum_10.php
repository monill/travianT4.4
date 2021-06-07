<?php
if ($session->access != BANNED) {
    $topic_id = $_GET['pod'];
    $post_id = $_GET['pod'];
    $topics = $database->ShowTopic($topic_id);
    $posts = $database->ShowPostEdit($post_id);
    foreach ($topics as $top) {
        $title = stripslashes($top['title']);
    }
    foreach ($posts as $pos) {
        $poss = stripslashes($pos['post']);
        $poss = preg_replace('/\[message\]/', '', $poss);
        $poss = preg_replace('/\[\/message\]/', '', $poss);
        $alliance0 = $pos['alliance0'];
        $player0 = $pos['player0'];
        $coor0 = $pos['coor0'];
        $report0 = $pos['report0'];
    }
?>
    <form method="post" name="post" action="allianz.php?s=2&pid=<?php echo $_GET['pid']; ?>&tid=<?php echo $_GET['idt']; ?>">
        <input type="hidden" name="s" value="2">
        <input type="hidden" name="pod" value="<?php echo $_GET['pod']; ?>">
        <input type="hidden" name="alliance0" value="<?php echo $alliance0; ?>">
        <input type="hidden" name="player0" value="<?php echo $player0; ?>">
        <input type="hidden" name="coor0" value="<?php echo $coor0; ?>">
        <input type="hidden" name="report0" value="<?php echo $report0; ?>">
        <input type="hidden" name="editpost" value="1">
        <h4 class="round"><?php echo AL_EDITANSWER; ?></h4>
        <table class="transparent" id="new_post">
            <tbody>
                <tr>
                    <th><?php echo AL_THREAD; ?>:</th>
                    <td><?php echo $title; ?></td>
                </tr>
                <tr>
                    <td colspan="2">

                        <div id="text_container" class="bbEditor">
                            <div class="boxes boxesColor gray">
                                <div class="boxes-tl"></div>
                                <div class="boxes-tr"></div>
                                <div class="boxes-tc"></div>
                                <div class="boxes-ml"></div>
                                <div class="boxes-mr"></div>
                                <div class="boxes-mc"></div>
                                <div class="boxes-bl"></div>
                                <div class="boxes-br"></div>
                                <div class="boxes-bc"></div>
                                <div class="boxes-contents">
                                    <div id="text_toolbar" class="bbToolbar">
                                        <button type="button" value="bbBold" class="icon bbButton bbBold bbType{d} bbTag{b}" title="Bold"><img src="/assets/images/x.gif" class="bbBold" alt="bbBold" title="Bold"></button>
                                        <button type="button" value="bbItalic" class="icon bbButton bbItalic bbType{d} bbTag{i}" title="Italic"><img src="/assets/images/x.gif" class="bbItalic" alt="bbItalic" title="Italic"></button>
                                        <button type="button" value="bbUnderscore" class="icon bbButton bbUnderscore bbType{d} bbTag{u}" title="Underline">
                                            <img src="/assets/images/x.gif" class="bbUnderscore" alt="bbUnderscore" title="Underline"></button>
                                        <button type="button" value="bbAlliance" class="icon bbButton bbAlliance bbType{d} bbTag{alliance0}" title="Alliance"><img src="/assets/images/x.gif" class="bbAlliance" alt="bbAlliance" title="Alliance"></button>
                                        <button type="button" value="bbPlayer" class="icon bbButton bbPlayer bbType{d} bbTag{player0}" title="Player</li>"><img src="/assets/images/x.gif" class="bbPlayer" alt="bbPlayer" title="Player</li>"></button>
                                        <button type="button" value="bbCoordinate" class="icon bbButton bbCoordinate bbType{d} bbTag{coor0}" title="Coordinates"><img src="/assets/images/x.gif" class="bbCoordinate" alt="bbCoordinate" title="Coordinates">
                                        </button>
                                        <button type="button" value="bbReport" class="icon bbButton bbReport bbType{d} bbTag{report0}"><img src="/assets/images/x.gif" class="bbReport" alt="bbReport" title="Report"></button>
                                        <button type="button" value="bbResource" id="text_resourceButton" class="bbWin{resources} bbButton bbResource icon" title="Resources"><img src="/assets/images/x.gif" class="bbResource" alt="bbResource"></button>
                                        <button type="button" value="bbSmilies" id="text_smilieButton" class="bbWin{smilies} bbButton bbSmilies icon" title="Smilies"><img src="/assets/images/x.gif" class="bbSmilies" alt="bbSmilies"></button>
                                        <button type="button" value="bbTroops" id="text_troopButton" class="bbWin{troops} bbButton bbTroops icon" title="Troops"><img src="/assets/images/x.gif" class="bbTroops" alt="bbTroops"></button>
                                        <button type="button" value="bbPreview" id="text_previewButton" class="icon bbButton bbPreview" title="Preview"><img src="/assets/images/x.gif" class="bbPreview" alt="bbPreview">
                                        </button>
                                        <div class="clear"></div>
                                        <div id="text_toolbarWindows" class="bbToolbarWindow">
                                            <div id="text_resources"><a href="#" class="bbType{o} bbTag{lumber}"><img src="/assets/images/x.gif" class="r1" alt="Wood" title="Wood"></a><a href="#" class="bbType{o} bbTag{clay}"><img src="/assets/images/x.gif" class="r2" alt="clay" title="clay"></a><a href="#" class="bbType{o} bbTag{Crop}"><img src="/assets/images/x.gif" class="r4" alt="Crop" title="Crop"></a><a href="#" class="bbType{o} bbTag{Iron}"><img src="/assets/images/x.gif" class="r3" alt="Iron" title="Iron"></a></div>
                                            <div id="text_smilies"><a href="#" class="bbType{s} bbTag{*aha*}"><img class="smiley aha" src="/assets/images/x.gif" alt="*aha*"></a><a href="#" class="bbType{s} bbTag{*angry*}"><img class="smiley angry" src="/assets/images/x.gif" alt="*angry*"></a><a href="#" class="bbType{s} bbTag{*cool*}"><img class="smiley cool" src="/assets/images/x.gif" alt="*cool*"></a><a href="#" class="bbType{s} bbTag{*cry*}"><img class="smiley cry" src="/assets/images/x.gif" alt="*cry*"></a><a href="#" class="bbType{s} bbTag{*cute*}"><img class="smiley cute" src="/assets/images/x.gif" alt="*cute*"></a><a href="#" class="bbType{s} bbTag{*depressed*}"><img class="smiley depressed" src="/assets/images/x.gif" alt="*depressed*"></a><a href="#" class="bbType{s} bbTag{*eek*}"><img class="smiley eek" src="/assets/images/x.gif" alt="*eek*"></a><a href="#" class="bbType{s} bbTag{*ehem*}"><img class="smiley ehem" src="/assets/images/x.gif" alt="*ehem*"></a><a href="#" class="bbType{s} bbTag{*emotional*}"><img class="smiley emotional" src="/assets/images/x.gif" alt="*emotional*"></a><a href="#" class="bbType{s} bbTag{:D}"><img class="smiley grin" src="/assets/images/x.gif" alt=":D"></a><a href="#" class="bbType{s} bbTag{:)}"><img class="smiley happy" src="/assets/images/x.gif" alt=":)"></a><a href="#" class="bbType{s} bbTag{*hit*}"><img class="smiley hit" src="/assets/images/x.gif" alt="*hit*"></a><a href="#" class="bbType{s} bbTag{*hmm*}"><img class="smiley hmm" src="/assets/images/x.gif" alt="*hmm*"></a><a href="#" class="bbType{s} bbTag{*hmpf*}"><img class="smiley hmpf" src="/assets/images/x.gif" alt="*hmpf*"></a><a href="#" class="bbType{s} bbTag{*hrhr*}"><img class="smiley hrhr" src="/assets/images/x.gif" alt="*hrhr*"></a><a href="#" class="bbType{s} bbTag{*huh*}"><img class="smiley huh" src="/assets/images/x.gif" alt="*huh*"></a><a href="#" class="bbType{s} bbTag{*lazy*}"><img class="smiley lazy" src="/assets/images/x.gif" alt="*lazy*"></a><a href="#" class="bbType{s} bbTag{*love*}"><img class="smiley love" src="/assets/images/x.gif" alt="*love*"></a><a href="#" class="bbType{s} bbTag{*nocomment*}"><img class="smiley nocomment" src="/assets/images/x.gif" alt="*nocomment*"></a><a href="#" class="bbType{s} bbTag{*noemotion*}"><img class="smiley noemotion" src="/assets/images/x.gif" alt="*noemotion*"></a><a href="#" class="bbType{s} bbTag{*notamused*}"><img class="smiley notamused" src="/assets/images/x.gif" alt="*notamused*"></a><a href="#" class="bbType{s} bbTag{*pout*}"><img class="smiley pout" src="/assets/images/x.gif" alt="*pout*"></a><a href="#" class="bbType{s} bbTag{*redface*}"><img class="smiley redface" src="/assets/images/x.gif" alt="*redface*"></a><a href="#" class="bbType{s} bbTag{*rolleyes*}"><img class="smiley rolleyes" src="/assets/images/x.gif" alt="*rolleyes*"></a><a href="#" class="bbType{s} bbTag{:(}"><img class="smiley sad" src="/assets/images/x.gif" alt=":("></a><a href="#" class="bbType{s} bbTag{*shy*}"><img class="smiley shy" src="/assets/images/x.gif" alt="*shy*"></a><a href="#" class="bbType{s} bbTag{*smile*}"><img class="smiley smile" src="/assets/images/x.gif" alt="*smile*"></a><a href="#" class="bbType{s} bbTag{*tongue*}"><img class="smiley tongue" src="/assets/images/x.gif" alt="*tongue*"></a><a href="#" class="bbType{s} bbTag{*veryangry*}"><img class="smiley veryangry" src="/assets/images/x.gif" alt="*veryangry*"></a><a href="#" class="bbType{s} bbTag{*veryhappy*}"><img class="smiley veryhappy" src="/assets/images/x.gif" alt="*veryhappy*"></a><a href="#" class="bbType{s} bbTag{;)}"><img class="smiley wink" src="/assets/images/x.gif" alt=";)"></a></div>
                                            <div id="text_troops"><a href="#" class="bbType{o} bbTag{tid1}"><img class="unit u1" src="/assets/images/x.gif" alt="Legionnaire"></a><a href="#" class="bbType{o} bbTag{tid2}"><img class="unit u2" src="/assets/images/x.gif" alt="Praetorian" title="Praetorian"></a><a href="#" class="bbType{o} bbTag{tid3}"><img class="unit u3" src="/assets/images/x.gif" alt="Imperian" title="Imperian"></a><a href="#" class="bbType{o} bbTag{tid4}"><img class="unit u4" src="/assets/images/x.gif" alt="Equites Legati" title="Equites Legati"></a><a href="#" class="bbType{o} bbTag{tid5}"><img class="unit u5" src="/assets/images/x.gif" alt="Equites Imperatoris" title="Equites Imperatoris"></a><a href="#" class="bbType{o} bbTag{tid6}"><img class="unit u6" src="/assets/images/x.gif" alt="Equites Caesaris" title="Equites Caesaris"></a><a href="#" class="bbType{o} bbTag{tid7}"><img class="unit u7" src="/assets/images/x.gif" alt="Ram" title="Ram"></a><a href="#" class="bbType{o} bbTag{tid8}"><img class="unit u8" src="/assets/images/x.gif" alt="Fire Catapult" title="Fire Catapult"></a><a href="#" class="bbType{o} bbTag{tid9}"><img class="unit u9" src="/assets/images/x.gif" alt="Senator" title="Senator"></a><a href="#" class="bbType{o} bbTag{tid10}"><img class="unit u10" src="/assets/images/x.gif" alt="Settler" title="Settler"></a><a href="#" class="bbType{o} bbTag{tid11}"><img class="unit u11" src="/assets/images/x.gif" alt="Maceman" title="Maceman"></a><a href="#" class="bbType{o} bbTag{tid12}"><img class="unit u12" src="/assets/images/x.gif" alt="Spearman" title="Spearman"></a><a href="#" class="bbType{o} bbTag{tid13}"><img class="unit u13" src="/assets/images/x.gif" alt="Axeman" title="Axeman"></a><a href="#" class="bbType{o} bbTag{tid14}"><img class="unit u14" src="/assets/images/x.gif" alt="Scout" title="Scout"></a><a href="#" class="bbType{o} bbTag{tid15}"><img class="unit u15" src="/assets/images/x.gif" alt="Paladin" title="Paladin"></a><a href="#" class="bbType{o} bbTag{tid16}"><img class="unit u16" src="/assets/images/x.gif" alt="Teutonic Knight" title="Teutonic Knight"></a><a href="#" class="bbType{o} bbTag{tid17}"><img class="unit u17" src="/assets/images/x.gif" alt="Ram" title="Ram"></a><a href="#" class="bbType{o} bbTag{tid18}"><img class="unit u18" src="/assets/images/x.gif" alt="Catapult" title="Catapult"></a><a href="#" class="bbType{o} bbTag{tid19}"><img class="unit u19" src="/assets/images/x.gif" alt="Chieftain" title="Chieftain"></a><a href="#" class="bbType{o} bbTag{tid20}"><img class="unit u20" src="/assets/images/x.gif" alt="Settler" title="Settler"></a><a href="#" class="bbType{o} bbTag{tid21}"><img class="unit u21" src="/assets/images/x.gif" alt="Phalanx" title="Phalanx"></a><a href="#" class="bbType{o} bbTag{tid22}"><img class="unit u22" src="/assets/images/x.gif" alt="Swordsman" title="Swordsman"></a><a href="#" class="bbType{o} bbTag{tid23}"><img class="unit u23" src="/assets/images/x.gif" alt="Pathfinder" title="Pathfinder"></a><a href="#" class="bbType{o} bbTag{tid24}"><img class="unit u24" src="/assets/images/x.gif" alt="Theutates Thunder" title="Theutates Thunder"></a><a href="#" class="bbType{o} bbTag{tid25}"><img class="unit u25" src="/assets/images/x.gif" alt="Druidrider" title="Druidrider"></a><a href="#" class="bbType{o} bbTag{tid26}"><img class="unit u26" src="/assets/images/x.gif" alt="Haeduan" title="Haeduan"></a><a href="#" class="bbType{o} bbTag{tid27}"><img class="unit u27" src="/assets/images/x.gif" alt="Battering Ram" title="Battering Ram"></a><a href="#" class="bbType{o} bbTag{tid28}"><img class="unit u28" src="/assets/images/x.gif" alt="Trebuchet" title="Trebuchet"></a><a href="#" class="bbType{o} bbTag{tid29}"><img class="unit u29" src="/assets/images/x.gif" alt="Chief" title="Chief"></a><a href="#" class="bbType{o} bbTag{tid30}"><img class="unit u30" src="/assets/images/x.gif" alt="Settler" title="Settler"></a>
                                                <a href="#" class="bbType{o} bbTag{tid31}"><img class="unit u31" src="/assets/images/x.gif" alt="Rat" title="Rat"></a><a href="#" class="bbType{o} bbTag{tid32}"><img class="unit u32" src="/assets/images/x.gif" alt="Spider" title="Spider"></a>
                                                <a href="#" class="bbType{o} bbTag{tid33}"><img class="unit u33" src="/assets/images/x.gif" alt="Snake" title="Snake"></a><a href="#" class="bbType{o} bbTag{tid34}"><img class="unit u34" src="/assets/images/x.gif" alt="Bat" title="Bat"></a><a href="#" class="bbType{o} bbTag{tid35}"><img class="unit u35" src="/assets/images/x.gif" alt="Wild Boar" title="Wild Boar"></a><a href="#" class="bbType{o} bbTag{tid36}"><img class="unit u36" src="/assets/images/x.gif" alt="Wolf" title="Wolf"></a><a href="#" class="bbType{o} bbTag{tid37}"><img class="unit u37" src="/assets/images/x.gif" alt="Bear" title="Bear"></a><a href="#" class="bbType{o} bbTag{tid38}"><img class="unit u38" src="/assets/images/x.gif" alt="Crocodile" title="Crocodile"></a><a href="#" class="bbType{o} bbTag{tid39}"><img class="unit u39" src="/assets/images/x.gif" alt="Tiger" title="Tiger"></a><a href="#" class="bbType{o} bbTag{tid40}"><img class="unit u40" src="/assets/images/x.gif" alt="Elephant" title="Elephant"></a><a href="#" class="bbType{o} bbTag{tid41}"><img class="unit u41" src="/assets/images/x.gif" alt="Pikeman" title="Pikeman"></a><a href="#" class="bbType{o} bbTag{tid42}"><img class="unit u42" src="/assets/images/x.gif" alt="Thorned Warrior" title="Thorned Warrior"></a>
                                                <a href="#" class="bbType{o} bbTag{tid43}"><img class="unit u43" src="/assets/images/x.gif" alt="Guardsman" title="Guardsman"></a><a href="#" class="bbType{o} bbTag{tid44}"><img class="unit u44" src="/assets/images/x.gif" alt="Birds of Prey" title="Birds of Prey"></a><a href="#" class="bbType{o} bbTag{tid45}"><img class="unit u45" src="/assets/images/x.gif" alt="Axerider" title="Axerider"></a><a href="#" class="bbType{o} bbTag{tid46}"><img class="unit u46" src="/assets/images/x.gif" alt="Natarian Knight" title="Natarian Knight"></a><a href="#" class="bbType{o} bbTag{tid47}"><img class="unit u47" src="/assets/images/x.gif" alt="War Elephant" title="War Elephant"></a>
                                                <a href="#" class="bbType{o} bbTag{tid48}"><img class="unit u48" src="/assets/images/x.gif" alt="Ballista" title="Ballista"></a><a href="#" class="bbType{o} bbTag{tid49}"><img class="unit u49" src="/assets/images/x.gif" alt="Natarian Emperor" title="Natarian Emperor"></a><a href="#" class="bbType{o} bbTag{tid50}"><img class="unit u50" src="/assets/images/x.gif" alt="Settler" title="Settler"></a><a href="#" class="bbType{o} bbTag{Hero}"><img class="unit uhero" src="/assets/images/x.gif" alt="Hero" title="Hero"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <textarea id="text" name="text"></textarea>

                            <div id="text_preview" name="text_preview"></div>
                        </div>

                        <script type="text/javascript">
                            window.addEvent('domready', function() {
                                new Travian.Game.BBEditor("text");
                            });
                        </script>

                    </td>
                </tr>
            </tbody>
        </table>
        <div class="spacer"></div>
        <button type="submit" value="ok" name="s1" id="fbtn_ok" class="green ">
            <div class="button-container addHoverClick">
                <div class="button-background">
                    <div class="buttonStart">
                        <div class="buttonEnd">
                            <div class="buttonMiddle"></div>
                        </div>
                    </div>
                </div>
                <div class="button-content"><?php echo OK; ?></div>
            </div>
        </button>
        <script type="text/javascript">
            window.addEvent('domready', function() {
                if ($('fbtn_ok')) {
                    $('fbtn_ok').addEvent('click', function() {
                        window.fireEvent('buttonClicked', [this, {
                            "type": "submit",
                            "value": "ok",
                            "name": "s1",
                            "id": "fbtn_ok",
                            "class": "green ",
                            "title": "",
                            "confirm": "",
                            "onclick": ""
                        }]);
                    });
                }
            });
        </script>
    </form>
    </p>
<?php
} else {
    header("Location: banned.php");
    die;
}
?>