<?php
if ( $_POST['payload'] || $_GET["override"] ) {
    echo "Running <code>git reset --hard HEAD && git pull<code>. Output:<br><pre>"; 
    echo shell_exec( 'git reset --hard HEAD && git pull' );
    echo "</pre>";
} else {
    echo "<h1>You are not GitHub. Get out of here!</h1>";
}
?>
