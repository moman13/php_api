<?php
class Response {

    public static  function json($data,$message="success",$code=200){
        $response =[
            "message"=>$message,
            "code"=>$code,
            "data"=>$data
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        die();

    }
    public static  function payment($message,$code){
        $response =[
            "mag"=>$message,
            "error_code"=>$code,
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        die();

    }
}

?>