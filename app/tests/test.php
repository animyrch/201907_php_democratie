<?php
require_once __DIR__."/../production/inc/functions.inc.php";

if(true){
    echo "Tests start successful";
}
$errorResults = [];
$testEmailWrong = "kokfoezfze@okfoezkfze";
$testEmail = "emirgokhanozcelik@gmail.com";
eraseDb();

/***********************************************************/
/******************* testing user Class ********************/
/***********************************************************/

require_once "../production/Class/User.php";

// debug($userObject->db);

/****************user create*********************/

$testPseudo = "newPseudo";
$testMDp = "FZEJ232";
$testEmailWrong = "FEZFZEFfefe";
$testEmail = "test@test.com";

//Error management
$newUserId = User::CreateUser($testPseudo, $testMDp, $testEmailWrong);

if($newUserId !== -19){
    addToResults("incorrect email is not detected during user creation", __LINE__);
}
//correct use
$newUserId = User::CreateUser($testPseudo, $testMDp, $testEmail);

global $db;
$query = $db->prepare('SELECT id_user FROM user WHERE pseudo = :pseudo');
$params = array("pseudo" => $testPseudo);
$query->execute($params);
$userId = $query->fetchColumn();

if($newUserId !== $userId){
    addToResults("user was not created correctly", __LINE__);
}
/****************user get *********************/

$user = new User($newUserId);

if($user->userId !== $userId){
    addToResults("user id was not correctly obtained", __LINE__);
}
if($user->username !== $testPseudo){
    addToResults("username was not correctly obtained", __LINE__);
}
if($user->email !== $testEmail){
    addToResults("user email was not correctly obtained", __LINE__);
}
if($user->accountState !== "created"){
    addToResults("account state was not correctly obtained", __LINE__);
}


/****************user login*********************/

$resultId = User::CheckUser($testPseudo, $testMDp);
if(invalidId($resultId)){
    addToResults("user check fails", __LINE__);
}

/****************user delete*********************/
User::DeleteUser($testPseudo, $testMDp);
$resultId = User::CheckUser($testPseudo, $testMDp);

if($resultId != -70){
    addToResults("user was not deleted correctly", __LINE__);
}

eraseDb();

/******************* testing login functionalities START ********************/

// db shouldn't have any users. So all checkUser uses should fail

$result1 = User::CheckUser("wrongValue", "wrongValue"); //-3
$result2 = User::CheckUser("", ""); // -1 - should first check for username
$result3 = User::CheckUser("wrongValue", "user1mdp"); //-3
$result4 = User::CheckUser("user1", "wrongValue"); //-3
$result5 = User::CheckUser("user1", ""); // -2

if($result1 !== -70){
    addToResults("checkUser doesn't verify username correctly", __LINE__);
}

if($result2 !== -60){
    addToResults("checkUser doesn't verify username first in case of empty values", __LINE__);
}

if($result3 !== -70){
    addToResults("checkUser doesn't verify username correctly", __LINE__);
}

if($result4 !== -70){
    addToResults("checkUser doesn't verify password correctly", __LINE__);
}

if($result5 !== -10){
    addToResults("checkUser doesn't verify if password is empty", __LINE__);
}

//creating a user
$correctUsername = "user1";
$correctPassword = "user1mdp";
$correctEmail = "test@test.com";
$userId = User::CreateUser($correctUsername, $correctPassword, $correctEmail);

//we check that user can't login yet since they have not activated their account
$user = new User($userId);
$successfulLogin = $user->accountState === "validated";

if($successfulLogin){
    addToResults("a newly created account shouldn't be able to login", __LINE__);
}
$userToken = $user->userToken;
$user->validateAccount($userId, $userToken);

//we check that user can login yet since they have activated their account
$successfulLogin = $user->accountState === "validated";
if(!$successfulLogin){
    addToResults("a validated account should be able to login", __LINE__);
}
/******************* testing login functionalities END ********************/

eraseDb();
/******************* testing proposition Crud START ********************/

