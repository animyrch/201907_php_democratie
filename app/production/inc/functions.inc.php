<?php
require_once __DIR__."/db-connect.inc.php";

function debug($elem){
    echo "<pre>";
    var_dump($elem);
    echo "</pre>";
}

/**************** USER CRUD *************************/
function checkUser($username, $mdp){
    
    if(empty($username)){
        return throwError("invalidUsername");
    }
    if(empty($mdp)){
        return throwError("emptyPassword");
    }

    global $db;
    
    $query = $db->prepare('SELECT id_user FROM user WHERE pseudo = :pseudo AND mdp = :mdp');
    $params = array("mdp" => $mdp, "pseudo" => $username);
    $query->execute($params);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if($user){
        return (integer) $user["id_user"];
    }else{
        return throwError("invalidUsernameOrPassword");
    }
}


/**************** PROPOSITION CRUD *************************/

function createProposition($title, $content, $userId){

    if(empty($title)){
        return throwError("invalidPropositionTitle");
    }
    if(empty($content)){
        return throwError("invalidPropositionContent");
    }
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }

    global $db;

    $query = $db->prepare('INSERT INTO proposition (`id_user`, `title`, `contenu`, `nbPour`, `nbContre`, `date_valid`, `date_create`) VALUES (:userId, :title, :contenu, 1, 0, NULL, :today);');
    $params = array('userId' => $userId, 'title' => $title, 'contenu' => $content, 'today' => date("Y-m-d"));
    $query->execute($params);
    return (integer) $db->lastInsertId();
}

function deleteProposition($userId, $propositionId){
    
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }

    global $db;
    $query = $db->prepare("DELETE FROM proposition WHERE `id_user` = :userId AND `id_prop` = :propositionId LIMIT 1");
    $params = array("userId" => $userId, "propositionId" => $propositionId);
    $query->execute($params);
    return $query->rowCount();
}

function getProposition($userId , $propositionId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }
    
    global $db;
    $query = $db->prepare("SELECT * FROM proposition WHERE  `id_user` = :userId AND `id_prop` = :propositionId");
    $params = array("userId" => $userId, "propositionId" => $propositionId);
    $query->execute($params);
    $resultObject = $query->fetch(PDO::FETCH_OBJ);

    return $resultObject;
}

function getUserPropositions($userId){

    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    global $db;

    $query = $db->prepare('SELECT * FROM proposition WHERE `id_user` = :userId');
    $query->execute(array('userId'=>$userId));
    return $query->fetchAll(PDO::FETCH_OBJ);
}

function getVotedPropositions(){
    
}

function updateProposition($userId, $propositionId, $title, $contenu){

    if(empty($title)){
        return throwError("invalidPropositionTitle");
    }
    if(empty($contenu)){
        return throwError("invalidPropositionContent");
    }
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }

    global $db;
    $query = $db->prepare("UPDATE proposition SET `title` = :title, `contenu` = :contenu WHERE `id_user` = :userId AND `id_prop` = :propositionId AND `date_valid` IS NULL;");
    
    $params = array("userId" => $userId, "propositionId" => $propositionId, "title" => $title, "contenu" => $contenu);
    $query->execute($params);
    return $query->rowCount();
}

function submitPropositionToVote($userId, $propositionId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }

    global $db;
    $query = $db->prepare("UPDATE proposition SET `date_valid` = :dateValid WHERE id_prop = :propositionId AND id_user = :userId");
    $params = array("dateValid" => date("Y-m-d"), "userId" => $userId, "propositionId" => $propositionId);
    $query->execute($params);

    return $query->rowCount();
}
/**************** UTILITY FUNCTIONS *************************/
function invalidId($userId){
    return (empty($userId) || $userId < 1);
}

function throwError($type){

    $resultsArray = [
        "emptyPassword" => 10,
        "invalidPropositionContent" => 20,
        "invalidPropositionId" => 30,
        "invalidPropositionTitle" => 40,
        "invalidUserId" => 50,
        "invalidUsername" => 60,
        "invalidUsernameOrPassword" => 70,
        "sqlError" => 80,
    ];

    foreach($resultsArray as $errorType => $errorCode){
        if($errorType == $type){
            return $errorCode;
        }
    }
}
?>