<?php
session_start();

if(empty($_SESSION["userId"]) || !$_SESSION["connected"]){
    header("Location: /edsa-php_democratie/app/production/pages/login.php");
}