//Creating a proposition
$testTitle = "Test Title";
$testContent = "Test content for the test proposition with a test title from the test user User2.";
$testUser = User::CreateUser("KDOeje", "fezjfzFZEZz", $testEmail);

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
    addToResults("empty title error is not detected during proposition creation", __LINE__);
}
if($resultCreatePropositionWithEmptyTitle2 !== -40){
    addToResults("null title error is not detected during proposition creation", __LINE__);
}
if($resultCreatePropositionWithEmptyContent !== -20){
    addToResults("empty contents error is not detected during proposition creation", __LINE__);
}
if($resultCreatePropositionWithEmptyContent2 !== -20){
    addToResults("null contents error is not detected during proposition creation", __LINE__);
}
if($resultCreatePropositionWithInvalidUserId !== -50){
    addToResults("empty user id error is not detected during proposition creation", __LINE__);
}
if($resultCreatePropositionWithInvalidUserId2 !== -50){
    addToResults("null user id error is not detected during proposition creation", __LINE__);
}
if($resultCreatePropositionWithInvalidUserId3 !== -50){
    addToResults("incorrect user id error is not detected during proposition creation", __LINE__);
}

if($resultCreateCorrectProposition < 1){
    addToResults("a new proposition is not correctly created", __LINE__);
}



//Reading propositions of a user
$testTitle = "A new proposition";
$testContent = "Test content for the new proposition during a proposition read test.";
$testUser = User::CreateUser("jiejzijifez", "ZEFZEFzejfzeiji323", $testEmail);
createProposition($testTitle, $testContent, $testUser);

//error management
$resultGetPropositionWithInvalidUserId = getUserPropositions("");
$resultGetPropositionWithInvalidUserId2 = getUserPropositions(null);
$resultGetPropositionWithInvalidUserId3 = getUserPropositions(-1);
//correct use
$resultGetCorrectPropositions = getUserPropositions($testUser);

if($resultGetPropositionWithInvalidUserId !== -50){
    addToResults("empty user id is not detected during proposition read", __LINE__);
}
if($resultGetPropositionWithInvalidUserId2 !== -50){
    addToResults("null user id is not detected during proposition read", __LINE__);
}
if($resultGetPropositionWithInvalidUserId !== -50){
    addToResults("incorrect user id is not detected during proposition read", __LINE__);
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
    addToResults("correct proposition title was not present", __LINE__);
}
if(!$titleMatch){
    addToResults("correct proposition content was not present", __LINE__);
}

//Deleting a proposition of a user
$testTitle = "A proposition to be deleted";
$testContent = "Test content for the new proposition during a proposition deletion test.";
$testUser = User::CreateUser("FZEFzekfz", "FZEFZEé+°23423", $testEmail);
$testPropositionId = createProposition($testTitle, $testContent, $testUser);
//error management
$resultDeletePropositionWithInvalidUserId = deleteProposition("", $testPropositionId);
$resultDeletePropositionWithInvalidPropositionId = deleteProposition($testUser, -1);

//correct use
$resultDeleteCorrectProposition = deleteProposition($testUser, $testPropositionId); //TODO this needs to be used by after deleting votes for the proposition

if($resultDeletePropositionWithInvalidUserId !== -50){
    addToResults("empty user id is not detected during proposition deletion", __LINE__);
}
if($resultDeletePropositionWithInvalidPropositionId !== -30){
    addToResults("empty proposition id is not detected during proposition deletion", __LINE__);
}

if($resultDeleteCorrectProposition !== 1){
    addToResults("proposition deletion has encountered an error", __LINE__);
}

$resultGetCorrectPropositions = getUserPropositions($testUser);
$propositionMatch = false;
foreach($resultGetCorrectPropositions as $key => $proposition){

    if($proposition->id_prop === $resultDeleteCorrectProposition){
        $titleMatch = true;
    }

}
if($propositionMatch){
    addToResults("correct proposition was not deleted", __LINE__);
}

