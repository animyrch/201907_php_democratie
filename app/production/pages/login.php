<?php
require_once __DIR__."/../inc/session.php";
require_once __DIR__."/../inc/header.inc.php";
?>

<form method="POST">

<div class="field">
  <label class="label" for="username">Votre pseudo</label>
  <div class="control has-icons-left has-icons-right">
    <input class="input" type="text" placeholder="Entrez votre pseudo" name="username">
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
    <button type="submit" class="button is-success">
      Entrez
    </button>
  </p>
</div>

</form>