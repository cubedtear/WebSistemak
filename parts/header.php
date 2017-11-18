<?php require_once __DIR__ . "/../session.php"; ?>

<header class='main' id='h1'>
    <nav>
        <div class="nav-wrapper" style="padding-left: 32px">
            <a href="<?= get_link("/layout.php") ?>" class="brand-logo">Quizzes</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="<?= get_link("/layout.php") ?>">Home</a></li>
                <?php
                if (is_logged_in()) {
                    ?>
<!--                    <li><a href="--><?//= get_link("/addQuestionWithImages.php") ?><!--">Add quiz</a></li>-->
                    <li><a href="<?= get_link("/getQuestions.php") ?>">Get question SOAP</a></li>
                    <li><a href="<?= get_link("/handlingQuizes.php") ?>">Handling quizes</a></li>
                    <li><a href="<?= get_link("/showQuestionsWithImages.php") ?>">Show quizes</a></li>
                    <li><a href="<?= get_link("/logOut.php"); ?>">
                            <div style="display: flex; align-items: center">
                                Log out
                                <img style='max-height: 50px;max-width: 200px; margin-left: 16px' src="/img.php?id=<?= get_user_from_token($_GET["token"]) ?>">
                            </div>
                        </a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li><a href="/login.php">Log in</a></li>
                    <li><a href="/signUp.php">Sign up</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
</header>