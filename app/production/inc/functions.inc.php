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
    if(invalidId($userId)){
        return $resultsArray["invalidUserId"];
    }

    global $db;

    $query = $db->prepare('INSERT INTO proposition (`id_user`, `title`, `contenu`, `nbPour`, `nbContre`, `date_valid`, `date_create`) VALUES (:userId, :title, :contenu, 1, 0, NULL, :today);');
    $params = array('userId' => $userId, 'title' => $title, 'contenu' => $content, 'today' => date("Y-m-d"));
    $query->execute($params);
    return (integer) $db->lastInsertId();
}

function deleteProposition($userId, $propositionId){
    $resultsArray = ["invalidUserId" => -1, "invalidPropositionId" => -2];
    
    if(invalidId($userId)){
        return $resultsArray["invalidUserId"];
    }
    if(invalidId($propositionId)){
        return $resultsArray["invalidPropositionId"];
    }

    global $db;
    $query = $db->prepare("DELETE FROM proposition WHERE `id_user` = :userId AND `id_prop` = :propositionId LIMIT 1");
    $params = array("userId" => $userId, "propositionId" => $propositionId);
    $query->execute($params);
    return $query->rowCount();
}

function getProposition($userId , $propositionId){
    $statusCode = 0;
    $resultsArray = ["success" => 0, "invalidUserId" => -1, "invalidPropositionId" => -2];
    if(invalidId($userId)){
        $statusCode =  $resultsArray["invalidUserId"];
    }
    if(invalidId($propositionId)){
        $statusCode = $resultsArray["invalidPropositionId"];
    }
    if($statusCode !== 0){
        return array($statusCode, "", "");
    }
    
    $statusCode = $resultsArray["success"];
    global $db;
    $query = $db->prepare("SELECT * FROM proposition WHERE  `id_user` = :userId AND `id_prop` = :propositionId");
    $params = array("userId" => $userId, "propositionId" => $propositionId);
    $query->execute($params);
    $resultObject = $query->fetch(PDO::FETCH_OBJ);
    return array($statusCode, $resultObject);
}

function getUserPropositions($userId){
    $resultsArray = ["invalidUserId" => -1];

    if(invalidId($userId)){
        return $resultsArray["invalidUserId"];
    }
    global $db;

    $query = $db->prepare('SELECT * FROM proposition WHERE `id_user` = :userId');
    $query->execute(array('userId'=>$userId));
    return $query->fetchAll(PDO::FETCH_OBJ);
}

function updateProposition($userId, $propositionId, $title, $contenu){
    $resultsArray = ["success" => 0, "emptyTitle" => -1, "emptyContent" => -2, "invalidUserId" => -3, "invalidPropositionId" => -4];

    if(empty($title)){
        return $resultsArray["emptyTitle"];
    }
    if(empty($contenu)){
        return $resultsArray["emptyContent"];
    }
    if(invalidId($userId)){
        return $resultsArray["invalidUserId"];
    }
    if(invalidId($propositionId)){
        return $resultsArray["invalidPropositionId"];
    }

    global $db;
    $query = $db->prepare("UPDATE proposition SET `title` = :title, `contenu` = :contenu WHERE `id_user` = :userId AND `id_prop` = :propositionId AND `date_valid` IS NULL;");
    
    $params = array("userId" => $userId, "propositionId" => $propositionId, "title" => $title, "contenu" => $contenu);
    $query->execute($params);
    return $query->rowCount();
}

function submitPropositionToVote($userId, $propositionId){
    $resultsArray = ["success" => 0, "invalidUserId" => -1, "invalidPropositionId" => -2, "sqlError" => -3];
    if(invalidId($userId)){
        return $resultsArray["invalidUserId"];
    }
    if(invalidId($propositionId)){
        return $resultsArray["invalidPropositionId"];
    }

    global $db;
    $query = $db->prepare("UPDATE proposition SET `date_valid` = :dateValid WHERE id_prop = :propositionId AND id_user = :userId");
    $params = array("dateValid" => date("Y-m-d"), "userId" => $userId, "propositionId" => $propositionId);
    $query->execute($params);

    if($query->rowCount() == 1){
        return $resultsArray["success"];
    }else{
        return $resultsArray["sqlError"];
    }
}
/**************** UTILITY FUNCTIONS *************************/
function invalidId($userId){
    return (empty($userId) || $userId < 1);
}
?>