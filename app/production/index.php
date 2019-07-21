<?php
session_start();
require_once __DIR__."/inc/db-connect.inc.php";
require_once __DIR__."/inc/header.inc.php";
require_once __DIR__."/inc/functions.inc.php";
if(isset($_GET["username"])){
    $username = $_GET["username"];
}else{
    $username = "";
};
?>
<div class="tile  is-vertical container landingForms">
    
<form class="tile is-vertical box" id="loginForm" action="pages/login.php" method="POST">
    <p>Connectez-vous avec votre compte</p>
    <div class="field">
    <label class="label" for="username">Votre pseudo</label>
    <div class="control has-icons-left has-icons-right">
        <input class="input" type="text" placeholder="Entrez votre pseudo" name="username" value="<?=$username?>" >
    </div>
    </div>

    <div class="field">
    <p class="control has-icons-left">
        <label class="label" for="mdp">Votre mot de passe</label>
        <input class="input" type="password" placeholder="Entrez votre mot de passe" name="mdp">
    </p>
    </div>

    <div class="field">
        <p class="control">
            <p class="help is-danger">
            <?php if(isset($_GET["action"])) {
                $action = $_GET["action"];

                if($action == "loginNeeded"){ ?>
                    Vous devez vous connecter
                <?php } ?>

                <?php if($action == "emptyUsername"){ ?>
                    Vous devez rentrer un pseudo
                <?php } ?>

                <?php if($action == "emptyPassword"){ ?>
                    Vous devez rentrer un mot de passe
                <?php } ?>

                <?php if($action == "wrongInfo"){ ?>
                    Votre pseudo ou votre mot de passe est incorrect
                <?php } ?>

                <?php if($action == "disconnect"){ ?>
                    Au revoir et à bientôt
                <?php
                unset($_SESSION["userId"]);
                unset($_SESSION["connected"]);
                    }
                } ?>
            </p>
            <button type="submit" class="button is-success">
                Entrez
            </button>
    </p>
    </div>

</form>

<form class="tile is-vertical box" id="signupForm" action="pages/signup.php" method="POST">
    <p>Créer un nouveau compte</p>
    <div class="field">
    <label class="label" for="username">Votre pseudo</label>
    <div class="control has-icons-left has-icons-right">
        <input class="input" type="text" placeholder="Entrez votre pseudo" name="username" value="<?=$username?>" >
    </div>
    </div>

    <div class="field">
    <p class="control has-icons-left">
        <label class="label" for="mdp">Mot de passe (Au moins 8 caractères)</label>
        <input class="input" type="password" placeholder="Définissez un mot de passe" name="mdp">
    </p>
    </div>

    <div class="field">
    <p class="control has-icons-left">
        <label class="label" for="email">Votre email</label>
        <input class="input" type="email" placeholder="Entrez votre email" name="email">
    </p>
    </div>

    <div class="field">
        <p class="control">
            <p class="help is-danger">
            <?php if(isset($_GET["signupResult"])) {

                $signupResult = $_GET["signupResult"];

                if($signupResult == "emptyUsername"){
                ?>Vous devez rentrer un pseudo<?php }

                if($signupResult == "emptyPassword"){
                ?>Vous devez rentrer un mot de passe<?php }

                if($signupResult == "emptyEmail"){
                    ?>Vous devez rentrer un email valid<?php }

                if($signupResult == "failed"){
                    ?>Une erreur est survenu lors de la création du compte<?php }

                if($signupResult == "success"){
                    ?>Votre compte est créé. Un email vous est envoyé pour son activation.<?php }
            } ?>
            </p>
            <button type="submit" class="button is-success">
                Envoyer
            </button>
    </p>
    </div>
</form>

</div>