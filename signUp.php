<?php

require_once "db.php";

$errored = false;

if (isset($_POST["user"])) {
    // TODO Register

    $email = $_POST["email"];
    $name = $_POST["izena"];
    $user = $_POST["user"];
    $pass = $_POST["password"];

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
                <h2>Register</h2>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-align">
                <form class="col s6 push-s3" action="signUp.php" id="signupform" name="signupform" method="post" enctype="multipart/form-data">
                    <?php
                    if ($errored) {
                        echo "<div class='col s12 center-align'><h4>Error: Username already taken!</h4></div>";
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