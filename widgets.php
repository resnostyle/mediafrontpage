<?php
require_once "config.php";
require_once "functions.php";

foreach (glob("widgets/*.php") as $filename) {
    include_once $filename;
}
?>