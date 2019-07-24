<?php
require_once "utils.php";
require_once "../inc/db-connect.inc.php";
class User{

    public $userId;
    public $username;
    public $email;
    public $accountState;
    public $userToken;
    private $hashedMdp;
    
    public function __construct($userId = null){
        $this->getUser($userId);
    }

    public function getUser($userId){
        if(idInvalid($userId)){
            return throwError("invalidUserId");
        }

        global $db;
        $query = $db->prepare("SELECT * FROM user WHERE `id_user` = :userId");
        $params = array("userId" => $userId);
        $query->execute($params);
        $userObject = $query->fetch(PDO::FETCH_OBJ);
        
        $this->userId = $userId;
        $this->username = $userObject->pseudo;
        $this->email = $userObject->e_mail;
        $this->accountState = $userObject->account_state;
        $this->userToken = $userObject->user_token;
        $this->hashedMdp = $userObject->hashed_mdp;

        return true;
        
    }

    public function validateAccount($userId, $userToken){
        if(invalidId($userId)){
            return throwError("invalidUserId");
        }
        if(empty($userToken)){
            return throwError("invalidToken");
        }
        global $db;
        
        $query = $db->prepare('SELECT user_token FROM user WHERE id_user = :userId');
        $params = array("userId" => $userId);
        $query->execute($params);
        $userTokenDb = $query->fetchColumn();
    
        if($userTokenDb === $userToken){
            $query = $db->prepare("UPDATE user SET account_state = 'validated' WHERE `id_user` = :userId;");
            $params = array("userId" => $userId);
            $query->execute($params);
            if($query->rowCount() === 1){
                $this->accountState = "validated";
                return true;            
            }else{
                return throwError("sqlError");
            }
        }else{
            return throwError("invalidToken");
        }
    }

    public static function CreateUser($username, $mdp, $email){
        if(empty($username)){
            return throwErrorCode("invalidUsername");
        }
        if(invalidEmail($email)){
            return throwErrorCode("invalidEmail");
        }
        if(empty($mdp)){
            return throwErrorCode("emptyPassword");
        }else{
            $mdp = password_hash($mdp, PASSWORD_BCRYPT, array('cost' => 12));
        }

        global $db;
        $userToken = createToken();
        $query = $db->prepare("INSERT INTO user (pseudo, hashed_mdp, e_mail, user_token) VALUES (:pseudo, :hashedmdp, :email, :userToken)");
        $params = array("pseudo" => $username, "hashedmdp" => $mdp, "email" => $email, "userToken" => $userToken);
        $query->execute($params);
        

        return $db->lastInsertId();
    }

    public static function CheckUser($username, $mdp){
        if(empty($username)){
            return throwError("invalidUsername");
        }
        if(empty($mdp)){
            return throwError("emptyPassword");
        }
        // die;
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

    public static function DeleteUser($username, $mdp){
        if(empty($username)){
            return throwError("invalidUsername");
        }
        if(empty($mdp)){
            return throwError("emptyPassword");
        }
        global $db;
        $query = $db->prepare('DELETE FROM user WHERE pseudo = :pseudo LIMIT 1');
        $params = array("pseudo" => $username);
        $query->execute($params);
        
        return $query->rowCount();
    }

}