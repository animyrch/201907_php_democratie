<?php
require_once __DIR__."/../../../../includes/config/env.php";

try{
    $db = new PDO("mysql:host=$_DBHOST; dbname=$_DBNAME;", "$_DBUSER", "$_DBMDP");
}catch(PDOException $e){
    echo "Erreur!: " . $e->getMessage() . "<br/>";
    die();
}