<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";

$title = "";
$contenu = "";
$messageErreur = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["contenu"])){
        $contenu = $_POST["contenu"];
    }else{
        $messageErreur = "Le contenu est obligatoire";
    }
    if(!empty($_POST["title"])){
        $title = $_POST["title"];
    }else{
        $messageErreur = "Le titre est obligatoire";
    }
    //If, after the checks, there is no error, we enter data to DB et redirect to dashboard
    if($messageErreur === ""){
        require_once __DIR__."/../inc/functions.inc.php";
        createProposition($title, $contenu, $_SESSION["userId"]);
        header("Location: dashboard.php?action=propositionDone");
    }
}

?>
<section>
    <div class="container">
        <form method="POST">
            <h1 class="title is-1">Ajouter une proposition</h1>
            <div class="box">
                <article class="media">
                    <div class="media-content">
                        <div class="content">
                            <div class="field">
                                <div class="control">
                                    <label for="title">Titre</label>
                                    <input class="input" type="text" placeholder="Le titre de votre proposition" name="title" value="<?=$title?>" >
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <label for="contenu">Contenu</label>
                                    <textarea class="textarea" placeholder="Le contenu de votre proposition" name="contenu"><?=$contenu?></textarea>
                                </div>
                            </div>
                            <div class="notification is-primary">Lorque vous créez une proposition, vous votez forcément pour elle.</div>
                            <div class="field">
                                <p class="control">
                                    <p class="help is-danger">
                                    <?php if($messageErreur !== ""){echo $messageErreur; } ?>
                                    </p>
                                </p>
                            </div>
                            <div class="field is-grouped">
                                <div class="control">
                                    <button type="submit" class="button is-link">Envoyer</button>
                                </div>
                                <div class="control">
                                    <a href="dashboard.php" class="button is-text">Revenir au tableau de bord</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </form>
    </div>
</section>