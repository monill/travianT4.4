<?php

$tid = $_GET['tid'];
$topics = $database->ShowTopic($tid);
foreach ($topics as $arr) {
    $title = $arr['title'];
}
?>
<form method="post" name="post" action="allianz.php?s=2&pid=<?php echo $_GET['pid']; ?>&tid=<?php echo $_GET['tid']; ?>">
    <input type="hidden" name="s" value="2">
    <input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
    <input type="hidden" name="tid" value="<?php echo $_GET['tid']; ?>">
    <input type="hidden" name="newpost" value="1">

    <input type="hidden" name="checkstr" value="c0d">
    <h4 class="round">ارسال پاسخ</h4>


    <table class="transparent" id="new_post">
        <tbody>
            <tr>
                <th>موضوع:</th>
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
                                    <button type="button" value="bbBold" class="icon bbButton bbBold bbType{d} bbTag{b}">
                                        <img src="/assets/images/x.gif" class="bbBold" alt="bbBold"></button>
                                    <button type="button" value="bbItalic" class="icon bbButton bbItalic bbType{d} bbTag{i}"><img src="/assets/images/x.gif" class="bbItalic" alt="bbItalic"></button>
                                    <button type="button" value="bbUnderscore" class="icon bbButton bbUnderscore bbType{d} bbTag{u}"><img src="/assets/images/x.gif" class="bbUnderscore" alt="bbUnderscore">
                                    </button>
                                    <button type="button" value="bbAlliance" class="icon bbButton bbAlliance bbType{d} bbTag{اتحاد}"><img src="/assets/images/x.gif" class="bbAlliance" alt="bbAlliance">
                                    </button>
                                    <button type="button" value="bbPlayer" class="icon bbButton bbPlayer bbType{d} bbTag{بازیکن}"><img src="/assets/images/x.gif" class="bbPlayer" alt="bbPlayer">
                                    </button>
                                    <button type="button" value="bbCoordinate" class="icon bbButton bbCoordinate bbType{d} bbTag{x|y}"><img src="/assets/images/x.gif" class="bbCoordinate" alt="bbCoordinate">
                                    </button>
                                    <button type="button" value="bbReport" class="icon bbButton bbReport bbType{d} bbTag{گزارش}"><img src="/assets/images/x.gif" class="bbReport" alt="bbReport">
                                    </button>
                                    <button type="button" value="bbResource" id="text_resourceButton" class="bbWin{resources} bbButton bbResource icon"><img src="/assets/images/x.gif" class="bbResource" alt="bbResource">
                                    </button>
                                    <button type="button" value="bbSmilies" id="text_smilieButton" class="bbWin{smilies} bbButton bbSmilies icon"><img src="/assets/images/x.gif" class="bbSmilies" alt="bbSmilies"></button>
                                    <button type="button" value="bbTroops" id="text_troopButton" class="bbWin{troops} bbButton bbTroops icon"><img src="/assets/images/x.gif" class="bbTroops" alt="bbTroops"></button>
                                    <button type="button" value="bbPreview" id="text_previewButton" class="icon bbButton bbPreview"><img src="/assets/images/x.gif" class="bbPreview" alt="bbPreview"></button>
                                    <div class="clear"></div>
                                    <div id="text_toolbarWindows" class="bbToolbarWindow">
                                        <div id="text_resources"><a href="#" class="bbType{o} bbTag{l}"><img src="/assets/images/x.gif" class="r1" alt="چوب"></a><a href="#" class="bbType{o} bbTag{cl}"><img src="/assets/images/x.gif" class="r2" alt="خشت"></a><a href="#" class="bbType{o} bbTag{c}"><img src="/assets/images/x.gif" class="r4" alt="گندم"></a><a href="#" class="bbType{o} bbTag{i}"><img src="/assets/images/x.gif" class="r3" alt="آهن"></a></div>
                                        <div id="text_smilies"><a href="#" class="bbType{s} bbTag{*aha*}"><img class="smiley aha" src="/assets/images/x.gif" alt="*aha*"></a><a href="#" class="bbType{s} bbTag{*angry*}"><img class="smiley angry" src="/assets/images/x.gif" alt="*angry*"></a><a href="#" class="bbType{s} bbTag{*cool*}"><img class="smiley cool" src="/assets/images/x.gif" alt="*cool*"></a><a href="#" class="bbType{s} bbTag{*cry*}"><img class="smiley cry" src="/assets/images/x.gif" alt="*cry*"></a><a href="#" class="bbType{s} bbTag{*cute*}"><img class="smiley cute" src="/assets/images/x.gif" alt="*cute*"></a><a href="#" class="bbType{s} bbTag{*depressed*}"><img class="smiley depressed" src="/assets/images/x.gif" alt="*depressed*"></a><a href="#" class="bbType{s} bbTag{*eek*}"><img class="smiley eek" src="/assets/images/x.gif" alt="*eek*"></a><a href="#" class="bbType{s} bbTag{*ehem*}"><img class="smiley ehem" src="/assets/images/x.gif" alt="*ehem*"></a><a href="#" class="bbType{s} bbTag{*emotional*}"><img class="smiley emotional" src="/assets/images/x.gif" alt="*emotional*"></a><a href="#" class="bbType{s} bbTag{:D}"><img class="smiley grin" src="/assets/images/x.gif" alt=":D"></a><a href="#" class="bbType{s} bbTag{:)}"><img class="smiley happy" src="/assets/images/x.gif" alt=":)"></a><a href="#" class="bbType{s} bbTag{*hit*}"><img class="smiley hit" src="/assets/images/x.gif" alt="*hit*"></a><a href="#" class="bbType{s} bbTag{*hmm*}"><img class="smiley hmm" src="/assets/images/x.gif" alt="*hmm*"></a><a href="#" class="bbType{s} bbTag{*hmpf*}"><img class="smiley hmpf" src="/assets/images/x.gif" alt="*hmpf*"></a><a href="#" class="bbType{s} bbTag{*hrhr*}"><img class="smiley hrhr" src="/assets/images/x.gif" alt="*hrhr*"></a><a href="#" class="bbType{s} bbTag{*huh*}"><img class="smiley huh" src="/assets/images/x.gif" alt="*huh*"></a><a href="#" class="bbType{s} bbTag{*lazy*}"><img class="smiley lazy" src="/assets/images/x.gif" alt="*lazy*"></a><a href="#" class="bbType{s} bbTag{*love*}"><img class="smiley love" src="/assets/images/x.gif" alt="*love*"></a><a href="#" class="bbType{s} bbTag{*nocomment*}"><img class="smiley nocomment" src="/assets/images/x.gif" alt="*nocomment*"></a><a href="#" class="bbType{s} bbTag{*noemotion*}"><img class="smiley noemotion" src="/assets/images/x.gif" alt="*noemotion*"></a><a href="#" class="bbType{s} bbTag{*notamused*}"><img class="smiley notamused" src="/assets/images/x.gif" alt="*notamused*"></a><a href="#" class="bbType{s} bbTag{*pout*}"><img class="smiley pout" src="/assets/images/x.gif" alt="*pout*"></a><a href="#" class="bbType{s} bbTag{*redface*}"><img class="smiley redface" src="/assets/images/x.gif" alt="*redface*"></a><a href="#" class="bbType{s} bbTag{*rolleyes*}"><img class="smiley rolleyes" src="/assets/images/x.gif" alt="*rolleyes*"></a><a href="#" class="bbType{s} bbTag{:(}"><img class="smiley sad" src="/assets/images/x.gif" alt=":("></a><a href="#" class="bbType{s} bbTag{*shy*}"><img class="smiley shy" src="/assets/images/x.gif" alt="*shy*"></a><a href="#" class="bbType{s} bbTag{*smile*}"><img class="smiley smile" src="/assets/images/x.gif" alt="*smile*"></a><a href="#" class="bbType{s} bbTag{*tongue*}"><img class="smiley tongue" src="/assets/images/x.gif" alt="*tongue*"></a><a href="#" class="bbType{s} bbTag{*veryangry*}"><img class="smiley veryangry" src="/assets/images/x.gif" alt="*veryangry*"></a><a href="#" class="bbType{s} bbTag{*veryhappy*}"><img class="smiley veryhappy" src="/assets/images/x.gif" alt="*veryhappy*"></a><a href="#" class="bbType{s} bbTag{;)}"><img class="smiley wink" src="/assets/images/x.gif" alt=";)"></a>
                                        </div>
                                        <div id="text_troops"><a href="#" class="bbType{o} bbTag{tid1}"><img class="unit u1" src="/assets/images/x.gif" alt="سرباز لژیون"></a><a href="#" class="bbType{o} bbTag{tid2}"><img class="unit u2" src="/assets/images/x.gif" alt="محافظ"></a><a href="#" class="bbType{o} bbTag{tid3}"><img class="unit u3" src="/assets/images/x.gif" alt="شمشیرزن"></a><a href="#" class="bbType{o} bbTag{tid4}"><img class="unit u4" src="/assets/images/x.gif" alt="خبرچین"></a><a href="#" class="bbType{o} bbTag{tid5}"><img class="unit u5" src="/assets/images/x.gif" alt="شوالیه"></a><a href="#" class="bbType{o} bbTag{tid6}"><img class="unit u6" src="/assets/images/x.gif" alt="شوالیۀ سزار"></a><a href="#" class="bbType{o} bbTag{tid7}"><img class="unit u7" src="/assets/images/x.gif" alt="دژکوب"></a><a href="#" class="bbType{o} bbTag{tid8}"><img class="unit u8" src="/assets/images/x.gif" alt="منجنیق آتشین"></a><a href="#" class="bbType{o} bbTag{tid9}"><img class="unit u9" src="/assets/images/x.gif" alt="سناتور"></a><a href="#" class="bbType{o} bbTag{tid10}"><img class="unit u10" src="/assets/images/x.gif" alt="مهاجر"></a><a href="#" class="bbType{o} bbTag{tid11}"><img class="unit u11" src="/assets/images/x.gif" alt="گرزدار"></a><a href="#" class="bbType{o} bbTag{tid12}"><img class="unit u12" src="/assets/images/x.gif" alt="نیزه دار"></a><a href="#" class="bbType{o} bbTag{tid13}"><img class="unit u13" src="/assets/images/x.gif" alt="تبرزن"></a><a href="#" class="bbType{o} bbTag{tid14}"><img class="unit u14" src="/assets/images/x.gif" alt="جاسوس"></a><a href="#" class="bbType{o} bbTag{tid15}"><img class="unit u15" src="/assets/images/x.gif" alt="دلاور"></a><a href="#" class="bbType{o} bbTag{tid16}"><img class="unit u16" src="/assets/images/x.gif" alt="شوالیۀ توتن"></a><a href="#" class="bbType{o} bbTag{tid17}"><img class="unit u17" src="/assets/images/x.gif" alt="دژکوب"></a><a href="#" class="bbType{o} bbTag{tid18}"><img class="unit u18" src="/assets/images/x.gif" alt="منجنیق"></a><a href="#" class="bbType{o} bbTag{tid19}"><img class="unit u19" src="/assets/images/x.gif" alt="رئیس"></a><a href="#" class="bbType{o} bbTag{tid20}"><img class="unit u20" src="/assets/images/x.gif" alt="مهاجر"></a><a href="#" class="bbType{o} bbTag{tid21}"><img class="unit u21" src="/assets/images/x.gif" alt="سرباز پیاده"></a><a href="#" class="bbType{o} bbTag{tid22}"><img class="unit u22" src="/assets/images/x.gif" alt="شمشیرزن"></a><a href="#" class="bbType{o} bbTag{tid23}"><img class="unit u23" src="/assets/images/x.gif" alt="رد یاب"></a><a href="#" class="bbType{o} bbTag{tid24}"><img class="unit u24" src="/assets/images/x.gif" alt="رعد"></a><a href="#" class="bbType{o} bbTag{tid25}"><img class="unit u25" src="/assets/images/x.gif" alt="کاهن سواره"></a><a href="#" class="bbType{o} bbTag{tid26}"><img class="unit u26" src="/assets/images/x.gif" alt="شوالیۀ گول"></a><a href="#" class="bbType{o} bbTag{tid27}"><img class="unit u27" src="/assets/images/x.gif" alt="دژکوب"></a><a href="#" class="bbType{o} bbTag{tid28}"><img class="unit u28" src="/assets/images/x.gif" alt="منجنیق"></a><a href="#" class="bbType{o} bbTag{tid29}"><img class="unit u29" src="/assets/images/x.gif" alt="رئیس قبیله"></a><a href="#" class="bbType{o} bbTag{tid30}"><img class="unit u30" src="/assets/images/x.gif" alt="مهاجر"></a><a href="#" class="bbType{o} bbTag{tid31}"><img class="unit u31" src="/assets/images/x.gif" alt="موش صحرایی"></a><a href="#" class="bbType{o} bbTag{tid32}"><img class="unit u32" src="/assets/images/x.gif" alt="عنکبوت"></a><a href="#" class="bbType{o} bbTag{tid33}"><img class="unit u33" src="/assets/images/x.gif" alt="مار"></a><a href="#" class="bbType{o} bbTag{tid34}"><img class="unit u34" src="/assets/images/x.gif" alt="خفاش"></a><a href="#" class="bbType{o} bbTag{tid35}"><img class="unit u35" src="/assets/images/x.gif" alt="گراز"></a><a href="#" class="bbType{o} bbTag{tid36}"><img class="unit u36" src="/assets/images/x.gif" alt="گرگ"></a><a href="#" class="bbType{o} bbTag{tid37}"><img class="unit u37" src="/assets/images/x.gif" alt="خرس"></a><a href="#" class="bbType{o} bbTag{tid38}"><img class="unit u38" src="/assets/images/x.gif" alt="تمساح"></a><a href="#" class="bbType{o} bbTag{tid39}"><img class="unit u39" src="/assets/images/x.gif" alt="ببر"></a><a href="#" class="bbType{o} bbTag{tid40}"><img class="unit u40" src="/assets/images/x.gif" alt="فیل"></a><a href="#" class="bbType{o} bbTag{tid41}"><img class="unit u41" src="/assets/images/x.gif" alt="نیزه دار ناتار"></a><a href="#" class="bbType{o} bbTag{tid42}"><img class="unit u42" src="/assets/images/x.gif" alt="تيغ پوش"></a><a href="#" class="bbType{o} bbTag{tid43}"><img class="unit u43" src="/assets/images/x.gif" alt="محافظ ناتار"></a><a href="#" class="bbType{o} bbTag{tid44}"><img class="unit u44" src="/assets/images/x.gif" alt="پرندگان شکاری"></a><a href="#" class="bbType{o} bbTag{tid45}"><img class="unit u45" src="/assets/images/x.gif" alt="تيشه زن"></a><a href="#" class="bbType{o} bbTag{tid46}"><img class="unit u46" src="/assets/images/x.gif" alt="شوالیۀ ناتار"></a><a href="#" class="bbType{o} bbTag{tid47}"><img class="unit u47" src="/assets/images/x.gif" alt="فيل عظيم الجثۀ جنگی"></a><a href="#" class="bbType{o} bbTag{tid48}"><img class="unit u48" src="/assets/images/x.gif" alt="منجنیق عظيم"></a><a href="#" class="bbType{o} bbTag{tid49}"><img class="unit u49" src="/assets/images/x.gif" alt="امپراطوری ناتار"></a><a href="#" class="bbType{o} bbTag{tid50}"><img class="unit u50" src="/assets/images/x.gif" alt="مهاجر"></a><a href="#" class="bbType{o} bbTag{قهرمان}"><img class="unit uhero" src="/assets/images/x.gif" alt="قهرمان"></a></div>
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
    <button type="submit" value="save" class="green build" name="s1" id="btn_save">
        <div class="button-container addHoverClick">
            <div class="button-background">
                <div class="buttonStart">
                    <div class="buttonEnd">
                        <div class="buttonMiddle"></div>
                    </div>
                </div>
            </div>
            <div class="button-content">تایید</div>
        </div>
    </button>
</form>