//reading a specific proposition
$testTitle = "A new proposition to get";
$testContent = "Test content for the new proposition to be targetted during a proposition read test.";
$testUser = User::CreateUser("FZEZfezfez", "FZEK+3423423°+", $testEmail);
$testPropositionId = createProposition($testTitle, $testContent, $testUser);

//error management
$resultGetPropositionWithInvalidUserId = getProposition(-1, $testPropositionId);
$resultGetPropositionWithInvalidPropositionId = getProposition($testUser, "");
//correct use
$resultGetCorrectProposition = getProposition($testUser, $testPropositionId);

if($resultGetPropositionWithInvalidUserId !== -50){
    addToResults("empty user id is not detected during proposition read", __LINE__);
}
if($resultGetPropositionWithInvalidPropositionId !== -30){
    addToResults("empty proposition id is not detected during proposition read", __LINE__);
}

if(!$resultGetCorrectProposition){
    addToResults("correct proposition object was not created", __LINE__);
}
if($resultGetCorrectProposition->title != $testTitle){
    addToResults("correct proposition title was not found", __LINE__);
}
if($resultGetCorrectProposition->contenu != $testContent){
    addToResults("correct proposition content was not present", __LINE__);
}

//Updating a proposition
$testTitle = "Test Title for update";
$testContent = "Test content for the test proposition update with a test title from the test user User2.";
$testUser = User::CreateUser("EZFZEKFZEFZ", "JT43JJE2K33K2rfezkfze", $testEmail);
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
    addToResults("incorrect user id is not detected during proposition update", __LINE__);
}
if($resultUpdatePropositionWithEmptyPropositionId !== -30){
    addToResults("incorrect proposition id is not detected during proposition update", __LINE__);
}
if($resultUpdatePropositionWithEmptTitle !== -40){
    addToResults("empty title is not detected during proposition update", __LINE__);
}
if($resultUpdatePropositionWithEmptContent !== -20){
    addToResults("empty content is not detected during proposition creation", __LINE__);
}

if($resultUpdateCorrectProposition !== 1){
    addToResults("the proposition is not correctly updated", __LINE__);
}

$newPropositionContents = getProposition($testUser, $testPropositionId);
if($newPropositionContents->title !== $newTestTitle){
    addToResults("the proposition title was not correctly updated", __LINE__);
}
if($newPropositionContents->contenu !== $newTestContent){
    addToResults("the proposition content was not correctly updated", __LINE__);
}

//submitting a proposition to vote
$testTitle = "Test Title for submittoVote";
$testContent = "Test content for the test validate update for vote with a test title from the test user User2.";
$testUser = User::CreateUser("ZF44ZJEZ", "FZfezfezF__ZE33E", $testEmail);
$testPropositionId = createProposition($testTitle, $testContent, $testUser);
$resultSubmitPropositionWithEmptyUserId = submitPropositionToVote(null, $testPropositionId);
$resultSubmitPropositionWithEmptyPropositionId = submitPropositionToVote($testUser, null);
$resultCorrectSubmitProposition = submitPropositionToVote($testUser, $testPropositionId);


//error management
if($resultSubmitPropositionWithEmptyUserId !== -50){
    addToResults("incorrect user id is not detected during proposition submission to vote", __LINE__);
}
if($resultSubmitPropositionWithEmptyPropositionId !== -30){
    addToResults("incorrect proposition id is not detected during proposition submission to vote", __LINE__);
}
if($resultCorrectSubmitProposition !== 1){
    addToResults("the proposition content was not correctly submitted to vote", __LINE__);
}
$newPropositionContents = getProposition($testUser, $testPropositionId);
if($newPropositionContents->date_valid === ""){
    addToResults("the proposition content was not correctly submitted to vote", __LINE__);    
}

//checking that a proposition can no longer be edited after submission to vote but it can still be deleted
$newTestTitle = "new test title";
$newTestContent = "new test content for chcking that update is disabled after submission to vote";
$resultUpdatePropositionDisabled = updateProposition($testUser, $testPropositionId, $newTestTitle, $newTestContent);

