<?php

include("GameEngine/Protection.php");
include("GameEngine/Account.php");
include("templates/html.php");

if (isset($_GET['activated'])) {
    $onload = 'onload="$H({data:{cmd:\'News\',id:\'activate\'}}).dialog();"';
} else {
    $onload = '';
}

echo '
<body class="v35 gecko login perspectiveBuildings" ' . $onload . '>

	<link rel="stylesheet" href="/assets/css/jquery.countdown.css">
	<style type="text/css">
#defaultCountdown { width: 240px; height: 45px; }
div.innerLoginBox2{height:400px;background-image:url(/assets/images/quest_new_village.jpg);}
</style>
	<div id="background">
		<img id="staticElements" src="/assets/images/x.gif" alt="">
		<div id="bodyWrapper">
			<img style="filter:chroma();" src="/assets/images/x.gif" id="msfilter" alt="">
			<div id="header">
				<div id="mtop">
					<a id="logo" href="' . HOMEPAGE . '" target="_blank" title="' . SERVER_NAME . '"></a>
					<div class="clear"></div>
				</div>
			</div>
			<div id="center">
				<div id="sidebarBeforeContent" class="sidebar beforeContent">
					<div id="sidebarBoxMenu" class="sidebarBox">
                        <div class="sidebarBoxBaseBox">
                            <div class="baseBox baseBoxTop">
                                <div class="baseBox baseBoxBottom">
                                    <div class="baseBox baseBoxCenter"></div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebarBoxInnerBox">
                            <div class="innerBox header noHeader"></div>
                            <div class="innerBox content">
                        <ul>
                            <li>
                                <a href="' . HOMEPAGE . '" title="' . HOME . '">' . HOME . '</a>
                            </li>

                            <li class="active">
                                <a href="login.php" title="' . LOGIN . '">' . LOGIN . '</a>
                            </li>

                            <li>
                                <a href="anmelden.php" title="' . REG . '">' . REG . '</a>
                            </li>

                            <li>
                                <a href="#" title="' . FORUM . '">' . FORUM . '</a>
                            </li>

                            <li class="support">
                                <a href="#" title="' . SUPPORT . '">' . SUPPORT . '</a>
                            </li>

                        </ul>
                    </div>

                    <div class="innerBox footer">
					</div>
				</div></div></div>
						<div id="contentOuterContainer">
							<div class="contentTitle">
								<a id="answersButton" class="contentTitleButton" href="#" target="_blank">&nbsp;</a>
							</div>
							<div class="contentContainer">
								<div id="content" class="login">
                                    <h1 class="titleInHeader">' . LOGIN . '</h1>';
?>
<script type="text/javascript">
    Element.implement({
        showOrHide: function(imgid) {
            //einblenden
            if (this.getStyle('display') == 'none') {
                if (imgid != '') {
                    $(imgid).className = 'open';
                }
            }
            //ausblenden
            else {
                if (imgid != '') {
                    $(imgid).className = 'close';
                }
            }
            this.toggleClass('hide');
        }
    });
</script>
<script type="text/javascript">
    Element.implement({
        showOrHide: function(imgid) {
            //einblenden
            if (this.getStyle('display') == 'none') {
                if (imgid != '') {
                    $(imgid).className = 'open';
                }
            } else {
                if (imgid != '') {
                    $(imgid).className = 'close';
                }
            }
            this.toggleClass('hide');
        }
    });
