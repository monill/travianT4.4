﻿<?php include('../GameEngine/Lang/' . $_SESSION['lang'] . '.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="<?php echo $_SESSION['lang']; ?>">
    <link href="/admin/main.css" rel="stylesheet">
    <title>ACP :: <?php echo SERVER_NAME; ?></title>

    <script language="javascript" src="/assets/js/crypt.js" type="text/javascript"></script>

    <script>
        var editing = false;
        var CurrentStep = 1;

        function SetCurrent(val) {
            if (CurrentStep != val) {
                $('div_' + CurrentStep).style.display = 'none';
                $('a_title_' + CurrentStep).className = '';
                $('div_' + val).style.display = 'block';
                $('a_title_' + val).className = 'current';
            }
            CurrentStep = val;
        }
    </script>

    <link href="/admin/login.css" rel="stylesheet">
</head>

<body>
    <div align="center">
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="login">
            <div class="login">
                <div align="center"><img border="0" src="/admin/images/admin/admin.gif"></div>
                <table style="margin-top:20px" align="center" cellpadding="3" cellspacing="3" width="300" height="142">
                    <h4>Enter the control panel</h4>
                    <div style="color: #F00;">
                        <?php
                        if (isset($_GET['error'])) {
                            if ($_GET['error'] == 1) {
                                echo 'Incorrect username or password.';
                            } elseif ($_GET['error'] == 2) {
                                echo 'Please enter security code carefully.';
                            }
                        }
                        ?>
                    </div>
                    <tr>
                        <td height="19" width="70">
                            <img border="0" src="/admin/images/admin/breadcrumb_separator_arrow_1_dot.png" width="10" height="5"> username:
                        </td>
                        <td height="19" width="371" colspan="2">
                            <input name="name" type="text" value="<?php echo $_SESSION['username']; ?>" maxlength="15">
                        </td>
                    </tr>
                    <tr>
                        <td height="21" width="70">
                            <img border="0" src="/admin/images/admin/breadcrumb_separator_arrow_1_dot.png" width="10" height="5"> password
                        </td>
                        <td height="21" width="371" colspan="2"><input name="pw" type="password" value="" maxlength="20">
                        </td>
                    </tr>
                    <tr>
                        <td height="19" width="157">
                            <img border="0" src="/admin/images/admin/breadcrumb_separator_arrow_1_dot.png" width="10" height="5">security code:
                        </td>
                        <td height="19" width="131"><input name="img" maxlength="6" size="6"></td>
                        <td height="19" width="131"><img border="0" src="img.php?code_section=<?php echo time(); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p align="center">
                                <button type="submit" class="loginbtn" value="">
                                    <img width="80" height="20" src="/admin/images/admin/b/l1.gif" />
                                </button>
                            </p>
                        </td>
                    </tr>
                </table>

            </div>
        </form>
    </div>
    <div id="ce"></div>
    </div>
</body>

</html>