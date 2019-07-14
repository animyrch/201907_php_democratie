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

        if($userCheckResult === -60){
            header("Location: ../index.php?action=emptyUsername");
        }
        elseif($userCheckResult === -10){
            header("Location: ../index.php?action=emptyPassword");
        }
        elseif($userCheckResult === -70){
            header("Location: ../index.php?action=wrongInfo&username=$username");
        }
        else{
            $_SESSION["userId"] = $userCheckResult;
            $_SESSION["connected"] = true;
            header("Location: ./dashboard.php");
        }

    }
}else{
    header("Location: ../index.php?action=loginNeeded");
}
?>

