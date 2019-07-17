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

if($result1 !== -70){
    $errorContent = "checkUser doesn't verify username correctly";
    $errorNo = 1993;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result2 !== -60){
    $errorContent = "checkUser doesn't verify username first in case of empty values";
    $errorNo = 2891;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result3 !== -70){
    $errorContent = "checkUser doesn't verify username correctly";
    $errorNo = 2901;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result4 !== -70){
    $errorContent = "checkUser doesn't verify password correctly";
    $errorNo = 8391;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($result5 !== -10){
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

if($resultCreatePropositionWithEmptyTitle !== -40){
    $errorContent = "empty title error is not detected during proposition creation";
    $errorNo = 1939;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyTitle2 !== -40){
    $errorContent = "null title error is not detected during proposition creation";
    $errorNo = 9189;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyContent !== -20){
    $errorContent = "empty contents error is not detected during proposition creation";
    $errorNo = 9183;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithEmptyContent2 !== -20){
    $errorContent = "null contents error is not detected during proposition creation";
    $errorNo = 1989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId !== -50){
    $errorContent = "empty user id error is not detected during proposition creation";
    $errorNo = 3891;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId2 !== -50){
    $errorContent = "null user id error is not detected during proposition creation";
    $errorNo = 9198;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultCreatePropositionWithInvalidUserId3 !== -50){
    $errorContent = "incorrect user id error is not detected during proposition creation";
    $errorNo = 8938;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($resultCreateCorrectProposition < 1){
    $errorContent = "a new proposition is not correctly created";
    $errorNo = 9893;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}



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

if($resultGetPropositionWithInvalidUserId !== -50){
    $errorContent = "empty user id is not detected during proposition read";
    $errorNo = 7813;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId2 !== -50){
    $errorContent = "null user id is not detected during proposition read";
    $errorNo = 8798;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidUserId !== -50){
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
$resultDeleteCorrectProposition = deleteProposition($testUser, $testPropositionId); //TODO this needs to be used by after deleting votes for the proposition

if($resultDeletePropositionWithInvalidUserId !== -50){
    $errorContent = "empty user id is not detected during proposition deletion";
    $errorNo = 7190;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeletePropositionWithInvalidPropositionId !== -30){
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

if($resultGetPropositionWithInvalidUserId !== -50){
    $errorContent = "empty user id is not detected during proposition read";
    $errorNo = 8954;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetPropositionWithInvalidPropositionId !== -30){
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
if($resultUpdatePropositionWithEmptyUserId !== -50){
    $errorContent = "incorrect user id is not detected during proposition update";
    $errorNo = 6474;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptyPropositionId !== -30){
    $errorContent = "incorrect proposition id is not detected during proposition update";
    $errorNo = 9573;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptTitle !== -40){
    $errorContent = "empty title is not detected during proposition update";
    $errorNo = 3742;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultUpdatePropositionWithEmptContent !== -20){
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
if($resultSubmitPropositionWithEmptyUserId !== -50){
    $errorContent = "incorrect user id is not detected during proposition submission to vote";
    $errorNo = 3421;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultSubmitPropositionWithEmptyPropositionId !== -30){
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

//getting voted propositions from all user to further voting by users who have not voted yet
$hasError = false;
$noError = true;
$propositionsAvailableForVoting = getPropositionsToVote();
$votedPropositionContainer = array();
if(!is_array($propositionsAvailableForVoting)){
    $errorContent = "voted propositions are not correctly gathered ";
    $errorNo = 6374;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}else{
    $votedPropositionContainer = $propositionsAvailableForVoting;
}

foreach($votedPropositionContainer as $votedProposition){
    // var_dump($votedProposition);
    if(property_exists($votedProposition, "date_valid")){
        $noError *= $votedProposition->date_valid != "";
    }else{
        $hasError = true;
    }
}

if($hasError || !$noError){
    $errorContent = "propositions not submitted to vote or malformed propositions were also gathered";
    $errorNo = 4864;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}


/******************* testing proposition Crud END ********************/






/******************* testing voting Crud START ********************/

//getting the voted status for a given proposition by a given user

$testTitleVoted = "Test Title  - to be voted";
$testContentVoted = "Test content for the testing getting voted status for a given proposition by a given user - User 2. To be voted";
$testUser = 2; //for User 2
$testPropositionIdVoted = createProposition($testTitleVoted, $testContentVoted, $testUser);
// var_dump($testTitleVoted, $testContentVoted, $testUser);
submitPropositionToVote($testUser, $testPropositionIdVoted);
//Checking if inneed, Upon creating a proposition, user automatically votes for their proposition.

$resultGetVotedStatusWithIncorrectUserid = userVotedForProposition(-2, $testPropositionIdVoted);
$resultGetVotedStatusWithIncorrectPropositionid = userVotedForProposition($testUser, null);
$resultGetVotedStatus = userVotedForProposition($testUser, $testPropositionIdVoted);
$resultGetVotedStatusForOtherUser = userVotedForProposition(1, $testPropositionIdVoted);
// var_dump($resultGetVotedStatusForVoted);

if($resultGetVotedStatusWithIncorrectUserid != -50){
    $errorContent = "empty user id error is not detected during getting voted status";
    $errorNo = 4752;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultGetVotedStatusWithIncorrectPropositionid != -30){
    $errorContent = "empty proposition id error is not detected during getting voted status";
    $errorNo = 8536;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if(!$resultGetVotedStatus){
    $errorContent = "voted proposition is indicated as unvoted";
    $errorNo = 3843;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
// debug(userVotedForProposition(100, $testPropositionIdVoted));
if($resultGetVotedStatusForOtherUser){
    $errorContent = "unvoted proposition is indicated as voted";
    $errorNo = 3843;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}


//voting for someone else's proposition - voteForProposition()
$testTitleVoted = "Test Title-VotebyOther";
$testContentVoted = "Test content for the testing vote for other user's propositions";
$testUser = 3; //for User 3

//creating proposition with user 3
$testPropositionId = createProposition($testTitleVoted, $testContentVoted, $testUser);

//checking that user 1 hasn't voted for it yet
if(userVotedForProposition(1, $testPropositionId)){
    $errorContent = "unwanted for vote found";
    $errorNo = 7391;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//getting the number of nbPour for that proposition
$nbPourAmountOld = getProposition($testUser, $testPropositionId)->nbPour;

//voting for that proposition with user 1
voteForProposition(1, $testPropositionId);

//checking that user1 has voted for it
if(!userVotedForProposition(1, $testPropositionId)){
    $errorContent = "searched for vote was not found";
    $errorNo = 3562;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//checking that that proposition's nbPour number increase by 1
$nbPourAmountNew = getProposition($testUser, $testPropositionId)->nbPour;

if($nbPourAmountOld != ($nbPourAmountNew-1)){
    $errorContent = "votedFor counter is not increased for the proposition that was voted for";
    $errorNo = 8782;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
//checking that user1 can't vote for or against that proposition anymore
voteForProposition(1, $testPropositionId);
$nbPourAmountNew2 = getProposition($testUser, $testPropositionId)->nbPour;

if( (integer) $nbPourAmountNew === (integer) ($nbPourAmountNew2-1)){
    $errorContent = "votedFor counter is increased again even though user had already voted for it";
    $errorNo = 4562;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//voting against someone else's proposition - voteAgainstProposition()
$testTitleVoted = "Test Title-VoteAgainstOther";
$testContentVoted = "Test content for the testing vote against other user's propositions";
$testUser = 2; //for User 2

//creating proposition with user 2
$testPropositionId = createProposition($testTitleVoted, $testContentVoted, $testUser);

//checking that user 3 hasn't voted for it yet
if(userVotedForProposition(3, $testPropositionId)){
    $errorContent = "unwanted against vote found";
    $errorNo = 5414;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//getting the number of nbContre for that proposition
$nbAgainstAmountOld = getProposition($testUser, $testPropositionId)->nbContre;

//voting against that proposition with user 3
voteAgainstProposition(3, $testPropositionId);

//checking that user3 has voted against it
if(!userVotedForProposition(3, $testPropositionId)){
    $errorContent = "searched against vote was not found";
    $errorNo = 1987;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//checking that that proposition's nbContre number increase by 1
$nbAgainstAmountNew = getProposition($testUser, $testPropositionId)->nbContre;

if($nbAgainstAmountOld != ($nbAgainstAmountNew-1)){
    $errorContent = "votedAgainst counter is not increased for the proposition that was voted against";
    $errorNo = 2989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//checking that user3 can't vote for or against that proposition anymore
voteAgainstProposition(3, $testPropositionId);
$nbAgainstAmountNew2 = getProposition($testUser, $testPropositionId)->nbContre;
if((integer) $nbAgainstAmountNew === (integer) ($nbAgainstAmountNew2-1)){
    $errorContent = "votedAgainst counter is increased again even though user had already voted for it";
    $errorNo = 7389;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
/******************* testing voting Crud END ********************/





/******************* testing user Crud START ********************/
//user creation
$testPseudo = "newPseudo";
$testMDp = "FZEJ232";

$newUserId = createUser($testPseudo, $testMDp);
$verificationId = checkUser($testPseudo, $testMDp);

if($newUserId != $verificationId){
    $errorContent = "user was not created correctly";
    $errorNo = 2870;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//user deletion
deleteUser($testPseudo, $testMDp);
$result = checkUser($testPseudo, $testMDp);

if($result != -70){
    $errorContent = "user was not deleted correctly";
    $errorNo = 2898;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//getting a pseudo by id
$testPseudo = "FJZEHOf";
$testMDp = "FZ2432";
$newUserId = createUser($testPseudo, $testMDp);

$userInfo = getUserById($newUserId);

if($testPseudo != $userInfo->pseudo){
    $errorContent = "correct pseudo was not found";
    $errorNo = 3918;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
deleteUser($testPseudo, $testMDp);

/******************* testing user Crud END ********************/





/******************* testing comment Crud START ********************/

//testing comment creation
$propTitleForComment = "Test Title-forComment";
$propContentForComment = "Test content for the testing commenting system";
$testUser = 1; //for User 1
$testPropId = createProposition($propTitleForComment, $propContentForComment, $testUser);
$testCommentContent = "This is a comment for testing comment system";

//Error managements
$testCommentIdIncorrectUser = createComment(null, $testPropId, $testCommentContent);
$testCommentIdIncorrectProp = createComment($testUser, -30, $testCommentContent);
$testCommentIdIncorrectComment = createComment($testUser, $testPropId, "");
$testCommentId = createComment($testUser, $testPropId, $testCommentContent);

if($testCommentIdIncorrectUser !== -50){
    $errorContent = "incorrect user id is not detected during comment creation";
    $errorNo = 4183;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($testCommentIdIncorrectProp !== -30){
    $errorContent = "incorrect proposition id is not detected during comment creation";
    $errorNo = 4183;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($testCommentIdIncorrectComment !== -15){
    $errorContent = "incorrect comment is not detected during comment creation";
    $errorNo = 4183;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//correct use
if(invalidId($testCommentId)){
    $errorContent = "comment was not created";
    $errorNo = 8983;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//testing getting comments for a proposition
$propTitleForComment = "Test Title-forComment";
$propContentForComment = "Test content for the testing gathering all comments for a given proposition";
$creatingUser = 3; //for User 3
$testPropId = createProposition($propTitleForComment, $propContentForComment, $creatingUser);
$commentUser1 = 3;
$commentUser2 = 2;
$testCommentContentUser1 = "This is a comment from User 3 for testing comment gathering system and its their own proposition";
$testCommentContentUser2 = "This is a comment from User 2 for testing comment gathering system";
$commentFromUser1 = createComment($commentUser1, $testPropId, $testCommentContentUser1);
$commentFromUser2 = createComment($commentUser2, $testPropId, $testCommentContentUser2);
// debug($commentFromUser1);

$commentObjectsArrayIncorrectProp = getComments(null);
$commentObjectsArray = getComments($testPropId);
if($commentObjectsArrayIncorrectProp !== -30){
    $errorContent = "incorrect proposition id is not detected during comment gathering";
    $errorNo = 9893;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

$commentMatch = false;
foreach($commentObjectsArray as $key => $comment){
    if($comment->id_comment == $commentFromUser1){
        $commentMatch = true;
    }
}
if(!$commentMatch){
    $errorContent = "comment of the first user was not found";
    $errorNo = 8298;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
$commentMatch = false;
foreach($commentObjectsArray as $key => $comment){
    if($comment->id_comment === $commentFromUser2){
        $commentMatch = true;
    }
}
if(!$commentMatch){
    $errorContent = "comment of the second user was not found";
    $errorNo = 9382;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}


//testing comment deletion

$propTitleForComment = "Test Title-deleteComment";
$propContentForComment = "Test content for the testing comment deletion";
$testUser = 1; //for User 1
$testPropId = createProposition($propTitleForComment, $propContentForComment, $testUser);
$testCommentToDelete = "This is a comment to be deleted for testing comment system";
$testCommentId = createComment($testUser, $testPropId, $testCommentToDelete);

//error management
$resultDeleteCommentInvalidUser = deleteComment("", $testCommentId);
$resultDeleteCommentInvalidComment = deleteComment($testUser, null);

//correct use
$resultDeleteComment = deleteComment($testUser, $testCommentId); 

if($resultDeleteCommentInvalidUser !== -50){
    $errorContent = "incorrect user id is not detected during comment deletion";
    $errorNo = 9289;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}
if($resultDeleteCommentInvalidComment !== -18){
    $errorContent = "incorrect comment id is not detected during comment deletion";
    $errorNo = 9489;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

if($resultDeleteComment !== 1){
    $errorContent = "comment deletion has encountered an error";
    $errorNo = 3989;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

$resultGetCorrectComments = getComments($testUser);
$commentMatch = false;
foreach($resultGetCorrectComments as $key => $currentComment){

    if($currentComment->id_comment === $testCommentId){
        $titleMatch = true;
    }

}
if($propositionMatch){
    $errorContent = "correct comment was not deleted";
    $errorNo = 8278;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

//checking that users can only delete their own comments from propositions

$propTitleForComment = "Test-deleteOthersComment";
$propContentForComment = "Test content for the testing comment deletion of other uers which should be impossible";
$creatingUser = 1; //for User 1
$commentingUser = 2; //for User 2
$deletingUser = 3; //for User 3
$testPropId = createProposition($propTitleForComment, $propContentForComment, $creatingUser);
$testCommentToDelete = "This is a comment to be deleted for testing comment system, specifically comments of other users which should be impossible";
$testCommentId = createComment($commentingUser, $testPropId, $testCommentToDelete);
$result = deleteComment($deletingUser, $testCommentId);

if($result === 1){
    $errorContent = "a user was able to delete someone else's comment";
    $errorNo = 8773;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));   
}

$commentObjectsArray = getComments($testPropId);
$commentMatch = false;
foreach($commentObjectsArray as $key => $comment){
    if($comment->id_comment === $testCommentId){
        $commentMatch = true;
    }
}
if(!$commentMatch){
    $errorContent = "comment was not found after a user who has not created it tried to delete it ";
    $errorNo = 3631;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $errorContent));
}

/******************* testing comment Crud END ********************/


















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