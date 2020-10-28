<?php
    include "./sinh_vien.php";

    class TKB
    {
        private const bang_hoc_phan  = "Lop_hoc_phan";
        private const bang_sinh_vien = "Sinh_vien";
        private PDO      $ket_noi;

        public function __construct (PDO $ket_noi)
        {
            $this->ket_noi = $ket_noi;
        }

        // SUA O DAY

        public function hienThi (string $ma_sv, string $nam_hoc, int $hoc_ky) : array
        {
            $sqlQuery
                = /** @lang MySQL */
                "SELECT
                    hp.*, sv.*
                FROM
                    " . self::bang_hoc_phan . " hp,
                    " . self::bang_sinh_vien . " sv
                WHERE
                        ma_sv = :ma_sv
                    AND sv.ma_lop_hoc_phan = hp.ma_lop_hoc_phan
                    AND hp.nam_hoc = :nam_hoc
                    AND hp.hoc_ky = :hoc_ky";

            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute([
                                   ':ma_sv'   => $ma_sv,
                                   ':nam_hoc' => $nam_hoc,
                                   ':hoc_ky'  => $hoc_ky ]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch (PDOException $loi){
                exit($loi->getMessage());
            }
        }
    }
