<?php
    include_once "sinh_vien.php";

    class TKB
    {
        private const bang_lop_hoc_phan = "Module_Class";
        private const bang_tkb = "Schedules";
        private const bang_sinh_vien = "Student";
        private const bang_quan_he = "Participate";
        private const bang_hoc_phan = "Module";

        private string $ma_sv;
        private PDO $ket_noi;

        public function __construct(PDO $ket_noi, string $ma_sv)
        {
            $this->ket_noi = $ket_noi;
            $this->ma_sv   = $ma_sv;
        }

        public function hienThiTatCa(): array
        {
            $sqlQuery =
                "SELECT
                    tkb.ID_Module_Class,
                    hp.Module_Name,
                    tkb.ID_Room,
                    tkb.Shift_Schedules,
                    tkb.Day_Schedules
                FROM
                    " . self::bang_hoc_phan . " hp ,
                    " . self::bang_tkb . " tkb,
                    " . self::bang_sinh_vien . " sv,
                    " . self::bang_quan_he . " qh,
                    " . self::bang_lop_hoc_phan . " lhp
                WHERE
                        sv.ID_Student = :ma_sv
                    AND qh.ID_Student = :ma_sv
                    AND tkb.ID_Module_Class = qh.ID_Module_Class
                    AND tkb.ID_Module_Class = lhp.ID_Module_Class
                    AND hp.ID_Module = lhp.ID_Module";

            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute([':ma_sv' => $this->ma_sv]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }

        public function hienThi(string $from, string $to): array
        {
            $sqlQuery =
                "SELECT
                    hp.*, sv.*, qh.*
                FROM
                    " . self::bang_tkb . " hp,
                    " . self::bang_sinh_vien . " sv,
                    " . self::bang_quan_he . " qh
                WHERE
                        sv.ID_Student = :ma_sv
                    AND qh.ID_Student = :ma_sv
                    AND hp.ID_Module_Class = qh.ID_Module_Class
                    AND hp.Day_Schedules >= :from
                    AND hp.Day_Schedules <= :to";

            try {
                $stmt = $this->ket_noi->prepare($sqlQuery);
                $stmt->execute([
                    ':ma_sv' => $this->ma_sv,
                    ':from' => $from,
                    ':to' => $to]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $loi) {
                exit($loi->getMessage());
            }
        }
    }
