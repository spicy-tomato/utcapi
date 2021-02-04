<?php


class DepartmentClass
{
    private const bang_csdl = "class";
    private PDO $ket_noi;

    public function __construct(PDO $ket_noi)
    {
        $this->ket_noi = $ket_noi;
    }

    public function getALL(): array
    {
        $sql = "select Academic_Year, ID_Faculty, ID_Class from " . self::bang_csdl ;
        $sql .= " order by Academic_Year asc, ID_Class asc";
        $stmt = $this->ket_noi->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getALLAcademic_Year(): array
    {
        $this->file = json_decode(file_get_contents("php://input"), true);
        $str = "";
        foreach ($this->file[1] as $item)
        {
            $arr[] = $item;
            $str .= "?,";
        }
        $str= rtrim($str, ",");

        $sql = "select Academic_Year, ID_Faculty, ID_Class from " . self::bang_csdl . " where ";
        $sql .= "ID_Faculty in (" . $str . ") order by Academic_Year asc, ID_Class asc";
        $stmt = $this->ket_noi->prepare($sql);
        $stmt->execute($arr);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getALlFaculty(): array
    {
        $this->file = json_decode(file_get_contents("php://input"), true);
        $str = "";
        foreach ($this->file[0] as $item)
        {
            $arr[] = $item;
            $str .= "?,";
        }
        $str= rtrim($str, ",");

        $sql = "select Academic_Year, ID_Faculty, ID_Class from " . self::bang_csdl . " where ";
        $sql .= "Academic_Year in (" . $str . ") order by Academic_Year asc, ID_Class asc";
        $stmt = $this->ket_noi->prepare($sql);
        $stmt->execute($arr);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get(): array
    {
        $this->file = json_decode(file_get_contents("php://input"), true);
        $str = ["", ""];
        $i = 0;

        foreach ($this->file as $items)
        {
            foreach ($items as $item)
            {
                $arr[] = $item;
                $str[$i] .= "?,";
            }
            $str[$i] = rtrim($str[$i], ",");
            $i++;
        }

        $sql = "select Academic_Year, ID_Faculty, ID_Class from " . self::bang_csdl . " where Academic_Year in (" .$str[0];
        $sql .= ") and ID_Faculty in (" . $str[1] . ") order by Academic_Year asc, ID_Class asc";
        $stmt = $this->ket_noi->prepare($sql);
        $stmt->execute($arr);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}