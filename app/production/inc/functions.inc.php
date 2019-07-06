<?php
require_once __DIR__."/db-connect.inc.php";

function debug($elem){
    echo "<pre>";
    var_dump($elem);
    echo "</pre>";
}

/**************** USER CRUD *************************/
function checkUser($username, $mdp){
    $resultsArray = ["emptyUsername" => -1, "emptyPassword" => -2, "wrongUsernameOrPassword" => -3];
    
    if(empty($username)){
        return $resultsArray["emptyUsername"];
    }
    if(empty($mdp)){
        return $resultsArray["emptyPassword"];
    }

    global $db;
    
    $query = $db->prepare('SELECT id_user FROM user WHERE pseudo = :pseudo AND mdp = :mdp');
    $params = array("mdp" => $mdp, "pseudo" => $username);
    $query->execute($params);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if($user){
        return (integer) $user["id_user"];
    }else{
        return $resultsArray["wrongUsernameOrPassword"];
    }
}


/**************** PROPOSITION CRUD *************************/

function createProposition($title, $content, $userId){
    $resultsArray = ["emptyTitle" => -1, "emptyContent" => -2, "invalidUserId" => -3];

    if(empty($title)){
        return $resultsArray["emptyTitle"];
    }
    if(empty($content)){
        return $resultsArray["emptyContent"];
    }
    if(empty($userId) || $userId < 1){
        return $resultsArray["invalidUserId"];
    }

    global $db;

    $query = $db->prepare('INSERT INTO proposition (`id_user`, `title`, `contenu`, `nbPour`, `nbContre`, `date_valid`, `date_create`) VALUES (:userId, :title, :contenu, 1, 0, NULL, :today);');
    $params = array('userId' => $userId, 'title' => $title, 'contenu' => $content, 'today' => date("Y-m-d"));
    $query->execute($params);
    return $query->rowCount();
}

function getUserPropositions($userId){
    $resultsArray = ["invalidUserId" => -1];

    if(empty($userId) || $userId < 1){
        return $resultsArray["invalidUserId"];
    }
    global $db;

    $query = $db->prepare('SELECT * FROM proposition WHERE `id_user` = :userId');
    $query->execute(array('userId'=>$userId));
    return $query->fetchAll(PDO::FETCH_OBJ);
}
?>