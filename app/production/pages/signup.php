<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";
require_once __DIR__."/../inc/functions.inc.php";
require_once __DIR__."/../../config/env.php";

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
            $userToken = getUserById($userCreateResult)->user_token;
            $activationLink = "http://apformation.test/201907_php_democratie/app/production/pages/validateAccount.php?tokenId=$userToken&userId=$userCreateResult";
            $subject = " Activation compte pour $username sur Democratie 2.0";
            $message = "Coucou, voici ton lien d'activation. Clique le et le compte (voire le monde) sera à toi. $activationLink";
            $message = wordwrap($message, 70, "\n", true);
            if(mail($email,$subject,$message)){
                $message = "Un nouveau compte est créé pour $email sur Democratie 2.0";
                mail($_WEBMASTERMAIL,$subject,$message);
                header("Location: ../index.php?signupResult=success");
            }else{
                $message = "Création de compte a échoué pour $email sur Democratie 2.0";
                mail($_WEBMASTERMAIL,$subject,$message);
                header("Location: ../index.php?signupResult=failed");
            }            
        }
    }
}else{
    header("Location: ../index.php?signupResult=failed");
}
?>
