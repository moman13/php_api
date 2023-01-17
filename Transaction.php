<?php
require_once "autoload.php";
class Transaction {
    /**
     * @param $conn
     * @param $data
     * @return array
     */
    public  static function getTransactionInfo($conn,$data){
        self::validationOnIdOrRegno($data);
        $id = $data['id'];
        $regNo = $data['reg_no'];
        $currentUserToken = $data['token'];
        $checkIfTokenVaild = TokenManagement::checkTokenIsValid($conn,$currentUserToken);
        if($checkIfTokenVaild['status'] == false){
            Response::json(null,$checkIfTokenVaild["message"],401);
        }
        $getTransactions =$conn->prepare("SELECT * FROM transactions WHERE id=:id OR reg_no=:reg_no");
        $getTransactions->execute(["id"=>$id,'reg_no' => $regNo]);
        $transactions = $getTransactions->fetchAll();
        $currentUser = TokenManagement::currentUser($conn,$currentUserToken);
        if($currentUser == false){
            Response::json(null,"user not found",500);
        }
        LogHandler::insert($conn,"request info",$data,$currentUser["id"]);
        $data = [
            "name"=>base64_encode($currentUser["username"]),
            "items"=>$transactions
        ];
        return $data;

    }

    /**
     * @param $conn
     * @param $data
     * @return void
     */
    public static function payment($conn,$data){
        self::validationOnIdOrRegno($data);
        $currentUserToken = $data['token'];
        $checkIfTokenVaild = TokenManagement::checkTokenIsValid($conn,$currentUserToken);
        if($checkIfTokenVaild['status'] == false){
            Response::json(null,$checkIfTokenVaild["message"],401);
        }
        $currentUser =  TokenManagement::currentUser($conn,$currentUserToken);
        LogHandler::insert($conn,"payment ",$data,$currentUser["id"]);
    }

    /**
     * @param $conn
     * @param $data
     * @return void
     */
    public static function refund($conn,$data){
        self::validationOnIdOrRegno($data);
        $currentUserToken = $data['token'];
        $checkIfTokenVaild = TokenManagement::checkTokenIsValid($conn,$currentUserToken);
        if($checkIfTokenVaild['status'] == false){
            Response::payment($checkIfTokenVaild["message"],$checkIfTokenVaild["code"]);
        }
        $currentUser =  TokenManagement::currentUser($conn,$currentUserToken);
        LogHandler::insert($conn,"refund ",$data,$currentUser["id"]);
    }

    /**
     * @param $data
     * @return void
     */
    private function validationOnIdOrRegno($data){
        if(!(isset($data["id"]) || isset($data["reg_no"]))){
            Response::json(null,"id or reg_no is required",422);
        }

    }
}

?>