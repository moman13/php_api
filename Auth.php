<?php
require_once "autoload.php";
class Auth {
    /**
     * handling the login
     * @param $conn
     * @param $data
     * @return array
     */

    public  static function login($conn,$data){
        $userName = base64_decode($data['username']);
        $password = base64_decode($data['password']);
        $findUserFromDB = $conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $findUserFromDB->execute(["username"=>$userName,'password' => $password]);
        $user = $findUserFromDB->fetch();
        if($user ==false){
            Response::json(null,"user not found",422);
        }

        $getToken = TokenManagement::createToken($conn,$user["id"]);
        LogHandler::insert($conn,"user login",$data,$user["id"]);
        $data =[
            "token"=> $getToken["token"],
            "created_at"=>$user["created_at"],
            "valid_till"=>$getToken["valid_till"]
        ] ;
        return $data;
    }
}

?>