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
}

$userPropositions = getUserPropositions($userId);
?>

<section>
    <div class="container">
        <h1>Bonjour Emir!</h1>
        <hr>
        <div><a href="../index.php?action=disconnect">Se déconnecter</a></div>
        <hr>
        <h3>VOS PROPOSITIONS</h3>
        <div>Nombre de propositions : <?=count($userPropositions)?></div>
        <?php if($displayMsg != ""){ ?>
        <div class="notification is-primary"><?= $displayMsg ?></div>
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
                        <td><a class="button is-link">Voir</a></td>
                    <?php } ?>
                    <td><a href="?action=delete&proposition=<?=$proposition->id_prop?>" class="button is-danger">Supprimer</a></td>

                </tr>
            <?php } ?>
            </tbody>
        </table>

        <a href="proposition_create.php" class="button is-primary is-focused">Ajouter une proposition</a>

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
                <tr>
                    <th>43</th>
                    <td>TEST</td>
                    <td>test titre</td>
                    <td>oui</td>
                    <td>2</td>
                    <td>3</td>
                    <td><a class="button is-link">Voir</a></td>
                </tr>
                <tr>
                    <th>43</th>
                    <td>TEST</td>
                    <td>test titre</td>
                    <td>oui</td>
                    <td>2</td>
                    <td>3</td>
                    <td><a class="button is-link">Voir</a></td>
                </tr>
                <tr>
                    <th>43</th>
                    <td>TEST</td>
                    <td>test titre</td>
                    <td>oui</td>
                    <td>2</td>
                    <td>3</td>
                    <td><a class="button is-link">Voir</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>