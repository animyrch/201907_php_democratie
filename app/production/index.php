<?php
require_once __DIR__."/inc/session.php";
require_once __DIR__."/inc/db-connect.inc.php";
require_once __DIR__."/inc/header.inc.php";


if(empty($_SESSION["userid"])){
    header("Location: pages/login.php");
}

