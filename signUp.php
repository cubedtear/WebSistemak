<?php

require_once "db.php";

$errored = false;
$errored2 = false;
$errored3 = false;

if (isset($_POST["user"])) {

    require_once "vendor/nusoap/nusoap.php";


    function egiaztatu_email($email)
    {
        try {
            $bezeroa = new SoapClient("http://ehusw.es/rosa/webZerbitzuak/egiaztatuMatrikula.php?wsdl");
            $emaitza = $bezeroa->egiaztatuE($email);
            return strpos($emaitza, "BAI") === 0;
        } catch (Exception $e) {
            return false;
        }
    }

    function check_password($pass)
    {
        try {
            $bezeroa = new SoapClient("https://$_SERVER[HTTP_HOST]/egiaztatuPasahitza.php?wsdl");
            $emaitza = $bezeroa->check_pass($pass);
            return strpos($emaitza, "BALIOZKOA") === 0;
        } catch (Exception $e) {
            return false;
        }
    }

    $email = $_POST["email"];
    $name = $_POST["izena"];
    $user = $_POST["user"];
    $pass = $_POST["password"];

    if (!egiaztatu_email($email)) {
        $errored2 = true;
    } else if (!check_password($pass)) {
        $errored3 = true;
    }

    if (!$errored2 && !$errored3) {
        $argazki = get_default_profile_image();
        if (strlen($_FILES["file"]["name"]) > 0) {
            $data = resize_image($_FILES["file"]);
            $argazki = base64_encode($data);
        }

        $id = sign_up($email, $name, $user, $pass, $argazki);
        if ($id != null) {
            header("Location: /layout.php");
            die();
        } else {
            $errored = true;
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>

    <?php
    require_once "parts/head.php";
    ?>
    <script language="JavaScript">
        function init_function() {

            $('#signupform').submit(function () {
                var password = $('#password');
                var confirm_password = $('#password2');
                if (password.val() == confirm_password.val()) {
                    password.removeClass("invalid");
                    confirm_password.removeClass("invalid");
                    return true;
                } else {
                    password.addClass("invalid");
                    confirm_password.addClass("invalid").focus();
                    return false;
                }
            });

        }
    </script>
</head>
<body>

<?php
require_once "parts/header.php"
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <h2>Register</h2>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-align">
                <form class="col s6 push-s3" action="signUp.php" id="signupform" name="signupform" method="post" enctype="multipart/form-data">
                    <?php
                    if ($errored) {
                        echo "<div class='col s12 center-align'><h4>Error: Email already taken!</h4></div>";
                    }
                    if ($errored2) {
                        echo "<div class='col s12 center-align'><h4>Error: You are not registered in the subject!</h4></div>";
                    }
                    if ($errored3) {
                        echo "<div class='col s12 center-align'><h4>Error: Your password is too weak!</h4></div>";
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
                            <input name="izena" id="izena" type="text" class="validate" required>
                            <label for="izena" data-error="Name and surnames, beggining with capital letters">Name and surnames</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input name="user" id="user" type="text" class="validate" required>
                            <label for="user" data-error="Must not contain any whitespace">Username</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input name="password" id="password" type="password" class="validate" required>
                            <label for="password" data-error="Passwords do not match">Password</label>
                        </div>
                        <div class="input-field col s6">
                            <input name="password2" id="password2" type="password" class="validate" required>
                            <label for="password2" data-error="Passwords do not match">Confirm password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 file-field">
                            <div class="btn">
                                <span>Profile picture</span>
                                <input name="file" type="file" id="fitxategia" accept="image/*">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path" type="text">
                            </div>
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
    </div>
</main>
<?php
require_once "parts/footer.php";
?>
</body>
</html>