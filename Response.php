<?php
class Response {
    /**
     * custom response  for general response
     * @param $data
     * @param $message
     * @param $code
     * @return void
     */
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

    /**
     * custom response for transaction
     * @param $message
     * @param $code
     * @return void
     */
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