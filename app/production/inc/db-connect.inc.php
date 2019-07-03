<?php
require_once __DIR__."/../../config/env.php";

try{
    $db = new PDO("mysql:host=$_DBHOST; dbname=$_DBNAME;", "$_DBUSER", "$_DBMDP");
    echo "connection successfull";
}catch(PDOException $e){
    echo "Erreur!: " . $e->getMessage() . "<br/>";
    die();
}