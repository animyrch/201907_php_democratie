<?php
require_once __DIR__."/../inc/session.inc.php";
require_once __DIR__."/../inc/header.inc.php";

if(empty($_SESSION["userId"]) || !$_SESSION["connected"]){
    header("Location: login.php");
}
?>
        <h3>Ecran détails de la proposition</h3>
        <div class="box">
        <article class="media">
            <div class="media-content">
            <div class="content">
                <p>
                <strong>John Smith</strong> <small>@johnsmith</small> <small>31m</small>
                <br>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean efficitur sit amet massa fringilla egestas. Nullam condimentum luctus turpis.
                </p>
            </div>
            </div>
        </article>
        </div>