</script>
<?php
if (COMMENCE < time()) {
?>
    <div class="outerLoginBox <?php echo $loginform; ?> <?php if ($_SESSION['LOGIN_FAILED'] <= 3) {
                                                            echo "";
                                                        } else {
                                                            echo "withCaptcha";
                                                        } ?>">
        <h2>Turco</h2>
        <>
            <div class="noJavaScript"><?php echo LG_NOJAVA; ?></div>

            <div class="innerLoginBox">
                <form method="post" name="snd" action="login.php" class="<?php echo $loginform; ?>">
                    <input type="hidden" name="ft" value="a4" />
                    <table class="transparent loginTable">
                        <tbody>
                            <tr class="account">
                                <td class="accountNameOrEmailAddress">Username</td>
                                <td>
                                    <input type="text" name="user" value="<?php echo $form->getDiff("user", $_COOKIE['COOKUSR']); ?>" class="text">
                                    <div class="error RTL"><?php echo $form->getError("user"); ?></div>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="pass">
                                <td>Password</td>
                                <td>
                                    <input type="password" maxlength="20" name="pw" value="<?php echo $form->getValue("pw"); ?>" class="text"><br>
                                    <div class="error RTL"><?php echo $form->getError("pw"); ?></div>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="lowResOption">
                                <td>Low graphics</td>
                                <td colspan="2">
                                    <input type="checkbox" class="checkbox" id="lowRes" name="lowRes" value="1">
                                    <label for="lowRes">Recommended for those with low internet speed</label>
                                </td>
                            </tr>
                            <?php if ($_SESSION['LOGIN_FAILED'] > 3) { ?>
                                <tr class="captcha">
                                    <td></td>
                                    <td>
                                        <?php
                                        // show captcha HTML using Securimage::getCaptchaHtml()
                                        require_once dirname(__FILE__) . "/" . 'Security/securimage.php';
                                        $options = array();
                                        $options['input_name'] = 'ct_captcha'; // change name of input element for form post
                                        $options['show_audio_button'] = FALSE;
                                        if (!empty($_SESSION['ctform']['captcha_error'])) {
                                            // error html to show in captcha output
                                            $options['error_html'] = $_SESSION['ctform']['captcha_error'];
                                        }

                                        echo Securimage::getCaptchaHtml($options);
                                        ?>
                                        <div class='error' id='captchaInfo'><?php echo $form->getError('captcha'); ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <button type="submit" value="Request password" name="s1" id="s1" class="green" onclick="document.login.w.value=screen.width+':'+screen.height;">
                                        <div class="button-container addHoverClick">
                                            <div class="button-background">
                                                <div class="buttonStart">
                                                    <div class="buttonEnd">
                                                        <div class="buttonMiddle"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="button-content">Login</div>
                                        </div>
                                    </button>
                                    <input type="hidden" name="w" value="">
                                    <input type="hidden" name="login" value="<?php echo time(); ?>">
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="greenbox passwordForgotten">
                <div class="greenbox-top"></div>
                <div class="greenbox-content">
                    <div class="passwordForgottenLink">
                        <a onclick="$('showPasswordForgotten').showOrHide('arrow');" href="<?php echo isset($_GET['action']) ? '#' : '?action=forgotPassword'; ?>" class="showPWForgottenLink">
                            <img class="close" id="arrow" src="/assets/images/x.gif">I forgot my password?
                        </a>
                    </div>
                    <div class="showPasswordForgotten <?php if (isset($_GET['action']) && $_GET['action'] == 'forgotPassword') {
                                                        } else {
                                                            echo 'hide';
                                                        } ?>" id="showPasswordForgotten">
                        <?php if (isset($_GET['finish'])) { ?>
                            <font color="#008000"><?php echo LG_RPSENT; ?></font>
                        <?php } else { ?>
                            <form method="POST" action="">
                                <input type="hidden" name="forgotPassword" value="1">

                                <div class="forgotPasswordDescription">Your password is sent by e-mail.</div>
                                <table class="transparent pwForgottenTable" id="pw_forgotten_form" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr class="mail">
                                            <th>E-mail:</th>
                                            <td>
                                                <input class="text" type="text" name="pw_email" value=""><br>

                                                <div class="error RTL"><?php echo $form->getError("pw_email"); ?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="2">
                                                <button type="submit" value="Request password" name="s2" id="s2" class="green ">
                                                    <div class="button-container addHoverClick">
                                                        <div class="button-background">
                                                            <div class="buttonStart">
                                                                <div class="buttonEnd">
                                                                    <div class="buttonMiddle"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="button-content">Send My Password</div>
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        <?php } ?>
                    </div>
                </div>
                <div class="greenbox-bottom"></div>
                <div class="clear"></div>
            </div>

        <?php } else { ?>
            <div class="outerLoginBox " id="startsindiv">
                <div class="innerLoginBox2">
                    <table class="transparent loginTable">
                        <tbody>
                            <tr class="account">
                                <td><?php echo LG_SERVERSTART; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        dthen = <?php echo COMMENCE; ?>;
                        var dnow = <?php echo time() ?>;
                        CountActive = true;
                        CountStepper = -1;
                        LeadingZero = true;
                        DisplayFormat = "%%D%% %%H%% %%M%% %%S%%";
                        FinishMessage = "<?php echo LG_STARTNOW; ?>";

                        function calcage(secs, num1, num2) {
                            s = ((Math.floor(secs / num1)) % num2).toString();
                            if (LeadingZero && s.length < 2) s = "0" + s;
                            return "" + s + "";
                        }

                        function CountBack(secs) {
                            if (secs < 0) {
                                window.setTimeout(function() {
                                    window.location.href = '<?php echo HOMEPAGE . "/" . sv_ . ""; ?>';
                                }, 5000);
                            }
                            DisplayStr = DisplayFormat.replace(/%%D%%/g, "<span class='countdown-row countdown-show4'><span class='countdown-section'><span class='countdown-amount'>" + calcage(secs, 86400, 100000) + "</span><span class='countdown-period'>Days</span></span>");
                            DisplayStr = DisplayStr.replace(/%%H%%/g, "<span class='countdown-section'><span class='countdown-amount'>" + calcage(secs, 3600, 24) + "</span><span class='countdown-period'>Hours</span></span>");
                            DisplayStr = DisplayStr.replace(/%%M%%/g, "<span class='countdown-section'><span class='countdown-amount'>" + calcage(secs, 60, 60) + "</span><span class='countdown-period'>Minutes</span></span>");
                            DisplayStr = DisplayStr.replace(/%%S%%/g, "<span class='countdown-section'><span class='countdown-amount'>" + calcage(secs, 1, 60) + "</span><span class='countdown-period'>Seconds</span></span></span>");

                            document.getElementById("defaultCountdown").innerHTML = DisplayStr;
                            if (CountActive) setTimeout("CountBack(" + (secs + CountStepper) + ")", SetTimeOutPeriod);
                        }

                        function putspan(backcolor, forecolor) {
                            document.write("<div class='is-countdown' id='defaultCountdown'></div>");
                        }

                        if (typeof(BackColor) == "undefined") BackColor = "white";
                        if (typeof(ForeColor) == "undefined") ForeColor = "black";

                        CountStepper = Math.ceil(CountStepper);
                        if (CountStepper == 0)
                            CountActive = false;
                        var SetTimeOutPeriod = (Math.abs(CountStepper) - 1) * 1000 + 990;
                        putspan(BackColor, ForeColor);

                        if (CountStepper > 0)
                            ddiff = new Date(dnow - dthen);
                        else
                            ddiff = new Date(dthen - dnow);
                        gsecs = Math.floor(ddiff);
                        CountBack(gsecs);
                    </script>
                    <?php
                    echo '<div class="clear"></div></div>';
                }
                echo '</div> </div>
                <div class="clear"></div>
		        </div>
                <div class="contentFooter">&nbsp;</div>
                </div>
                <div id="sidebarAfterContent" class="sidebar afterContent">';
                if (NEWSBOX1) {
                    $t1 = trim(file_get_contents("templates/News/newsbox1.php"));
                    if (strlen($t1) > 0) {
                    ?>
                        <div id="sidebarBoxNews1" class="sidebarBox sidebarBoxNews">
                            <div class="sidebarBoxBaseBox">
                                <div class="baseBox baseBoxTop">
                                    <div class="baseBox baseBoxBottom">
                                        <div class="baseBox baseBoxCenter"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebarBoxInnerBox">
                                <div class="innerBox header noHeader"></div>
                                <div class="innerBox content">
                                    <div class="news news1">
                                        <a href="#" class="newsContent newsContentWithLink" onclick="$H({data:{cmd:'News',id:'1'}}).dialog(); return false;"><?php echo $t1; ?></a>
                                    </div>
                                </div>
                                <div class="innerBox footer"></div>
                            </div>
                        </div>
                    <?php
                    }
                }
                if (NEWSBOX2) {
                    $t2 = trim(file_get_contents("templates/News/newsbox2.php"));
                    if (strlen($t2) > 0) {
                    ?>
                        <div id="sidebarBoxNews2" class="sidebarBox   sidebarBoxNews">
                            <div class="sidebarBoxBaseBox">
                                <div class="baseBox baseBoxTop">
                                    <div class="baseBox baseBoxBottom">
                                        <div class="baseBox baseBoxCenter"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebarBoxInnerBox">
                                <div class="innerBox header noHeader"></div>
                                <div class="innerBox content">
                                    <div class="news news2">
                                        <a href="#" class="newsContent newsContentWithLink" onclick="$H({data:{cmd:'News',id:'2'}}).dialog(); return false;"><?php echo $t2; ?></a>
                                    </div>
                                </div>
                                <div class="innerBox footer"></div>
                            </div>
                        </div>
                <?php
                    }
                }
                echo '</div></div>';
                if (isset($_GET['action']) && $_GET['action'] == 'forgotPassword') {
                    echo "<br><br><br><br><br>";
                }
                include('templates/footer.php');
                echo '</div><div id="ce"></div></div></div></div></body></html>';
