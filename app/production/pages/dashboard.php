<?php
require_once __DIR__."/../inc/session.inc.php";

if(empty($_SESSION["userId"]) || !$_SESSION["connected"]){
    header("Location: login.php");
}

echo "login successful";