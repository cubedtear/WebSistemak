<?php

require_once "db.php";
require_once "session.php";
require_once __DIR__ . "/session.php";

if (is_logged_in()) {
    redirect("/layout.php");
}

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $userid = get_user_id_from_unlock_token($token);
    if (!is_null($userid)) {
        unlock_account($userid);
        $error = "Account unlocked<br>Log in";
    }
}

if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $id = check_credentials($email, $pass);
    if ($id > 0) {
        // TODO Increase logged in count

        if ($email == "web000@ehu.es") {
            log_in_as(Rol::Teacher, $id);
            redirect("/reviewingQuizes.php");
        } else {
            log_in_as(Rol::User, $id);
            redirect("/handlingQuizes.php");
        }
        die();
    } else if ($id == 0) {
        $error = "Error: Too many attempts<br>Your account has been blocked!<br>Check your email to restore it";
    } else {
        $error = "Error: Incorrect email or password!";
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
                <h2>Log in</h2>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-align">
                <form class="col s6 push-s3" action="login.php" id="loginform" name="loginform" method="post" enctype="multipart/form-data">
                    <?php
                    if (isset($error)) {
                        echo "<div class='col s12 center-align'><h4>$error</h4></div>";
                    }
                    ?>
                    <div class="row">
                        <div class="input-field col s12">
                            <input name="email" placeholder="xxxxx123@ikasle.ehu.es" id="email" type="email" class="validate" required pattern="[a-zA-Z]{2,}[0-9]{3}@(ikasle\.)?ehu\.eu?s">
                            <label for="email" data-error="Wrong email">Email *</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input name="password" id="password" type="password" class="validate" required>
                            <label for="password" data-error="Passwords do not match">Password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s4 push-s2">
                            <button class="btn waves-effect waves-light" id="reset" type="reset" name="reset">Reset
                                <i class="material-icons right">settings_backup_restore</i>
                            </button>
                        </div>
                        <div class="col s4 push-s2">
                            <button class="btn waves-effect waves-light" id="submit" type="submit" name="submit">Submit
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-align">
                <a href="accountRecovery.php">Forgot your password?</a>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-align">
                Don't have an account? <a href="signUp.php">Create one!</a>
            </div>
        </div>
    </div>
</main>
<?php
require_once "parts/footer.php";
?>
</body>
</html>