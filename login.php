<?php

require_once "db.php";
require_once "session.php";
require_once __DIR__ . "/session.php";

$errored = false;

if (is_logged_in()) {
    redirect("/layout.php");
}

if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $id = check_credentials($email, $pass);
    if (!is_null($id)) {

        $xml = new SimpleXMLElement(file_get_contents("xml/counter.xml"));
        $xml[0] = intval($xml[0])+1;
        $xml->asXML("xml/counter.xml");

        redirect("/layout.php?token=" . get_user_token($id));
        die();
    } else {
        $errored = true;
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
                    if ($errored) {
                        echo "<div class='col s12 center-align'><h4>Error: Incorrect email or password!</h4></div>";
                    }
                    ?>
                    <div class="row">
                        <div class="input-field col s12">
                            <input name="email" placeholder="xxxxx123@ikasle.ehu.es" id="email" type="email" class="validate" required pattern="[a-zA-Z]{2,}[0-9]{3}@ikasle\.ehu\.eu?s">
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
                Don't have an account? <a href="<?= get_link("signUp.php") ?>">Create one!</a>
            </div>
        </div>
    </div>
</main>
<?php
require_once "parts/footer.php";
?>
</body>
</html>