if($resultUpdatePropositionDisabled === 1){
    addToResults("the proposition already submitted to vote is incorrectly updated", __LINE__);
}

//getting voted propositions from all user to further voting by users who have not voted yet
$hasError = false;
$noError = true;
$propositionsAvailableForVoting = getPropositionsToVote();
$votedPropositionContainer = array();
if(!is_array($propositionsAvailableForVoting)){
    addToResults("voted propositions are not correctly gathered ", __LINE__);
}else{
    $votedPropositionContainer = $propositionsAvailableForVoting;
}

foreach($votedPropositionContainer as $votedProposition){
    if(property_exists($votedProposition, "date_valid")){
        $noError *= $votedProposition->date_valid != "";
    }else{
        $hasError = true;
    }
}

if($hasError || !$noError){
    addToResults("propositions not submitted to vote or malformed propositions were also gathered", __LINE__);
}


/******************* testing proposition Crud END ********************/


eraseDb();



/******************* testing voting Crud START ********************/

//getting the voted status for a given proposition by a given user

$testTitleVoted = "Test Title  - to be voted";
$testContentVoted = "Test content for the testing getting voted status for a given proposition by a given user - User 2. To be voted";
$proposer = User::CreateUser("FZEFZEZZE", "FZEJjfize32", $testEmail);
$testPropositionIdVoted = createProposition($testTitleVoted, $testContentVoted, $proposer);
submitPropositionToVote($proposer, $testPropositionIdVoted);
//Checking if indeed, Upon creating a proposition, user automatically votes for their proposition.

$resultGetVotedStatusWithIncorrectUserid = userVotedForProposition(-2, $testPropositionIdVoted);
$resultGetVotedStatusWithIncorrectPropositionid = userVotedForProposition($proposer, null);
$resultGetVotedStatus = userVotedForProposition($proposer, $testPropositionIdVoted);
$resultGetVotedStatusForOtherUser = userVotedForProposition(User::CreateUser("FZEFZSzefz", "fZEFZEojojfezf3", $testEmail), $testPropositionIdVoted);

if($resultGetVotedStatusWithIncorrectUserid != -50){
    addToResults("empty user id error is not detected during getting voted status", __LINE__);
}
if($resultGetVotedStatusWithIncorrectPropositionid != -30){
    addToResults("empty proposition id error is not detected during getting voted status", __LINE__);
}
if(!$resultGetVotedStatus){
    addToResults("voted proposition is indicated as unvoted", __LINE__);
}
if($resultGetVotedStatusForOtherUser){
    addToResults("unvoted proposition is indicated as voted", __LINE__);
}


//voting for someone else's proposition - voteForProposition()
$testTitleVoted = "Test Title-VotebyOther";
$testContentVoted = "Test content for the testing vote for other user's propositions";
$testUser = User::CreateUser("ZFZJEZ", "FZfezfezFZE33E", $testEmail);
$votingUser= User::CreateUser("FZEA23", "fzizjeiffjez", $testEmail);

//creating proposition with user 3
$testPropositionId = createProposition($testTitleVoted, $testContentVoted, $testUser);
//checking that user 1 hasn't voted for it yet
if(userVotedForProposition($votingUser, $testPropositionId)){
    addToResults("unwanted for vote found", __LINE__);
}

//getting the number of nbPour for that proposition
$nbPourAmountOld = getProposition($testUser, $testPropositionId)->nbPour;
// voting for that proposition with user 1
voteForProposition($votingUser, $testPropositionId);

//checking that user1 has voted for it
if(!userVotedForProposition($votingUser, $testPropositionId)){
    addToResults("searched for vote was not found", __LINE__);
}

//checking that that proposition's nbPour number increase by 1
$nbPourAmountNew = getProposition($testUser, $testPropositionId)->nbPour;

if($nbPourAmountOld != ($nbPourAmountNew-1)){
    addToResults("votedFor counter is not increased for the proposition that was voted for", __LINE__);
}
//checking that user1 can't vote for or against that proposition anymore
voteForProposition($votingUser, $testPropositionId);
$nbPourAmountNew2 = getProposition($testUser, $testPropositionId)->nbPour;

