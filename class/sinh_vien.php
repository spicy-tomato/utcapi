<?php

    class SinhVien
    {
        private const bang_csdl = "student";
        private PDO $ket_noi;

        public function __construct (PDO $ket_noi)
        {
            $this->ket_noi = $ket_noi;
        }

        public function timTatCa () : array
        {
            $sqlQuery
                = /** @lang text */
                "SELECT 
                    ID_Student,
                    Student_Name,
                    ID_class
                FROM 
                    " . self::bang_csdl;


            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch (PDOException $loi){
                exit($loi->getMessage());
            }
        }

        public function tim (string $ma_sv) : array
        {
            $sqlQuery
                = /** @lang text */
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
                $stmt->execute([ ':ma_sv' => $ma_sv ]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch (PDOException $loi){
                exit($loi->getMessage());
            }
        }

        public function them (array $thong_tin) : void
        {
            $sqlQuery
                = /** @lang text */
                "INSERT INTO
                    " . self::bang_csdl . "
                SET
                    ma_sv    = :ma_sv,
                    mat_khau = :mat_khau,
                    ho_ten   = :ho_ten,
                    ma_lop   = :ma_lop";

            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute([
                                   ':ma_sv'    => $thong_tin['ma_sv'],
                                   ':mat_khau' => $thong_tin['mat_khau'],
                                   ':ho_ten'   => $thong_tin['ho_ten'],
                                   ':ma_lop'   => $thong_tin['ma_lop'],
                               ]);
            }
            catch (PDOException $loi){
                exit($loi->getMessage());
            }
        }

        public function capNhat (string $ma_sv, array $thong_tin) : void
        {
            $sqlQuery
                = /** @lang text */
                "UPDATE 
                    " . self::bang_csdl . "
                SET
                    mat_khau = :mat_khau,
                    ho_ten   = :ho_ten,
                    ma_lop   = :ma_lop
                WHERE
                    ma_sv    = :ma_sv
                LIMIT 0, 1";

            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute([
                                   ':ma_sv'    => $ma_sv,
                                   ':mat_khau' => $thong_tin['mat_khau'],
                                   ':ho_ten'   => $thong_tin['ho_ten'],
                                   ':ma_lop'   => $thong_tin['ma_lop'],
                               ]);
            }
            catch (PDOException $loi){
                exit($loi->getMessage());
            }
        }

        public function xoa (string $ma_sv) : void
        {
            $sqlQuery
                = /** @lang text */
                "DELETE FROM
                    " . self::bang_csdl . "
                WHERE
                    ma_sv = :ma_sv
                LIMIT 0, 1";

            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute([ ':ma_sv' => $ma_sv ]);
            }
            catch (PDOException $loi){
                exit($loi->getMessage());
            }
        }
    }