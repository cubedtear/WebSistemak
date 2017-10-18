<?php
function orri_sinple($content)
{
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
                    <?php
                    echo $content;
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once "parts/footer.php";
    ?>
    </body>
    </html>
    <?php
}
