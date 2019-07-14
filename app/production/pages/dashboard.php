<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";
require_once __DIR__."/../inc/functions.inc.php";
$displayMsg = "";
if(!empty($_GET["action"])){
    $action = $_GET["action"];
    if($action === "delete" && isset($_GET["proposition"])){
        if(deleteProposition($userId, $_GET["proposition"]) !== 1){
            $displayMsg = "Vous ne pouvez supprimer que vos propres propositions";
        }
    }
    if($action === "propositionUpdateFailed"){
        $displayMsg = "Vous ne pouvez modifier que vos propres propositions";
    }
    if($action === "propositionUpdated"){
        $displayMsg = "Proposition modifiée";
    }
    if($action === "voteFor" && isset($_GET["proposition"])){
        voteForProposition($userId, $_GET["proposition"]);
        $displayMsg = "Vous avez voté pour cette proposition";
    }
    if($action === "voteAgainst" && isset($_GET["proposition"])){
        voteAgainstProposition($userId, $_GET["proposition"]);
        $displayMsg = "Vous avez voté contre cette proposition";
    }
}

$userPropositions = getUserPropositions($userId);
$votedPropositions = getPropositionsSubmittedForVote();
?>

<section>
    <div class="container">
        <h1>Bonjour Emir!</h1>
        <hr>
        <div><a href="../index.php?action=disconnect">Se déconnecter</a></div>
        <hr>
        <h3>VOS PROPOSITIONS</h3>
        <div>Nombre de propositions : <?=count($userPropositions)?></div>
        <a href="proposition_create.php" class="button is-primary is-focused">Ajouter une proposition</a>
        <?php if($displayMsg != ""){ ?>
        <div class="box"><div class="notification is-primary"><?= $displayMsg ?></div></div>
        <?php } ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Soumise au vote</th>
                    <th>Pour</th>
                    <th>Contre</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Soumise au vote</th>
                    <th>Pour</th>
                    <th>Contre</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </tfoot>
            <tbody>
            <?php foreach($userPropositions as $proposition){ 
                    $propositionValidated = $proposition->date_valid != NULL; ?>
                <tr>
                    <th><?=$proposition->id_prop?></th>
                    <td><?=$proposition->title?></td>
                    <td><?=($propositionValidated ? "oui" : "non")?></td>
                    <td><?=$proposition->nbPour?></td>
                    <td><?=$proposition->nbContre?></td>

                    <?php if(!$propositionValidated){ ?>
                        <td><a href="proposition_modify.php?proposition=<?=$proposition->id_prop?>" class="button is-link">Modifier</a></td>
                    <?php }else{ ?>
                        <td><a href="proposition_modify.php?proposition=<?=$proposition->id_prop?>&disabled=1" class="button is-link">Voir</a></td>
                    <?php } ?>
                    <td><a href="?action=delete&proposition=<?=$proposition->id_prop?>" class="button is-danger">Supprimer</a></td>

                </tr>
            <?php } ?>
            </tbody>
        </table>

        <hr>
        <h3>Propositions soumises au vote</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Titre</th>
                    <th>Vous avez déjà voté</th>
                    <th>Pour</th>
                    <th>Contre</th>
                    <th>Voir</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($votedPropositions as $votedProposition){ 
                    $proposer = getUserById($votedProposition->id_user);
                    $userVotedForThisProposition = getVotedStatusForPropositionByUser($userId, $votedProposition->id_prop); ?>
                    <tr>
                        <th><?=$votedProposition->id_prop?></th>
                        <td><?=$proposer->pseudo?></td>
                        <td><?=$votedProposition->title?></td>
                        <td><?=($userVotedForThisProposition ? "déjà voté" : "")?></td>
                        <td><?=$votedProposition->nbPour?></td>
                        <td><?=$votedProposition->nbContre?></td>
                        <td>
                            <a href="proposition_display.php?user=<?=$votedProposition->id_user?>&propositionId=<?=$votedProposition->id_prop?>&alreadyVoted=<?=$userVotedForThisProposition?>" 
                            class="button is-link">Voir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>