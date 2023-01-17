<?php
require_once "autoload.php";
$requestMethod = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);
$requestOperation = $data['operation'];
switch ($requestOperation) {
    case 'auth':
        /* @todo  must be validation on username and password */
        $result = Auth::login($conn,$data);
        Response::json($result);
        break;
    case 'request':
        $result = Transaction::getTransactionInfo($conn,$data);
        Response::json($result);
        break;
    case 'payment':
        Transaction::payment($conn,$data);
        Response::payment("Operation accepted",0);
        break;
    case 'refund':
        Transaction::refund($conn,$data);
        Response::payment("Operation accepted",0);
        break;
    default:
         Response::payment("Operation Incomplete",5004);
        break;
}


?>