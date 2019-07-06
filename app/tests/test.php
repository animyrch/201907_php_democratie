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