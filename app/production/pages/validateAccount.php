<?php 
require_once "../inc/functions.inc.php";

$userToken = (!empty($_GET["tokenId"]) ? $_GET["tokenId"] : "");
$userId = (!empty($_GET["userId"]) ? $_GET["userId"] : "");

$validationResult = validateUserAccount($userId, $userToken);

header("Location: dashboard.php");
