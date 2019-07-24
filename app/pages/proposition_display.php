<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";

$messageErreur = "";
$propositionId = "";
$proposer = null;
$user = "";
$alreadyVoted = 0;

if(!empty($_GET["alreadyVoted"])){
    $alreadyVoted = $_GET["alreadyVoted"];
}
if(!empty($_GET["user"])){
    $proposerId = $_GET["user"];
    $proposer = new User($proposerId);
}else{
    $messageErreur = "Aucune utilisateur n'est associé avec cette séléction";
}
if(!empty($_GET["propositionId"])){
    $propositionId = $_GET["propositionId"];
}else{
    $messageErreur = "Aucune proposition n'est associé avec cette séléction";
}

require_once __DIR__."/../inc/functions.inc.php";
require_once __DIR__."/../Class/User.php";

// debug($_GET);
$proposition = getProposition($proposerId, $propositionId);
if($proposition){
    $title = $proposition->title . " - " . $proposer->username ;
    $contenu = $proposition->contenu;
}else{
    header("Location: dashboard.php?action=propositionDisplayFailed");
}
?>
<!-- Propositions section -->
<section>
    <?php if($messageErreur != ""){ ?>
    <div class="notification is-primary"><?= $messageErreur ?></div>
    <?php } ?>
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
        <form method="POST" action="?user=<?=$proposerId?>&propositionId=<?=$propositionId?>&alreadyVoted=<?=$alreadyVoted?>&action=comment">
            <div class="field">
                <label class="label">Laisser un commentaire</label>
                <div class="control">
                    <textarea name="commentContent" class="textarea" placeholder="Votre commentaire"></textarea>
                </div>
            </div>
            <div class="control comment-button">
                <button class="button is-link">Submit</button>
            </div>
        </form>
    </div>
</section>


<!-- Comments section -->
<?php
$action = (!empty($_GET["action"]) ? $_GET["action"] : "");
if($action == "comment"){
    $commentContent = $_POST["commentContent"];
    createComment($userId, $propositionId, $commentContent);
}
if($action == "deleteComment"){
    $commentToDelete = $_GET["commentToDelete"];
    deleteComment($userId, $commentToDelete);
}
$comments = [];
$comments = getComments($propositionId);
?>
<section>
    <div class="container">
        <header class="card-header">
            <p class="card-header-title">
                Les commentaires sur cette proposition (<?=count($comments)?>)
            </p>
        </header>
        <div class="comments tile is-ancestor is-vertical">

        <?php foreach($comments as $key => $comment){ 
        $commenter = new User($comment->id_user);
        $commentTime =  $comment->date_comment;
        $commentTimeHumanReadable =  $commentTime; ?>
        <div class="card tile is-child">
            <div class="card-content tile is-ancestor is-horizontal">
                <div class="content tile  is-11 is-child">
                    <?=$comment->comment?>
                    <br>
                    <b><span>Par <?=$commenter->username?></span>, le <time datetime="<?=$commentTime?>"><?=$commentTimeHumanReadable?></time></b>
                </div>
                <?php if($userId == $comment->id_user){ ?>
                <footer class="tile is-1 is-child">
                    <a  href="?user=<?=$proposerId?>&propositionId=<?=$propositionId?>&alreadyVoted=<?=$alreadyVoted?>&action=deleteComment&commentToDelete=<?=$comment->id_comment?>" 
                        class="card-footer-item">Delete</a>
                </footer>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</section>
