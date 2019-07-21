<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";
require_once __DIR__."/../inc/functions.inc.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["username"]) && isset($_POST["mdp"]) && isset($_POST["email"])){
        $username = $_POST["username"];
        $mdp = $_POST["mdp"];
        $email = $_POST["email"];
        $userCreateResult = createUser($username, $mdp, $email);

        if($userCreateResult === -60){
            header("Location: ../index.php?signupResult=emptyUsername");
        }
        elseif($userCreateResult === -10){
            header("Location: ../index.php?signupResult=emptyPassword");
        }
        elseif($userCreateResult === -19){
            header("Location: ../index.php?signupResult=emptyEmail");
        }
        else{
            header("Location: ../index.php?signupResult=success");
        }

    }
}else{
    header("Location: ../index.php?signupResult=failed");
}
?>
