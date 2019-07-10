<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";

$propositionId = "";
$user = "";
$alreadyVoted = 0;

if(!empty($_GET["alreadyVoted"])){
    $alreadyVoted = $_GET["alreadyVoted"];
}
if(!empty($_GET["user"])){
    $proposer = $_GET["user"];
}else{
    $messageErreur = "Aucune utilisateur n'est associé avec cette séléction";
}
if(!empty($_GET["propositionId"])){
    $propositionId = $_GET["propositionId"];
}else{
    $messageErreur = "Aucune proposition n'est associé avec cette séléction";
}

require_once __DIR__."/../inc/functions.inc.php";
// debug($_GET);
$proposition = getProposition($proposer, $propositionId);
if($proposition){
    $title = $proposition->title;
    $contenu = $proposition->contenu;
}else{
    header("Location: dashboard.php?action=propositionDisplayFailed");
}
?>
<section>
    <div class="container">
        <article class="message">
        <div class="message-header">
            <p><?=$title?></p>
        </div>
        <div class="message-body">
            <p><?=$contenu?></p>
        </div>
        <div class="control">
            <a href="dashboard.php" class="button is-text">Revenir au tableau de bord</a>
        </div>
        <div class="voteContainer">
            <a href="dashboard.php?action=voteFor&proposition=<?=$propositionId?>" class="button is-success is-rounded" <?php if($alreadyVoted == 1) echo " disabled "; ?>>Voter Pour Cette Proposition</a>
            <a href="dashboard.php?action=voteAgainst&proposition=<?=$propositionId?>" class="button is-danger is-rounded" <?php if($alreadyVoted == 1) echo " disabled "; ?>>Voter Contre Cette Proposition</a>
        </div>
        </article>
    </div>
    
</section>