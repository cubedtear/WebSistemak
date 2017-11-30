<?php

require_once "session.php";

if (is_logged_in()) {
    redirect("/layout.php");
}

function check_password($pass) {
    try {
        $bezeroa = new SoapClient("https://$_SERVER[HTTP_HOST]/egiaztatuPasahitza.php?wsdl");
        $emaitza = $bezeroa->check_pass($pass);
        return strpos($emaitza, "BALIOZKOA") === 0;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST["email"])) {
    $email = $_POST["email"];

    if (!preg_match("/[a-zA-Z]{2,}[0-9]{3}@ikasle\.ehu\.eu?s/", $email)) {
        $error = "Error: Invalid email";
    }

    $userid = get_userid_from_email($email);

    if ($userid != null) {
        $token = generate_recovery_token($userid);

        $to = $email;
        $subject = "Account recovery";
        $message = "Click the following link to recover your account <br><a href='https://$_SERVER[HTTP_HOST]/accountRecovery.php?token=$token'>Recover!</a>";
        $headers = 'From: aritzhack@gmail.com' . "\r\n" .
            'Reply-To: aritzhack@gmail.com' . "\r\n" .
            "Content-Type:text/html;charset=utf-8";

        $send_result = mail($to, $subject, $message, $headers);
        if (!$send_result) {
            $error = "Error sending email";
        }
    }

    if (!isset($error)) $error = "If the email is valid, a recovery link will have been sent";
}

if (isset($_GET["token"])) {
    $userid = get_user_id_from_recovery_token($_GET["token"]);

    if (is_null($userid)) {
        $error = "Error: Invalid or expired link";
    } else if (isset($_POST["password"])) {
        $pass = $_POST["password"];
        $pass2 = $_POST["password2"];
        if ($pass != $pass2) {
            $error = "Error: Passwords do not match";
        } else {
            if (!check_password($pass)) {
                $error = "Error: Password is too weak";
            } else if (change_password($userid, $pass)) {
                $error = "Password changed successfully";
            } else {
                $error = "Error changing your password!";
            }
        }
    }
    if (!is_null($userid)) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <?php
            require_once "parts/head.php";
            ?>
        </head>
        <body>

        <?php
        require_once "parts/header.php"
        ?>

        <main>
            <div class="container">
                <div class="row">
                    <div class="col s12 center-align">
                        <h2>Enter your new password</h2>
                    </div>
                    <?php
                    if (isset($error)) {
                        echo "<div class='row'><div class='col s12 center-align'><h4>$error</h4></div></div>";
                    }
                    ?>
                    <form class="col s4 push-s4" action="accountRecovery.php?token=<?= $_GET["token"] ?>" method="post">
                        <div class="row">
                            <div class="input-field col s12">
                                <input name="password" id="password" type="password" class="validate" required>
                                <label for="password" data-error="Passwords do not match">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input name="password2" id="password2" type="password" class="validate" required>
                                <label for="password2" data-error="Passwords do not match">Confirm password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 center-align">
                                <button class="btn waves-effect waves-light" type="submit">Submit
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        </main>
        <?php
        require_once "parts/footer.php";
        ?>
        </body>
        </html>
        <?php
        die();
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <?php
    require_once "parts/head.php";
    ?>
</head>
<body>

<?php
require_once "parts/header.php"
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <h2>Recover your password</h2>
            </div>
            <?php
            if (isset($error)) {
                echo "<div class='row'><div class='col s12 center-align'><h4>$error</h4></div></div>";
            }
            ?>
            <form class="col s4 push-s4" action="accountRecovery.php" method="post">
                <div class="row">
                    <div class="input-field col s12">
                        <input name="email" placeholder="xxxxx123@ikasle.ehu.es" id="email" type="email" class="validate" required pattern="[a-zA-Z]{2,}[0-9]{3}@(ikasle\.)?ehu\.eu?s">
                        <label for="email" data-error="Wrong email">Email *</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 center-align">
                        <button class="btn waves-effect waves-light" type="submit">Submit
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
</main>
<?php
require_once "parts/footer.php";
?>
</body>
</html>