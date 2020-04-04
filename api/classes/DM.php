<?php


class DM
{
    /**
     * @var PDO
     */
    public $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function saveDM($username, $message, $response) :bool
    {
        $query = 'INSERT INTO dms SET 
                       username=:username,
                       message= :message,
                       response = :response';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':response', $response);
        $stmt->bindParam(':message', $message);

        return $stmt->execute();

    }

    public function getDMs():array
    {
        $query = 'SELECT * FROM dms ORDER BY created_at DESC LIMIT 10';

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistory($username):array
    {
        $query = "SELECT id, message, response, created_at 
                    FROM dms WHERE username='$username' ORDER BY  created_at DESC LIMIT 10";

        $stmt = $this->conn->query($query);

//        $stmt->bindParam(':username',$username);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}