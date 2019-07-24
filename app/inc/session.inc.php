<?php
session_start();
// echo __DIR__."/../pages/login.php";
// die;
require_once __DIR__."/../Class/User.php";
if(empty($_SESSION["userId"]) || !$_SESSION["connected"]){
    header("Location: ../pages/login.php");
}else{
    $userId = ($_SESSION["userId"]);
    $connected = $_SESSION["connected"];
    $userObject = new User($userId);
}