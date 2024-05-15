<?php
include("GameEngine/Protection.php");
include("GameEngine/Account.php");

if (isset($_GET['del_cookie'])) {
    setcookie("COOKUSR", "", time() - 3600 * 24, "/");
    header("Location: login.php");
}
if (!isset($_COOKIE['COOKUSR'])) {
    $_COOKIE['COOKUSR'] = "";
}
include("templates/html.php");
?>

<div id="content" class="activate">

    <?php
    echo '
<body class="v35 gecko login perspectiveBuildings">

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
					<div id="sidebarBoxMenu" class="sidebarBox ">
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
								<a id="answersButton" class="contentTitleButton" href="http://t4.answers.travian.ir" target="_blank">&nbsp;</a>
							</div>
							<div class="contentContainer">
								<div id="content" class="login">';
    ?>

    <h1 class="titleInHeader"><?php echo REG; ?></h1>

    <?php
    if (!isset($_GET['token']) && !isset($_GET['cv']) && !isset($_GET['form'])) {

        if (isset($_GET['e']) && !is_numeric($_GET['e']))
            $_GET['e'] = 1;
        if (isset($_GET['e'])) {
            switch ($_GET['e']) {
                case 1:
                    include("templates/activate/delete.php");
                    break;
                case 2:
                    include("templates/activate/activated.php");
                    break;
                case 3:
                    include("templates/activate/cantfind.php");
                    break;
            }
        } else if (isset($_GET['id']) && isset($_GET['c'])) {
            if (isset($_GET['id']) && !is_numeric($_GET['id']))
                die('Attempt of sql injection blocked');
            $c = $database->getActivateField($_GET['id'], "email", 0);
            if ($_GET['c'] == $generator->encodeStr($c, 5)) {
                include("templates/activate/delete.php");
            } else {
                include("templates/activate/activate.php");
            }
        } else {
            include("templates/activate/activate.php");
        }
    } else {
        if (isset($_GET['token']) && isset($_GET['cv'])) {
            if ($_GET['token'] != $_SESSION['token']) {
                unset($_SESSION['token']);
                header("Location: login.php");
                exit;
            }
            unset($_SESSION['token']);
            $_GET['cv'] = filter_var($_GET['cv'], FILTER_SANITIZE_MAGIC_QUOTES);
            $_SESSION['MYUID'] = $_GET['cv'];

            function generateHash($plainText, $salt = 1)
            {
                $salt = substr($salt, 0, 9);
                return $salt . sha1($salt . $plainText);
            }

            for ($i = 1; $i <= 50; $i++) {
                if (generateHash($i) == $_GET['cv']) {
                    $_GET['cv'] = $i;
                    break;
                }
            }

            $_SESSION['MYP'] = 123123;
    ?>
            <form method="POST" action="activate.php?form=activator">
                <button type="submit" value="" name="">
                    <SCRIPT type="text/javascript">
                        document.forms[0].submit();
                    </SCRIPT>
                <?php
            }

            if (isset($_SESSION['MYUID']) && $_GET['form'] == 'activator' && !isset($_GET['step'])) {
                $_GET['cv'] = filter_var($_GET['cv'], FILTER_SANITIZE_NUMBER_INT);
                $_GET['cv'] = filter_var($_GET['cv'], FILTER_SANITIZE_MAGIC_QUOTES);
                $result = mysql_query("SELECT `reg2` FROM users WHERE id='" . $_SESSION['MYUID'] . "' LIMIT 1");
                $row = mysql_fetch_array($result);

                if ($row['reg2'] != 1 and $_SESSION['MYUID'] == '') {
                    header("Location: login.php");
                    exit;
                }
                ?>
                    <div id="vid">
                        <div class="ffBug"></div>
                        <div class="greenbox boxVidInfo">
                            <div class="greenbox-top"></div>
                            <div class="greenbox-content">
                                <h2>Choose your tribe</h2>
                            </div>
                            <div class="greenbox-bottom"></div>
                            <div class="clear"></div>
                        </div>
                        <div class="boxes boxGrey boxesColor gray">
                            <div class="boxes-tl"></div>
                            <div class="boxes-tr"></div>
                            <div class="boxes-tc"></div>
                            <div class="boxes-ml"></div>
                            <div class="boxes-mr"></div>
                            <div class="boxes-mc"></div>
                            <div class="boxes-bl"></div>
                            <div class="boxes-br"></div>
                            <div class="boxes-bc"></div>
                            <div class="boxes-contents cf">
                                <div class="content">
                                    <form method="POST" action="activate.php?form=activator&step=2">
                                        <input type="hidden" name="vid" value="3" />
                                        <input type="hidden" name="uid" value="<?php echo $_SESSION['MYUID']; ?>" />
                                        <div class="container">
                                            <div class="vidDescription">
												Great empires begin with important decisions!
                                                Are you an attacker who loves to fight? Otherwise, save your time relatively
                                                do you? A team player who likes to develop a thriving economy to fire the fuse?
                                            </div>
                                            <div class="vidSelect">
                                                <div class="kind">
                                                    <div id="vid3" class="vid vid3"></div>
                                                    <div id="vid1" class="vid vid1"></div>
                                                    <div id="vid2" class="vid vid2"></div>
                                                </div>
                                                <div class="description-container">
                                                    <div class="description vid1">
                                                        <div class="bubble"></div>
                                                        <div class="text">
                                                            <div class="headline">Teutons</div>
                                                            <div class="text">
                                                                <div class="special">Properties:</div>
                                                                <ul>
                                                                    <li>High time requirements</li>
                                                                    <li>Good for looting in the early game</li>
                                                                    <li>Powerful, cheap infantry</li>
                                                                    <li>For offensive players</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="avatar vid1"></div>
                                                    </div>
                                                    <div class="description vid2">
                                                        <div class="bubble"></div>
                                                        <div class="text">
                                                            <div class="headline">Romans</div>
                                                            <div class="text">
                                                                <div class="special">Properties:</div>
                                                                <ul>
                                                                    <li>Average time requirements</li>
                                                                    <li>Can improve villages in the fastest way</li>
                                                                    <li>Very strong but expensive military troops</li>
                                                                    <li>Hard to play for new players</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="avatar vid2"></div>
                                                    </div>
                                                    <div class="description vid3">
                                                        <div class="bubble"></div>
                                                        <div class="text">
                                                            <div class="headline">Gauls</div>
                                                            <div class="text">
                                                                <div class="special">Properties:</div>
                                                                <ul>
                                                                    <li>Low time requirements.</li>
                                                                    <li>Loot protection and good defense.</li>
                                                                    <li>Excellent, fast cavalry.</li>
                                                                    <li>Very suitable for new players.</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="avatar vid3"></div>
                                                    </div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="submitButton">
                                            <button type="submit" value="Choose A tribe" name="submitKind" id="submitKind" class="green ">
                                                <div class="button-container addHoverClick ">
                                                    <div class="button-background">
                                                        <div class="buttonStart">
                                                            <div class="buttonEnd">
                                                                <div class="buttonMiddle"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="button-content">Approve</div>
                                                </div>
                                            </button>
                                            <script type="text/javascript">
                                                window.addEvent('domready', function() {
                                                    if ($('submitKind')) {
                                                        $('submitKind').addEvent('click', function() {
                                                            window.fireEvent('buttonClicked', [this, {
                                                                "type": "submit",
                                                                "value": "Choose a tribe",
                                                                "name": "submitKind",
                                                                "id": "submitKind",
                                                                "class": "green ",
                                                                "title": "",
                                                                "confirm": "",
                                                                "onclick": ""
                                                            }]);
                                                        });
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var vid = new Travian.Game.Vid('vid3');
                    </script>
                    <div id="tpixeliframe_loading" style="display: none; z-index: 1000; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background-color: #000; opacity: 0.4; -moz-opacity: 0.4;"></div>
                    <script type="text/javascript">
                        var tg_load_handler = function() {
                            document.getElementById("tpixeliframe_loading").style.display = "none";
                        };
                        tg_load_handler.delay(1000);
                        window.onload = function() {
                            tg_iframe = document.getElementById("tpixeliframe");
                            tg_iframe.onload = tg_load_handler;
                        };
                        document.getElementById("tpixeliframe_loading").style.display = "block";
                    </script>
                <?php
            } else if (isset($_GET['step']) && $_GET['step'] == 2) {
                $_SESSION['MYVID'] = $_POST['vid'];
                header("Location: activate.php?form=activator&step=1");
                exit();
            } else if (isset($_GET['step']) && $_GET['step'] == 1) {
                ?>
                    <div id="sector">
                        <form name="snd" method="POST" action="activate.php">
                            <input type="hidden" name="uid" value="<?php echo $_SESSION['MYUID']; ?>" />
                            <div class="ffBug"></div>
                            <input type="hidden" name="vid" value="<?php echo $_SESSION['MYVID']; ?>" />
                            <input type="hidden" name="ft" value="a0" />

                            <div class="greenbox boxVidInfo">
                                <div class="greenbox-top"></div>
                                <div class="greenbox-content">
                                    <div>Choose start location</div>

                                    <div class="changeVid"><a href="activate.php?form=activator">Turn back</a>
                                    </div>
                                </div>
                                <div class="greenbox-bottom"></div>
                                <div class="clear"></div>
                            </div>
                            <div class="boxes boxGrey boxesColor gray">
                                <div class="boxes-tl"></div>
                                <div class="boxes-tr"></div>
                                <div class="boxes-tc"></div>
                                <div class="boxes-ml"></div>
                                <div class="boxes-mr"></div>
                                <div class="boxes-mc"></div>
                                <div class="boxes-bl"></div>
                                <div class="boxes-br"></div>
                                <div class="boxes-bc"></div>
                                <div class="boxes-contents cf">
                                    <div class="content">
                                        <div class="sectorDescription">
											Where to start building your empire you want?
											Use the "recommended" area for the most suitable region.
											Or Choose the region where your friends are and create a team!<br>
                                        </div>
                                        <div class="sectorSelect">
                                            <div class="map">
                                                <div id="nw" class="sector nw a">
                                                    <div class="highlight"></div>
                                                </div>
                                                <div id="no" class="sector no a">
                                                    <div class="highlight"></div>
                                                </div>
                                                <div id="sw" class="sector sw a">
                                                    <div class="highlight"></div>
                                                </div>
                                                <div id="so" class="sector so a">
                                                    <div class="highlight"></div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="start">
                                                <div class="center">
                                                    <select name="sector">
                                                        <option value="nw">North - West</option>
                                                        <option value="no">North - East</option>
                                                        <option value="sw">South - West</option>
                                                        <option value="so">South - East</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="buttonContainer">
                                                <button type="submit" value="Create village" name="submitSector" id="submitSector" class="green submitSector">
                                                    <div class="button-container addHoverClick">
                                                        <div class="button-background">
                                                            <div class="buttonStart">
                                                                <div class="buttonEnd">
                                                                    <div class="buttonMiddle"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="button-content">Approve</div>
                                                    </div>
                                                </button>
                                                <script type="text/javascript">
                                                    window.addEvent('domready', function() {
                                                        if ($('submitSector')) {
                                                            $('submitSector').addEvent('click', function() {
                                                                window.fireEvent('buttonClicked', [this, {
                                                                    "type": "submit",
                                                                    "value": "Create village",
                                                                    "name": "submitSector",
                                                                    "id": "submitSector",
                                                                    "class": "green submitSector",
                                                                    "title": "",
                                                                    "confirm": "",
                                                                    "onclick": ""
                                                                }]);
                                                            });
                                                        }
                                                    });
                                                </script>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <?php
                                        if ($vid == 3) {
                                            $class = "avatar vid3";
                                        } else if ($vid == 1) {
                                            $class = "avatar vid1";
                                        } else if ($vid == 2) {
                                            $class = "avatar vid2";
                                        }
                                        ?>
                                        <div class="<?php echo $class; ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script type="text/javascript">
                        var sector = new Travian.Game.Sector('nw');
                    </script>
            <?php
            }
        }
        ?>
            <div class="clear">&nbsp;</div>
</div>
<div class="clear"></div>
</div>
<div class="contentFooter">&nbsp;</div>
</div>

<div id="sidebarAfterContent" class="sidebar afterContent">
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
                <?php
                if (NEWSBOX1) {
                    echo '<div class="news news1"><center style="font-family:arial; direction:rtl;"> ';
                ?>
                    <a href="#" class="newsContent newsContentWithLink" onclick="$H({data: {cmd: 'News', id: '1'}}).dialog(); return false;">
                        <?php
                        $t1 = trim(file_get_contents("templates/News/newsbox1.php"));
                        echo $t1 . "</a><br>";
                        echo '</a></center></div>';
                        ?>
            </div>
            <div class="innerBox footer">
            </div>
        </div>
    </div>
<?php } ?>
<div id="sidebarBoxNews2" class="sidebarBox sidebarBoxNews">

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
            <?php
            if (NEWSBOX2) {
                echo '<div class="news news2"><center style="font-family:arial;direction:rtl;">';
            ?>
                <a href="#" class="newsContent newsContentWithLink" onclick="$H({data: {cmd: 'News', id: '2'}}).dialog(); return false;">
                <?php
                $t2 = trim(file_get_contents("templates/News/newsbox2.php"));
                echo $t2 . "</a><br>";
            }
                ?>
        </div>
        <div class="innerBox footer"></div>
    </div>
</div>

</div>
</div>

<?php include('templates/footer.php'); ?>
</div>
<div id="ce"></div>
</div>
</div>
</div>

</body>

</html>
