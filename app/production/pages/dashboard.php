<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";
if(empty($_SESSION["userId"]) || !$_SESSION["connected"]){
    header("Location: login.php");
}
?>

<section>
    <div class="container">
        <h1>Bonjour Emir!</h1>
        <hr>
        <div><a href="../index.php?action=disconnect">Se déconnecter</a></div>
        <hr>
        <h3>VOS PROPOSITIONS</h3>
        <div>Nombre de propositions : 1</div>

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
                <tr>
                    <th>43</th>
                    <td>TEST</td>
                    <td>oui</td>
                    <td>1</td>
                    <td>2</td>
                    <td><a class="button is-link">modifier</a></td>
                    <td><a class="button is-danger">supprimer</a></td>
                </tr>
                <tr>
                    
                <th>43</th>
                    <td>TEST</td>
                    <td>oui</td>
                    <td>1</td>
                    <td>2</td>
                    <td><a class="button is-link">modifier</a></td>
                    <td><a class="button is-danger">supprimer</a></td>
                </tr>
            </tbody>
        </table>

        <a class="button is-primary is-focused">Ajouter une proposition</a>

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