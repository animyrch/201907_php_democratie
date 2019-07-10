<?php
require_once __DIR__."/../production/inc/functions.inc.php";

if(true){
    echo "Tests start successful";
}
$errorResults = [];
/******************* testing login functionalities START ********************/

//check user should respond according to the following array
// $resultsArray = ["emptyUsername" => -1, "emptyPassword" => -2, "wrongUsernameOrPassword" => -3];
$correctUsername = "user1";
$correctPassword = "user1mdp";
$result1 = checkUser("wrongValue", "wrongValue"); //-3
$result2 = checkUser("", ""); // -1 - should first check for username
$result3 = checkUser("wrongValue", "user1mdp"); //-3
$result4 = checkUser("user1", "wrongValue"); //-3
$result5 = checkUser("user1", ""); // -2
$result6 = checkUser("user1", "user1mdp"); // -2

if($result1 !== -3){
    $errorContent = "checkUser doesn't verify username correctly";
    $errorNo = 1993;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result2 !== -1){
    $errorContent = "checkUser doesn't verify username first in case of empty values";
    $errorNo = 2891;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result3 !== -3){
    $errorContent = "checkUser doesn't verify username correctly";
    $errorNo = 2901;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result4 !== -3){
    $errorContent = "checkUser doesn't verify password correctly";
    $errorNo = 8391;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result5 !== -2){
    $errorContent = "checkUser doesn't verify if password is empty";
    $errorNo = 1089;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result6 !== 1){
    $errorContent = "existing user was not found";
    $errorNo = 1903;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

/******************* testing login functionalities END ********************/


/******************* testing proposition Crud START ********************/

//Creating a proposition
$testTitle = "Test Title";
$testContent = "Test content for the test proposition with a test title from the test user User2.";
$testUser = 2; //for User2

//error management
// $resultsArray = ["emptyTitle" => -1, "emptyContent" => -2, "invalidUserId" => -3];
$resultCreatePropositionWithEmptyTitle = createProposition("", $testContent, $testUser);
$resultCreatePropositionWithEmptyTitle2 = createProposition(null, $testContent, $testUser);
$resultCreatePropositionWithEmptyContent = createProposition($testTitle, "", $testUser);
$resultCreatePropositionWithEmptyContent2 = createProposition($testTitle, null, $testUser);
$resultCreatePropositionWithInvalidUserId = createProposition($testTitle, $testContent, "");
$resultCreatePropositionWithInvalidUserId2 = createProposition($testTitle, $testContent, null);
$resultCreatePropositionWithInvalidUserId3 = createProposition($testTitle, $testContent, -1);
//correct use
$resultCreateCorrectProposition = createProposition($testTitle, $testContent, $testUser);

if($resultCreatePropositionWithEmptyTitle !== -1){
    $errorContent = "empty title error is not detected during proposition creation";
    $errorNo = 1939;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyTitle2 !== -1){
    $errorContent = "null title error is not detected during proposition creation";
    $errorNo = 9189;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyContent !== -2){
    $errorContent = "empty contents error is not detected during proposition creation";
    $errorNo = 9183;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyContent2 !== -2){
    $errorContent = "null contents error is not detected during proposition creation";
    $errorNo = 1989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId !== -3){
    $errorContent = "empty user id error is not detected during proposition creation";
    $errorNo = 3891;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId2 !== -3){
    $errorContent = "null user id error is not detected during proposition creation";
    $errorNo = 9198;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId3 !== -3){
    $errorContent = "incorrect user id error is not detected during proposition creation";
    $errorNo = 8938;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($resultCreateCorrectProposition < 1){
    $errorContent = "a new proposition is not correctly created";
    $errorNo = 9893;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//TODO - this should also generate a line in the table VOTER. 


//Reading propositions of a user
$testTitle = "A new proposition";
$testContent = "Test content for the new proposition during a proposition read test.";
$testUser = 1; //for User1
createProposition($testTitle, $testContent, $testUser);

//error management
// $resultsArray = ["invalidUserId" => -1];
$resultGetPropositionWithInvalidUserId = getUserPropositions("");
$resultGetPropositionWithInvalidUserId2 = getUserPropositions(null);
$resultGetPropositionWithInvalidUserId3 = getUserPropositions(-1);
//correct use
$resultGetCorrectPropositions = getUserPropositions($testUser);

if($resultGetPropositionWithInvalidUserId !== -1){
    $errorContent = "empty user id is not detected during proposition read";
    $errorNo = 7813;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId2 !== -1){
    $errorContent = "null user id is not detected during proposition read";
    $errorNo = 8798;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId !== -1){
    $errorContent = "incorrect user id is not detected during proposition read";
    $errorNo = 3513;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

$titleMatch = false;
$contentMatch = false;
foreach($resultGetCorrectPropositions as $key => $proposition){

    if($proposition->title === $testTitle){
        $titleMatch = true;
    }
    if($proposition->contenu === $testContent){
        $contentMatch = true;
    }

}
if(!$titleMatch){
    $errorContent = "correct proposition title was not present";
    $errorNo = 1989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if(!$titleMatch){
    $errorContent = "correct proposition content was not present";
    $errorNo = 2898;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//Deleting a proposition of a user
$testTitle = "A proposition to be deleted";
$testContent = "Test content for the new proposition during a proposition deletion test.";
$testUser = 2; //for User2
$testPropositionId = createProposition($testTitle, $testContent, $testUser);

//error management
// $resultsArray = ["invalidUserId" => -1, "invalidPropositionId" => -2];
$resultDeletePropositionWithInvalidUserId1 = deleteProposition("", $testPropositionId);
$resultDeletePropositionWithInvalidUserId2 = deleteProposition(null, $testPropositionId);
$resultDeletePropositionWithInvalidUserId3 = deleteProposition(-1, $testPropositionId);
$resultDeletePropositionWithInvaliPropositionId1 = deleteProposition($testUser, "");
$resultDeletePropositionWithInvaliPropositionId2 = deleteProposition($testUser, null);
$resultDeletePropositionWithInvaliPropositionId3 = deleteProposition($testUser, -1);

//correct use
$resultDeleteCorrectProposition = deleteProposition($testUser, $testPropositionId);

if($resultDeletePropositionWithInvalidUserId1 !== -1){
    $errorContent = "empty user id is not detected during proposition deletion";
    $errorNo = 7190;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeletePropositionWithInvalidUserId2 !== -1){
    $errorContent = "null user id is not detected during proposition read";
    $errorNo = 8989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeletePropositionWithInvalidUserId3 !== -1){
    $errorContent = "incorrect user id is not detected during proposition read";
    $errorNo = 8910;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeletePropositionWithInvaliPropositionId1 !== -2){
    $errorContent = "empty proposition id is not detected during proposition deletion";
    $errorNo = 9091;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeletePropositionWithInvaliPropositionId2 !== -2){
    $errorContent = "null proposition id is not detected during proposition read";
    $errorNo = 8928;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeletePropositionWithInvaliPropositionId3 !== -2){
    $errorContent = "incorrect proposition id is not detected during proposition read";
    $errorNo = 8711;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($resultDeleteCorrectProposition !== 1){
    $errorContent = "proposition deletion has encountered an error";
    $errorNo = 8945;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

$resultGetCorrectPropositions = getUserPropositions($testUser);
$propositionMatch = false;
foreach($resultGetCorrectPropositions as $key => $proposition){

    if($proposition->id_prop === $resultDeleteCorrectProposition){
        $titleMatch = true;
    }

}
if($propositionMatch){
    $errorContent = "correct proposition was not deleted";
    $errorNo = 8791;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//reading a specific proposition
$testTitle = "A new proposition to get";
$testContent = "Test content for the new proposition to be targetted during a proposition read test.";
$testUser = 1; //for User1
$testPropositionId = createProposition($testTitle, $testContent, $testUser);

//error management
//$resultsArray = ["success" => 0, "invalidUserId" => -1, "invalidPropositionId" => -2];
$resultGetPropositionWithInvalidUserId = getProposition("", $testPropositionId);
$resultGetPropositionWithInvalidUserId2 = getProposition(null, $testPropositionId);
$resultGetPropositionWithInvalidUserId3 = getProposition(-1, $testPropositionId);
$resultGetPropositionWithInvalidPropositionId = getProposition($testUser, "");
$resultGetPropositionWithInvalidPropositionId2 = getProposition($testUser, null);
$resultGetPropositionWithInvalidPropositionId3 = getProposition($testUser, -1);
//correct use
$resultGetCorrectProposition = getProposition($testUser, $testPropositionId);

if($resultGetPropositionWithInvalidUserId[0] !== -1){
    $errorContent = "empty user id is not detected during proposition read";
    $errorNo = 8954;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId2[0] !== -1){
    $errorContent = "null user id is not detected during proposition read";
    $errorNo = 8983;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId[0] !== -1){
    $errorContent = "incorrect user id is not detected during proposition read";
    $errorNo = 7388;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidPropositionId[0] !== -2){
    $errorContent = "empty proposition id is not detected during proposition read";
    $errorNo = 8788;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidPropositionId2[0] !== -2){
    $errorContent = "null proposition id is not detected during proposition read";
    $errorNo = 3453;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidPropositionId[0] !== -2){
    $errorContent = "incorrect proposition id is not detected during proposition read";
    $errorNo = 3275;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($resultGetCorrectProposition[0] != 0){
    $errorContent = "correct response code was not given";
    $errorNo = 8938;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetCorrectProposition[1]->title != $testTitle){
    $errorContent = "correct proposition title was not found";
    $errorNo = 3452;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetCorrectProposition[1]->contenu != $testContent){
    $errorContent = "correct proposition content was not present";
    $errorNo = 3989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//Updating a proposition
$testTitle = "Test Title for update";
$testContent = "Test content for the test proposition update with a test title from the test user User2.";
$testUser = 2; //for User2
$testPropositionId = createProposition($testTitle, $testContent, $testUser);

$newTestTitle = "New title for test update";
$newTestContent = "This content is the updated version if the previous version";
//error management
// updateProposition($userId, $propositionId, $title, $contenu){
// $resultsArray = ["success" => 0, "emptyTitle" => -1, "emptyContent" => -2, "invalidUserId" => -3, "invalidPropositionId" => -4];
$resultUpdatePropositionWithEmptyUserId = updateProposition("", $testPropositionId, $newTestTitle, $newTestContent);
$resultUpdatePropositionWithEmptyPropositionId = updateProposition($testUser, "", $newTestTitle, $newTestContent);
$resultUpdatePropositionWithEmptTitle = updateProposition($testUser, $testPropositionId, "", $newTestContent);
$resultUpdatePropositionWithEmptContent = updateProposition($testUser, $testPropositionId, $newTestTitle, "");

// //correct use
// var_dump($testUser, $testPropositionId, $testTitle, $testContent);
$resultUpdateCorrectProposition = updateProposition($testUser, $testPropositionId, $newTestTitle, $newTestContent);
// var_dump($resultUpdateCorrectProposition);
if($resultUpdatePropositionWithEmptyUserId !== -3){
    $errorContent = "incorrect user id is not detected during proposition update";
    $errorNo = 6474;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptyPropositionId !== -4){
    $errorContent = "incorrect proposition id is not detected during proposition update";
    $errorNo = 9573;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptTitle !== -1){
    $errorContent = "empty title is not detected during proposition update";
    $errorNo = 3742;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptContent !== -2){
    $errorContent = "empty content is not detected during proposition creation";
    $errorNo = 2746;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($resultUpdateCorrectProposition !== 1){
    $errorContent = "the proposition is not correctly updated";
    $errorNo = 5743;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

$newPropositionContents = getProposition($testUser, $testPropositionId);
if($newPropositionContents[1]->title !== $newTestTitle){
    $errorContent = "the proposition title was not correctly updated";
    $errorNo = 5345;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));    
}
if($newPropositionContents[1]->contenu !== $newTestContent){
    $errorContent = "the proposition content was not correctly updated";
    $errorNo = 3578;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));    
}

//submitting a proposition to vote
$testTitle = "Test Title for submittoVote";
$testContent = "Test content for the test validate update for vote with a test title from the test user User2.";
$testUser = 2; //for User2
$testPropositionId = createProposition($testTitle, $testContent, $testUser);
$resultSubmitPropositionWithEmptyUserId = submitPropositionToVote(null, $testPropositionId);
$resultSubmitPropositionWithEmptyPropositionId = submitPropositionToVote($testUser, null);
$resultCorrectSubmitProposition = submitPropositionToVote($testUser, $testPropositionId);


//error management
//$resultsArray = ["success" => 0, "invalidUserId" => -1, "invalidPropositionId" => -2];
if($resultSubmitPropositionWithEmptyUserId !== -1){
    $errorContent = "incorrect user id is not detected during proposition submission to vote";
    $errorNo = 3421;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultSubmitPropositionWithEmptyPropositionId !== -2){
    $errorContent = "incorrect proposition id is not detected during proposition submission to vote";
    $errorNo = 9289;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCorrectSubmitProposition !== 0){
    $errorContent = "the proposition content was not correctly submitted to vote";
    $errorNo = 9289;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
$newPropositionContents = getProposition($testUser, $testPropositionId);
if($newPropositionContents[1]->date_valid === ""){
    $errorContent = "the proposition content was not correctly submitted to vote";
    $errorNo = 5345;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));    
}
/******************* testing proposition Crud END ********************/











if(empty($errorResults)){
    echo "<br><br>all PHP tests were succesfull";
}else{
    $result = "<br><br>some PHP tests were unsuccessfull ";
    foreach($errorResults as $content){

        $result .= "<br>".$content["errorno"]." : ".$content["errorcontent"]. "<br>";
    }
    echo $result;
}

?>