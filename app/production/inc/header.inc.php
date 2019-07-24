<?php
// session_start();
require_once __DIR__."/db-connect.inc.php";
// require_once __DIR__."/inc/header.inc.php";
require_once __DIR__."/functions.inc.php";
?>
<!DOCTYPE HTML>
<html lang="fr">
<html>
<head>
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css" integrity="sha256-vK3UTo/8wHbaUn+dTQD0X6dzidqc5l7gczvH+Bnowwk=" crossorigin="anonymous" />
  <link rel="stylesheet" href="/201907_php_democratie/app/production/css/main.css" />
</head>
<body>
<header>
<section class="hero is-primary is-bold">
  <div class="container">
    <img class="is-rounded" src="/201907_php_democratie/app/production/assets/Logo_JFVD.png">
      <?php if(!empty($userId)){ ?>
        <h1 class="is-1 title">
          <?="Bonjour ".$userObject->username?>
        </h1>
        <h2 class="is-2 subtitle">
          <div><a class="button is-info is-inverted is-outlined" href="../index.php?action=disconnect">Se déconnecter</a></div>
        </h2>
      <?php }else{ ?>
        <div class="welcome-message">
          <h1 class="title">
            Bienvenu chez Democratie 2.0 !
          </h1>
          <h2 class="subtitle">
            Connectez-vous avec votre compte ou en créer un nouveau !!!
          </h2>
        </div>
      <?php } ?>
  </div>
</section>
<?php
//echo '<img src="'.__DIR__.'\..\assets\votelogo.jpg">';
?>
</header>