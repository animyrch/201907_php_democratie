<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";

$messageErreur = "";
$contenu = "";
$title = "";
$readOnly = !empty($_GET["disabled"]);

//first access
if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(!empty($_GET["proposition"])){
        $propositionId = $_GET["proposition"];
    }else{
        $messageErreur = "Aucune proposition n'est séléctionnée";
    }
    if($messageErreur === ""){
        require_once __DIR__."/../inc/functions.inc.php";
        $proposition = getProposition($userId, $propositionId);
        if($proposition){
            $title = $proposition->title;
            $contenu = $proposition->contenu;
        }else{
            header("Location: dashboard.php?action=propositionUpdateFailed");
        }
    }
}

//modification submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["propositionId"])){
        $propositionId = $_POST["propositionId"];
    }else{
        header("Location: dashboard.php?action=propositionUpdateFailed");
    }
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
        if(isset($_POST['btnUpdate'])){
            updateProposition($userId, $propositionId, $title, $contenu);
        }else{
            submitPropositionToVote($userId, $propositionId);
        }
        header("Location: dashboard.php?action=propositionUpdated");
    }
}

?>
<section>
    <div class="container">
        <form method="POST">
            <h1 class="title is-1">Détails de votre proposition</h1>
            <div class="box">
                <article class="media">
                    <div class="media-content">
                        <div class="content">
                            <div class="field">
                                <div class="control">
                                    <label for="title">Titre</label>
                                    <input class="input" type="text" placeholder="Le titre de votre proposition" name="title" value="<?=$title?>" <?php if($readOnly) echo "disabled"; ?>>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <label for="contenu">Contenu</label>
                                    <textarea class="textarea" placeholder="Le contenu de votre proposition" name="contenu" <?php if($readOnly) echo "disabled"; ?>><?=$contenu?></textarea>
                                </div>
                            </div>

                            <?php if($readOnly){ ?>
                            <div class="notification is-primary">Cette proposition est déjà soumise au vote et ne peut plus être modifier.</div>
                            <?php }else{ ?>
                            <div class="notification is-primary">Lorque vous soumettez une proposition au vote, il ne sera plus possible de la modifier.</div>
                            <?php }?>

                            <div class="field">
                                <p class="control">
                                    <p class="help is-danger">
                                    <?php if($messageErreur !== ""){echo $messageErreur; } ?>
                                    </p>
                                </p>
                            </div>
                            <input name="propositionId" style="display : none" value="<?=$propositionId?>">
                            <div class="field is-grouped">
                                <div class="control">
                                    <button type="submit" name="btnValid" class="button is-dark" <?php if($readOnly) echo "disabled"; ?>>Soumettre au vote</button>
                                </div>
                                <div class="control">
                                    <button type="submit" name="btnUpdate" class="button is-link" <?php if($readOnly) echo "disabled"; ?>>Mettre à jour</button>
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