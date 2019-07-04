<?php
require_once __DIR__."/inc/session.inc.php";
require_once __DIR__."/inc/db-connect.inc.php";
require_once __DIR__."/inc/header.inc.php";
if(isset($_GET["username"])){
    $username = $_GET["username"];
}else{
    $username = "";
};
?>
<form id="loginForm" action="pages/login.php" method="POST">

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