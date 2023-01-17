<?php
include "db_config.php";
include "autoload.php";
$requestMethod = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);
$requestOperation = $data['operation'];

switch ($requestOperation) {
    case 'auth':
        /* @todo  must be validation on username and password */
        $userName = base64_decode($data['username']);
        $password = base64_decode($data['password']);
        $findUserFromDB = $conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $findUserFromDB->execute(["username"=>$userName,'password' => $password]);
        $user = $findUserFromDB->fetch();
        if($user ==false){
            Response::json(null,"user not found",422);
            break;
        }

        $getToken = TokenManagement::createToken($conn,$user["id"]);
        LogHandler::insert($conn,"user login",$data,$user["id"]);
        $data =[
           "token"=> $getToken["token"],
            "created_at"=>$user["created_at"],
            "valid_till"=>$getToken["valid_till"]
        ] ;

        Response::json($data);

        break;
    case 'request':
        /* @todo  must be validation on id or reg_no */
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

        Response::json($data);

        break;
    case 'payment':

        $currentUserToken = $data['token'];
        $checkIfTokenVaild = TokenManagement::checkTokenIsValid($conn,$currentUserToken);
        if($checkIfTokenVaild['status'] == false){
            Response::json(null,$checkIfTokenVaild["message"],401);
        }
        $currentUser =  TokenManagement::currentUser($conn,$currentUserToken);
        LogHandler::insert($conn,"payment ",$data,$currentUser["id"]);
        Response::payment("Operation accepted",0);
        break;
    case 'refund':

        $currentUserToken = $data['token'];
        $checkIfTokenVaild = TokenManagement::checkTokenIsValid($conn,$currentUserToken);
        if($checkIfTokenVaild['status'] == false){
            Response::payment($checkIfTokenVaild["message"],$checkIfTokenVaild["code"]);
        }
        $currentUser =  TokenManagement::currentUser($conn,$currentUserToken);
        LogHandler::insert($conn,"refund ",$data,$currentUser["id"]);
        Response::payment("Operation accepted",0);
        break;
    default:
         Response::payment("Operation Incomplete",5004);
        break;
}




?>