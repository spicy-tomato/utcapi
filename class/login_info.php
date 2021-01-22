<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . '/utcapi/api-v2/class/sinh_vien_controller.php';

    class LoginInfo
    {
        private const bang_csdl = "student";
        private PDO $ket_noi;
        private string $ma_sv;
        private string $password;

        public function __construct(PDO $ket_noi, string $ma_sv, string $password)
        {
            $this->ket_noi  = $ket_noi;
            $this->ma_sv    = $ma_sv;
            $this->password = $password;
        }

        public function login(): bool
        {
            $sqlQuery =
                "SELECT
                    ID_Student,
                    Student_Name,
                    ID_class
                FROM 
                    " . self::bang_csdl . "
                WHERE
                    ID_Student = :ma_sv
                LIMIT 0, 1";

            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute([':ma_sv' => $this->ma_sv]);

                $loggedAccount = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($loggedAccount) == 1) {
                    return true;
                }

                return false;

            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }

        public function getMaSv(): string
        {
            return $this->ma_sv;
        }
    }