<?php
session_start();
// echo __DIR__."/../pages/login.php";
// die;
if(empty($_SESSION["userId"]) || !$_SESSION["connected"]){
    header("Location: ../pages/login.php");
}else{
    $userId = ($_SESSION["userId"]);
    $connected = $_SESSION["connected"];
}