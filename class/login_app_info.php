<?php

class LoginApp
{
    private const db_table = "student";
    private PDO $conn;

    public function __construct (PDO $conn)
    {
        $this->conn = $conn;
    }

    public function checkAccount () : array
    {
        $account = json_decode(file_get_contents("php://input"), true);

        $sql_query = "
                SELECT *
                FROM " . self::db_table . "
                WHERE 
                    ID_Student = " . $account['ID_Student'];

        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$response) {
            $data['message'] = 'false';
            $data['error'] = 'Your student ID is incorrect';
        }
        else if ($response['Password_Student'] != $account['Password_Student']) {
            $data['message'] = 'false';
            $data['error'] = 'Your password is incorrect';
        }
        else {
            $data['message'] = 'true';
            $data['info'] = $response;
        }

        return $data;
    }
}


