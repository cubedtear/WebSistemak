<?php

$files = scandir(ini_get("session.save_path"));

if (!$files) {
    echo "Error";
} else {
    print_r($files);
}