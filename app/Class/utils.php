<?php 

function throwErrorCode($type){

    $resultsArray = [
        "emptyPassword" => -10,
        "invalidAccountState" => -13,
        "invalidCommentContent" => -15,
        "invalidCommentId" => -18,
        "invalidEmail" => -19,
        "invalidPropositionContent" => -20,
        "invalidPropositionId" => -30,
        "invalidPropositionTitle" => -40,
        "invalidToken" => -45,
        "invalidUserId" => -50,
        "invalidUsername" => -60,
        "invalidUsernameOrPassword" => -70,
        "sqlError" => -80,
    ];
    return $resultsArray[$type];

}

function createToken(){
    $token = bin2hex(random_bytes(16));
    return $token;
}

function idInvalid($userId){
    return (empty($userId) || $userId < 1);
    //TODO check if full digit
}
function emailInvalid($email){
    return (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL));
}

function debug($elem){
    echo "<pre>";
    var_dump($elem);
    echo "</pre>";
}