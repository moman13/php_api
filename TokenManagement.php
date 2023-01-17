<?php
class TokenManagement {
    public  $SECRET_KEY = "mms_secret_key";
    public  static function createToken($conn,$user_id,$expiredInDays=7){
        $now = date("Y-m-d H:i:s");
        $vaildTil =date("Y-m-d H:i:s",strtotime($now." +$expiredInDays days"));
        $tokenGeneric ="mms_secret_key".$_SERVER["SERVER_NAME"];
        $token = hash('sha256', $tokenGeneric.$user_id);
        $insert = $conn->prepare("INSERT INTO sessions (user_id, token, valid_till) VALUES (?, ?, ?)");
        $insert->execute([$user_id, $token, $vaildTil]);
        return array("token"=>$token,"valid_till"=>$vaildTil);
    }
    public static function checkTokenIsValid($conn,$token){
        $now = date("Y-m-d H:i:s");
        $findSession= $conn->prepare("SELECT * FROM sessions WHERE token= :token");
        $findSession->execute(["token"=>$token]);
        $session = $findSession->fetch();

        if($session == false){
            return array('status'=>false,"message"=>"Token is invalid","code"=>5002);
        }
        if ($session["valid_till"] < $now){
            $currentUser =  TokenManagement::currentUser($conn,$token);
            LogHandler::insert($conn,"token  expiered ",$token,$currentUser["id"]);
            return array('status'=>false,"message"=>"Token  expiered","code"=>5003);
        }
        return  array("status"=>true,"message"=>"Token valid","code"=>5000);

 }
    public static function currentUser($conn,$token){
        $now = date("Y-m-d H:i:s");
        $findSession= $conn->prepare("SELECT * FROM sessions WHERE token=:token");
        $findSession->execute(["token"=>$token]);
        $session = $findSession->fetch();
        $getUserFromDB = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $getUserFromDB->execute(["id"=>$session['user_id']]);
        $currentUser =  $getUserFromDB->fetch();
        return $currentUser;

    }
}

?>