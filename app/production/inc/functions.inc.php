<?php
require_once __DIR__."/db-connect.inc.php";

function debug($elem){
    echo "<pre>";
    var_dump($elem);
    echo "</pre>";
}

function checkUser($username, $mdp){
    $resultsArray = ["emptyUsername" => -1, "emptyPassword" => -2, "wrongUsernameOrPassword" => -3];
    
    if($username == ""){
        return $resultsArray["emptyUsername"];
    }
    if($mdp == ""){
        return $resultsArray["emptyPassword"];
    }

    global $db;
    
    $query = $db->prepare('SELECT id FROM user WHERE pseudo = :pseudo AND mdp = :mdp');
    $params = array("mdp" => $mdp, "pseudo" => $username);
    $query->execute($params);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if($user){
        return (integer) $user["id"];
    }else{
        return $resultsArray["wrongUsernameOrPassword"];
    }
}
?>