if( (integer) $nbPourAmountNew === (integer) ($nbPourAmountNew2-1)){
    addToResults("votedFor counter is increased again even though user had already voted for it", __LINE__);
}

//voting against someone else's proposition - voteAgainstProposition()
$testTitleVoted = "Test Title-VoteAgainstOther";
$testContentVoted = "Test content for the testing vote against other user's propositions";
$testUser = User::CreateUser("fezfzFZFZe", "FEZJ32R23Kf", $testEmail);
$otherTestUSer = User::CreateUser("FZEFfezfze", "FZE3ré3Rfz3", $testEmail);
//creating proposition with user 2
$testPropositionId = createProposition($testTitleVoted, $testContentVoted, $testUser);

//checking that user 3 hasn't voted for it yet
if(userVotedForProposition($otherTestUSer, $testPropositionId)){
    addToResults("unwanted against vote found", __LINE__);
}

//getting the number of nbContre for that proposition
$nbAgainstAmountOld = getProposition($testUser, $testPropositionId)->nbContre;

//voting against that proposition with user $otherTestUSer
voteAgainstProposition($otherTestUSer, $testPropositionId);

//checking that $otherTestUSer has voted against it
if(!userVotedForProposition($otherTestUSer, $testPropositionId)){
    addToResults("searched against vote was not found", __LINE__);
}

//checking that that proposition's nbContre number increase by 1
$nbAgainstAmountNew = getProposition($testUser, $testPropositionId)->nbContre;

if($nbAgainstAmountOld != ($nbAgainstAmountNew-1)){
    addToResults("votedAgainst counter is not increased for the proposition that was voted against", __LINE__);
}

//checking that user3 can't vote for or against that proposition anymore
voteAgainstProposition($otherTestUSer, $testPropositionId);
$nbAgainstAmountNew2 = getProposition($testUser, $testPropositionId)->nbContre;
if((integer) $nbAgainstAmountNew === (integer) ($nbAgainstAmountNew2-1)){
    addToResults("votedAgainst counter is increased again even though user had already voted for it", __LINE__);
}
/******************* testing voting Crud END ********************/




eraseDb();



/******************* testing comment Crud START ********************/

//testing comment creation
$propTitleForComment = "Test Title-forComment";
$propContentForComment = "Test content for the testing commenting system";
$testUser = User::CreateUser("FZEFfezfztr", "FZKFZEKf23423(TGgg", $testEmail); 
$testPropId = createProposition($propTitleForComment, $propContentForComment, $testUser);
$testCommentContent = "This is a comment for testing comment system";

//Error managements
$testCommentIdIncorrectUser = createComment(null, $testPropId, $testCommentContent);
$testCommentIdIncorrectProp = createComment($testUser, -30, $testCommentContent);
$testCommentIdIncorrectComment = createComment($testUser, $testPropId, "");
$testCommentId = createComment($testUser, $testPropId, $testCommentContent);

if($testCommentIdIncorrectUser !== -50){
    addToResults("incorrect user id is not detected during comment creation", __LINE__);
}
if($testCommentIdIncorrectProp !== -30){
    addToResults("incorrect proposition id is not detected during comment creation", __LINE__);
}
if($testCommentIdIncorrectComment !== -15){
    addToResults("incorrect comment is not detected during comment creation", __LINE__);
}

