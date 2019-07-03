<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";
require_once __DIR__."/../inc/functions.inc.php";
// debug($_SERVER);
// var_dump($_SERVER);
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["username"]) && isset($_POST["mdp"])){
        $username = $_POST["username"];
        $mdp = $_POST["mdp"];
        $userCheckResult = checkUser($username, $mdp);
        // $resultsArray = ["emptyUsername" => -1, "emptyPassword" => -2, "wrongUsernameOrPassword" => -3];
        if($userCheckResult === -1){
            header("Location: ../index.php?action=emptyUsername");
        }
        if($userCheckResult === -2){
            header("Location: ../index.php?action=emptyPassword");
        }
        if($userCheckResult === -3){
            header("Location: ../index.php?action=wrongInfo&username=$username");
        }
        if($userCheckResult > 0){
            $_SESSION["userId"]=$userCheckResult;
            $_SESSION["connected"] = true;
            header("Location: ./dashboard.php");
        }

    }
}else{
    header("Location: ../index.php?action=loginNeeded");
}
?>

