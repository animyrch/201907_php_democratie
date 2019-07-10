<?php
require_once __DIR__."/db-connect.inc.php";



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

function createUser($username, $mdp){
    if(empty($username)){
        return throwError("invalidUsername");
    }
    if(empty($mdp)){
        return throwError("emptyPassword");
    }

    global $db;
    
    $query = $db->prepare('INSERT INTO user (pseudo, mdp) VALUES (:pseudo, :mdp)');
    $params = array("pseudo" => $username, "mdp" => $mdp);
    $query->execute($params);

    return $db->lastInsertId();
}

function deleteUser($username, $mdp){
    if(empty($username)){
        return throwError("invalidUsername");
    }
    if(empty($mdp)){
        return throwError("emptyPassword");
    }

    global $db;
    $query = $db->prepare('DELETE FROM user WHERE pseudo = :pseudo AND mdp = :mdp LIMIT 1');
    $params = array("pseudo" => $username, "mdp" => $mdp);
    $query->execute($params);
    
    return $query->rowCount();
}

function getUserNameById(){
    return 1;
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

    $query = $db->prepare('INSERT INTO proposition (`id_user`, `title`, `contenu`, `nbPour`, `nbContre`, `date_valid`) VALUES (:userId, :title, :contenu, 1, 0, NULL);');
    $params = array('userId' => $userId, 'title' => $title, 'contenu' => $content);
    $query->execute($params);

    $propositionId = (integer) $db->lastInsertId();

    if(!invalidId($propositionId)){
        $query = $db->prepare('INSERT INTO voter (`id_user`, `id_prop`) VALUES (:userId, :propositionId);');
        $params = array('userId' => $userId, 'propositionId' => $propositionId);
        $query->execute($params);
    }


    return $propositionId;
}

function deleteProposition($userId, $propositionId){
    
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }
    // debug($userId);
    // debug($propositionId);
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

function getPropositionsSubmittedForVote(){
    global $db;
    $query = $db->prepare("SELECT * FROM proposition WHERE `date_valid` IS NOT NULL");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
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

/**************** VOTING CRUD *************************/

function getVotedStatusForPropositionByUser($userId, $propositionId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }

    global $db;
    $query = $db->prepare("SELECT * FROM voter WHERE id_user = :userId AND id_prop = :propositionId LIMIT 1");
    $params = array("userId" => $userId, "propositionId" => $propositionId);
    $query->execute($params);
    $result = $query->fetch(PDO::FETCH_OBJ);
    // var_dump($result);
    return count($result) === 1;
}

/**************** UTILITY FUNCTIONS *************************/
function debug($elem){
    echo "<pre>";
    var_dump($elem);
    echo "</pre>";
}

function invalidId($userId){
    return (empty($userId) || $userId < 1);
    //TODO check if full digit
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
    return $resultsArray[$type];
    
}
?>