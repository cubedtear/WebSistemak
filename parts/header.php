<?php require_once __DIR__ . "/../session.php"; ?>

<header class='main' id='h1'>
    <nav>
        <div class="nav-wrapper" style="padding-left: 32px">
            <a href="/layout.php" class="brand-logo">Quizzes</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="/layout.php">Home</a></li>
                <?php
                if (is_logged_in()) {
                    if (is_user()) {
                        ?>
                        <!--                    <li><a href="--><? //= get_link("/addQuestionWithImages.php") ?><!--">Add quiz</a></li>-->
                        <li><a href="/getQuestions.php">Get question SOAP</a></li>
                        <li><a href="/handlingQuizes.php">Handling quizes</a></li>
                        <li><a href="/showQuestionsWithImages.php">Show quizes</a></li>

                        <?php
                    } else if (is_teacher()) {
                        ?>
                        <li><a href="/reviewingQuizes.php">Review quizes</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="/logOut.php">
                            <div style="display: flex; align-items: center">
                                Log out
                                <img style='max-height: 50px;max-width: 200px; margin-left: 16px' src="/img.php?id=<?= get_user_id(); ?>">
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