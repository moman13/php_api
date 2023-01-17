<?php
class LogHandler {

    public static  function all($conn){
        $getAll= $conn->prepare("SELECT * FROM logs ");
        $getAll->execute();
        $getAll = $getAll->fetchAll();
        return $getAll;
    }
    public static  function getByUser($conn,$user_id){
        $getLog= $conn->prepare("SELECT * FROM logs WHERE user_id=:user_id");
        $getLog->execute(["user_id"=>$user_id]);
        $userRecord = $getLog->fetch();
        return $userRecord;

    }
    public static  function insert($conn,$title,$data,$user_id){
        $sql = "INSERT INTO logs (title, payload, user_id) VALUES (?,?,?)";
        $stmt= $conn->prepare($sql);
        $stmt->execute([$title, json_encode($data), $user_id]);
        return true;
    }
}

?>