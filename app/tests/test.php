<?php
require_once __DIR__."/../production/inc/functions.inc.php";

if(true){
    echo "Tests start successful";
}
$errorResults = [];
/******************* testing login functionalities START ********************/

//check user should respond according to the following array
$correctUsername = "user1";
$correctPassword = "user1mdp";
$result1 = checkUser("wrongValue", "wrongValue"); //-3
$result2 = checkUser("", ""); // -1 - should first check for username
$result3 = checkUser("wrongValue", "user1mdp"); //-3
$result4 = checkUser("user1", "wrongValue"); //-3
$result5 = checkUser("user1", ""); // -2
$result6 = checkUser("user1", "user1mdp"); // -2

if($result1 !== 70){
    $errorContent = "checkUser doesn't verify username correctly";
    $errorNo = 1993;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result2 !== 60){
    $errorContent = "checkUser doesn't verify username first in case of empty values";
    $errorNo = 2891;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result3 !== 70){
    $errorContent = "checkUser doesn't verify username correctly";
    $errorNo = 2901;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result4 !== 70){
    $errorContent = "checkUser doesn't verify password correctly";
    $errorNo = 8391;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result5 !== 10){
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
$resultCreatePropositionWithEmptyTitle = createProposition("", $testContent, $testUser);
$resultCreatePropositionWithEmptyTitle2 = createProposition(null, $testContent, $testUser);
$resultCreatePropositionWithEmptyContent = createProposition($testTitle, "", $testUser);
$resultCreatePropositionWithEmptyContent2 = createProposition($testTitle, null, $testUser);
$resultCreatePropositionWithInvalidUserId = createProposition($testTitle, $testContent, "");
$resultCreatePropositionWithInvalidUserId2 = createProposition($testTitle, $testContent, null);
$resultCreatePropositionWithInvalidUserId3 = createProposition($testTitle, $testContent, -1);
//correct use
$resultCreateCorrectProposition = createProposition($testTitle, $testContent, $testUser);

if($resultCreatePropositionWithEmptyTitle !== 40){
    $errorContent = "empty title error is not detected during proposition creation";
    $errorNo = 1939;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyTitle2 !== 40){
    $errorContent = "null title error is not detected during proposition creation";
    $errorNo = 9189;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyContent !== 20){
    $errorContent = "empty contents error is not detected during proposition creation";
    $errorNo = 9183;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyContent2 !== 20){
    $errorContent = "null contents error is not detected during proposition creation";
    $errorNo = 1989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId !== 50){
    $errorContent = "empty user id error is not detected during proposition creation";
    $errorNo = 3891;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId2 !== 50){
    $errorContent = "null user id error is not detected during proposition creation";
    $errorNo = 9198;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId3 !== 50){
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
$resultGetPropositionWithInvalidUserId = getUserPropositions("");
$resultGetPropositionWithInvalidUserId2 = getUserPropositions(null);
$resultGetPropositionWithInvalidUserId3 = getUserPropositions(-1);
//correct use
$resultGetCorrectPropositions = getUserPropositions($testUser);

if($resultGetPropositionWithInvalidUserId !== 50){
    $errorContent = "empty user id is not detected during proposition read";
    $errorNo = 7813;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId2 !== 50){
    $errorContent = "null user id is not detected during proposition read";
    $errorNo = 8798;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId !== 50){
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
$resultDeletePropositionWithInvalidUserId = deleteProposition("", $testPropositionId);
$resultDeletePropositionWithInvalidPropositionId = deleteProposition($testUser, -1);

//correct use
$resultDeleteCorrectProposition = deleteProposition($testUser, $testPropositionId);

if($resultDeletePropositionWithInvalidUserId !== 50){
    $errorContent = "empty user id is not detected during proposition deletion";
    $errorNo = 7190;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeletePropositionWithInvalidPropositionId !== 30){
    $errorContent = "empty proposition id is not detected during proposition deletion";
    $errorNo = 9091;
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
$resultGetPropositionWithInvalidUserId = getProposition(-1, $testPropositionId);
$resultGetPropositionWithInvalidPropositionId = getProposition($testUser, "");
//correct use
$resultGetCorrectProposition = getProposition($testUser, $testPropositionId);

if($resultGetPropositionWithInvalidUserId !== 50){
    $errorContent = "empty user id is not detected during proposition read";
    $errorNo = 8954;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidPropositionId !== 30){
    $errorContent = "empty proposition id is not detected during proposition read";
    $errorNo = 8788;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if(!$resultGetCorrectProposition){
    $errorContent = "correct proposition object was not created";
    $errorNo = 8938;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetCorrectProposition->title != $testTitle){
    $errorContent = "correct proposition title was not found";
    $errorNo = 3452;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetCorrectProposition->contenu != $testContent){
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
$resultUpdatePropositionWithEmptyUserId = updateProposition("", $testPropositionId, $newTestTitle, $newTestContent);
$resultUpdatePropositionWithEmptyPropositionId = updateProposition($testUser, "", $newTestTitle, $newTestContent);
$resultUpdatePropositionWithEmptTitle = updateProposition($testUser, $testPropositionId, "", $newTestContent);
$resultUpdatePropositionWithEmptContent = updateProposition($testUser, $testPropositionId, $newTestTitle, "");

// //correct use
$resultUpdateCorrectProposition = updateProposition($testUser, $testPropositionId, $newTestTitle, $newTestContent);
if($resultUpdatePropositionWithEmptyUserId !== 50){
    $errorContent = "incorrect user id is not detected during proposition update";
    $errorNo = 6474;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptyPropositionId !== 30){
    $errorContent = "incorrect proposition id is not detected during proposition update";
    $errorNo = 9573;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptTitle !== 40){
    $errorContent = "empty title is not detected during proposition update";
    $errorNo = 3742;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptContent !== 20){
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
if($newPropositionContents->title !== $newTestTitle){
    $errorContent = "the proposition title was not correctly updated";
    $errorNo = 5345;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));    
}
if($newPropositionContents->contenu !== $newTestContent){
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
if($resultSubmitPropositionWithEmptyUserId !== 50){
    $errorContent = "incorrect user id is not detected during proposition submission to vote";
    $errorNo = 3421;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultSubmitPropositionWithEmptyPropositionId !== 30){
    $errorContent = "incorrect proposition id is not detected during proposition submission to vote";
    $errorNo = 9289;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCorrectSubmitProposition !== 1){
    $errorContent = "the proposition content was not correctly submitted to vote";
    $errorNo = 9249;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
$newPropositionContents = getProposition($testUser, $testPropositionId);
if($newPropositionContents->date_valid === ""){
    $errorContent = "the proposition content was not correctly submitted to vote";
    $errorNo = 5345;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));    
}

//checking that a proposition can no longer be edited after submission to vote but it can still be deleted
$newTestTitle = "new test title";
$newTestContent = "new test content for chcking that update is disabled after submission to vote";
$resultUpdatePropositionDisabled = updateProposition($testUser, $testPropositionId, $newTestTitle, $newTestContent);

if($resultUpdatePropositionDisabled === 1){
    $errorContent = "the proposition already submitted to vote is incorrectly updated";
    $errorNo = 2312;
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