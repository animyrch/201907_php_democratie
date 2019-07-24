<?php 
require_once "../inc/functions.inc.php";
require_once "../Class/User.php";
$userToken = (!empty($_GET["tokenId"]) ? $_GET["tokenId"] : "");
$userId = (!empty($_GET["userId"]) ? $_GET["userId"] : "");

$user = new User($userId);
$validationResult = $user->validateAccount($userId, $userToken);

header("Location: dashboard.php");
