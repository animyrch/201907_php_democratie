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
    
    $query = $db->prepare('SELECT * FROM user WHERE pseudo = :pseudo');
    $params = array("pseudo" => $username);
    $query->execute($params);
    $user = $query->fetch(PDO::FETCH_OBJ);
    if($user && password_verify($mdp, $user->hashed_mdp)){
        return (integer) $user->id_user;
    }else{
        return throwError("invalidUsernameOrPassword");
    }
}

function createUser($username, $mdp, $email){
    if(empty($username)){
        return throwError("invalidUsername");
    }
    if(invalidEmail($email)){
        return throwError("invalidEmail");
    }
    if(empty($mdp)){
        return throwError("emptyPassword");
    }else{
        $mdp = password_hash($mdp, PASSWORD_BCRYPT, array('cost' => 12));
    }

    global $db;
    $userToken = createUniqueToken();
    $query = $db->prepare("INSERT INTO user (pseudo, hashed_mdp, e_mail, user_token) VALUES (:pseudo, :hashedmdp, :email, :userToken)");
    $params = array("pseudo" => $username, "hashedmdp" => $mdp, "email" => $email, "userToken" => $userToken);
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

    if(!invalidID(checkUser($username, $mdp))){

        global $db;
        
        $query = $db->prepare('DELETE FROM user WHERE pseudo = :pseudo LIMIT 1');
        $params = array("pseudo" => $username);
        $query->execute($params);
        
        return $query->rowCount();
    }else{
        return false;
    }
}

function getUserById($userId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }

    global $db;
    $query = $db->prepare("SELECT * FROM user WHERE `id_user` = :userId");
    $params = array("userId" => $userId);
    $query->execute($params);
    $userObject = $query->fetch(PDO::FETCH_OBJ);
    return $userObject;

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
    global $db;

    //checking that the proposition exists
    $propositionObject = getProposition($userId , $propositionId);
    if($propositionObject){
        
        //deleting related comments
        $query = $db->prepare("DELETE FROM commenter WHERE `id_prop` = :propositionId");
        $params = array("propositionId" => $propositionId);
        $query->execute($params);
        
        //deleting related votes
        $query = $db->prepare("DELETE FROM voter WHERE `id_prop` = :propositionId");
        $params = array("propositionId" => $propositionId);
        $query->execute($params);
        
        //deleting the proposition
        $query = $db->prepare("DELETE FROM proposition WHERE `id_user` = :userId AND `id_prop` = :propositionId LIMIT 1");
        $params = array("userId" => $userId, "propositionId" => $propositionId);
        $query->execute($params);
        return $query->rowCount();
        
    }else{
        return throwError("sqlError");
    }
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

function getPropositionsToVote(){
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

function userVotedForProposition($userId, $propositionId){
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
    return $result != false;
}

function voteForProposition($userId, $propositionId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }
    global $db;
    
    //first checking if the user has already voted
    $repeatVote = isRepeatVote($userId, $propositionId);
    //if the user hasn't already voted for that proposition
    if(!$repeatVote){
        $query = $db->prepare("INSERT INTO voter (id_user, id_prop) VALUES (:userId, :propositionId);");
        $params = array("userId" => $userId, "propositionId" => $propositionId);
        $query->execute($params);
        $rowCount = $query->rowCount();
    
        $query = $db->prepare("UPDATE proposition SET nbPour = nbPour+1;");
        $query->execute();
        $rowCount *= $query->rowCount();
        
        return $rowCount;
    }
    
    return $repeatVote;    
}

function voteAgainstProposition($userId, $propositionId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }
    global $db;

    //first checking if the user has already voted
    $repeatVote = isRepeatVote($userId, $propositionId);
    if(!$repeatVote){

        $query = $db->prepare("INSERT INTO voter (id_user, id_prop) VALUES (:userId, :propositionId);");
        $params = array("userId" => $userId, "propositionId" => $propositionId);
        $query->execute($params);
        $rowCount = $query->rowCount();

        $query = $db->prepare("UPDATE proposition SET nbContre = nbContre+1;");
        $query->execute();
        $rowCount *= $query->rowCount();
        
        return $rowCount;
    }

    return $repeatVote;
}

function isRepeatVote($userId, $propositionId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }

    global $db;

    $query = $db->prepare("SELECT * FROM voter where id_user = :userId AND id_prop = :propositionId;");
    $params = array("userId" => (string) $userId, "propositionId" => $propositionId);
    $query->execute($params);
    return $query->fetch(PDO::FETCH_OBJ);  
}



/**************** COMMENTS CRUD *************************/

function getComments($propositionId){
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }

    $commentObject = [];
    global $db;

    $query = $db->prepare('SELECT * FROM commenter WHERE `id_prop` = :propositionId');
    $query->execute(array('propositionId'=>$propositionId));
    $commentObject = $query->fetchAll(PDO::FETCH_OBJ);

    return $commentObject;
}

function createComment($userId, $propositionId, $comment){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($propositionId)){
        return throwError("invalidPropositionId");
    }
    if(empty($comment)){
        return throwError("invalidCommentContent");
    }
    $lastInsertID = "";
    global $db;

    $query = $db->prepare('INSERT INTO commenter (id_user, id_prop, comment) VALUES (:userId, :propositionId, :comment)');
    $params = array("userId" => $userId, "propositionId" => $propositionId, "comment" => $comment);
    $query->execute($params);
    $lastInsertID = $db->lastInsertId();

    return $lastInsertID;
}

function deleteComment($userId, $commentId){
    if(invalidId($userId)){
        return throwError("invalidUserId");
    }
    if(invalidId($commentId)){
        return throwError("invalidCommentId");
    }

    $rowCount = "";
    global $db;
    $query = $db->prepare('DELETE FROM commenter WHERE `id_user` = :userId AND `id_comment` = :commentId LIMIT 1');
    $params = array("userId" => $userId, "commentId" => $commentId);
    $query->execute($params);
    
    $rowCount = $query->rowCount();
    return $rowCount;
}



/**************** UTILITY FUNCTIONS *************************/
function debug($elem){
    echo "<pre>";
    var_dump($elem);
    echo "</pre>";
}
function createUniqueToken(){
    $token = bin2hex(random_bytes(16));
    return $token;
}
function invalidId($userId){
    return (empty($userId) || $userId < 1);
    //TODO check if full digit
}
function invalidEmail($email){
    return (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL));
}
function throwError($type){

    $resultsArray = [
        "emptyPassword" => -10,
        "invalidCommentContent" => -15,
        "invalidCommentId" => -18,
        "invalidEmail" => -19,
        "invalidPropositionContent" => -20,
        "invalidPropositionId" => -30,
        "invalidPropositionTitle" => -40,
        "invalidUserId" => -50,
        "invalidUsername" => -60,
        "invalidUsernameOrPassword" => -70,
        "sqlError" => -80,
    ];
    return $resultsArray[$type];
    
}
?>