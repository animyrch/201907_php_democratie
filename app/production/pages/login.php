<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";
require_once __DIR__."/../inc/functions.inc.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["username"]) && isset($_POST["mdp"])){
        $username = $_POST["username"];
        $mdp = $_POST["mdp"];
        $loginResult = User::CheckUser($username, $mdp);

        if($loginResult === -60){
            header("Location: ../index.php?action=emptyUsername");
        }
        elseif($loginResult === -10){
            header("Location: ../index.php?action=emptyPassword");
        }
        elseif($loginResult === -70){
            header("Location: ../index.php?action=wrongInfo&username=$username");
        }
        elseif($loginResult === -13){
            header("Location: ../index.php?action=pendingAccount");
        }
        if(!invalidId($loginResult)){
            $_SESSION["userId"] = $loginResult;
            $_SESSION["connected"] = true;
            header("Location: ./dashboard.php");
        }

    }
}else{
    header("Location: ../index.php?action=loginNeeded");
}
?>

