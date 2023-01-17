<?php
class LogHandler {
    /**
     * @param $conn
     * @return array
     */
    public static  function all($conn){
        $getAll= $conn->prepare("SELECT * FROM logs ");
        $getAll->execute();
        $getAll = $getAll->fetchAll();
        return $getAll;
    }

    /**
     * @param $conn
     * @param $user_id
     * @return array
     */
    public static  function getByUser($conn,$user_id){
        $getLog= $conn->prepare("SELECT * FROM logs WHERE user_id=:user_id");
        $getLog->execute(["user_id"=>$user_id]);
        $userRecord = $getLog->fetch();
        return $userRecord;

    }

    /**
     * @param $conn
     * @param $title
     * @param $data
     * @param $user_id
     * @return void
     */
    public static  function insert($conn,$title,$data,$user_id)
    {
        try {
            $conn->beginTransaction();
            $sql = "INSERT INTO logs (title, payload, user_id) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title, json_encode($data), $user_id]);
            $conn->commit();
        } catch (\PDOException $e) {
            $conn->rollBack();
            Response::json('',$e->getMessage(),500);
        }
    }
}

?>