//correct use
if(invalidId($testCommentId)){
    addToResults("comment was not created", __LINE__);
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

$commentObjectsArrayIncorrectProp = getComments(null);
$commentObjectsArray = getComments($testPropId);
if($commentObjectsArrayIncorrectProp !== -30){
    addToResults("incorrect proposition id is not detected during comment gathering", __LINE__);
}

$commentMatch = false;
foreach($commentObjectsArray as $key => $comment){
    if($comment->id_comment == $commentFromUser1){
        $commentMatch = true;
    }
}
if(!$commentMatch){
    addToResults("comment of the first user was not found", __LINE__);
}
$commentMatch = false;
foreach($commentObjectsArray as $key => $comment){
    if($comment->id_comment === $commentFromUser2){
        $commentMatch = true;
    }
}
if(!$commentMatch){
    addToResults("comment of the second user was not found", __LINE__);
}


//testing comment deletion

$propTitleForComment = "Test Title-deleteComment";
$propContentForComment = "Test content for the testing comment deletion";
$testUser = User::CreateUser("JVLzKFJ", "KFZEjfez5432", $testEmail); 
$testPropId = createProposition($propTitleForComment, $propContentForComment, $testUser);
$testCommentToDelete = "This is a comment to be deleted for testing comment system";
$testCommentId = createComment($testUser, $testPropId, $testCommentToDelete);

//error management
$resultDeleteCommentInvalidUser = deleteComment("", $testCommentId);
$resultDeleteCommentInvalidComment = deleteComment($testUser, null);

//correct use
$resultDeleteComment = deleteComment($testUser, $testCommentId); 

if($resultDeleteCommentInvalidUser !== -50){
    addToResults("incorrect user id is not detected during comment deletion", __LINE__);
}
if($resultDeleteCommentInvalidComment !== -18){
    addToResults("incorrect comment id is not detected during comment deletion", __LINE__);
}

if($resultDeleteComment !== 1){
    addToResults("comment deletion has encountered an error", __LINE__);
}

$resultGetCorrectComments = getComments($testUser);
$commentMatch = false;
foreach($resultGetCorrectComments as $key => $currentComment){

    if($currentComment->id_comment === $testCommentId){
        $titleMatch = true;
    }

}
if($propositionMatch){
    addToResults("correct comment was not deleted", __LINE__);
}

//checking that users can only delete their own comments from propositions

$propTitleForComment = "Test-deleteOthersComment";
$propContentForComment = "Test content for the testing comment deletion of other uers which should be impossible";
$creatingUser = User::CreateUser("FZEfzefezf", "FZEFjfijijez323", $testEmail);
$commentingUser = User::CreateUser("FZEfFZEF3ezf", "FZEFZE324234fzef", $testEmail);
$deletingUser = User::CreateUser("ZJ3R23Fez", "fezfezFZEFZ2342", $testEmail);
$testPropId = createProposition($propTitleForComment, $propContentForComment, $creatingUser);
$testCommentToDelete = "This is a comment to be deleted for testing comment system, specifically comments of other users which should be impossible";
$testCommentId = createComment($commentingUser, $testPropId, $testCommentToDelete);
$result = deleteComment($deletingUser, $testCommentId);

if($result === 1){
    addToResults("a user was able to delete someone else's comment", __LINE__);
}

$commentObjectsArray = getComments($testPropId);
$commentMatch = false;
foreach($commentObjectsArray as $key => $comment){
    if($comment->id_comment === $testCommentId){
        $commentMatch = true;
    }
}
if(!$commentMatch){
    addToResults("comment was not found after a user who has not created it tried to delete it ", __LINE__);
}

/******************* testing comment Crud END ********************/



eraseDb();










showTestResults($errorResults);


function eraseDb(){
    global $db;
    $sql = "TRUNCATE democratie.voter;
    TRUNCATE democratie.commenter;
    DELETE FROM democratie.proposition; ALTER TABLE democratie.proposition AUTO_INCREMENT = 1;
    DELETE FROM democratie.user;ALTER TABLE democratie.user AUTO_INCREMENT = 1;";
    $query = $db->exec($sql);
}

function addToResults($message, $errorNo){
    global $errorResults;
    array_push($errorResults, array("errorno" => $errorNo, "errorcontent" => $message));
}
function showTestResults($results){
    global $errorResults;
    if(empty($results)){
        echo "<br><br>all PHP tests were succesfull";
    }else{
        $result = "<br><br>some PHP tests were unsuccessfull ";
        foreach($errorResults as $content){
    
            $result .= "<br>".$content["errorno"]." : ".$content["errorcontent"]. "<br>";
        }
        echo $result;
    }
}


?>