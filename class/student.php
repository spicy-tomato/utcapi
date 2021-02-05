<?php

    class Student
    {
        private const db_table = "Student";
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function get(): array
        {
            $sqlQuery
                = /** @lang text */
                "SELECT 
                    ID_Student,
                    Student_Name,
                    ID_class
                FROM 
                    " . self::db_table;


            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }

        public function tim(string $ma_sv): array
        {
            $sqlQuery
                = /** @lang text */
                "SELECT
                    ID_Student,
                    Student_Name,
                    ID_class
                FROM 
                    " . self::db_table . "
                WHERE
                    ID_Student = :ma_sv
                LIMIT 0, 1";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([':ma_sv' => $ma_sv]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }

        public function them(array $thong_tin): void
        {
            $sqlQuery
                = /** @lang text */
                "INSERT INTO
                    " . self::db_table . "
                SET
                    ma_sv    = :ma_sv,
                    mat_khau = :mat_khau,
                    ho_ten   = :ho_ten,
                    ma_lop   = :ma_lop";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([
                    ':ma_sv' => $thong_tin['ma_sv'],
                    ':mat_khau' => $thong_tin['mat_khau'],
                    ':ho_ten' => $thong_tin['ho_ten'],
                    ':ma_lop' => $thong_tin['ma_lop'],
                ]);
            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }

        public function capNhat(string $ma_sv, array $thong_tin): void
        {
            $sqlQuery
                = /** @lang text */
                "UPDATE 
                    " . self::db_table . "
                SET
                    mat_khau = :mat_khau,
                    ho_ten   = :ho_ten,
                    ma_lop   = :ma_lop
                WHERE
                    ma_sv    = :ma_sv
                LIMIT 0, 1";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([
                    ':ma_sv' => $ma_sv,
                    ':mat_khau' => $thong_tin['mat_khau'],
                    ':ho_ten' => $thong_tin['ho_ten'],
                    ':ma_lop' => $thong_tin['ma_lop'],
                ]);
            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }

        public function xoa(string $ma_sv): void
        {
            $sqlQuery
                = /** @lang text */
                "DELETE FROM
                    " . self::db_table . "
                WHERE
                    ma_sv = :ma_sv
                LIMIT 0, 1";

            try {
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute([':ma_sv' => $ma_sv]);
            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }
    }