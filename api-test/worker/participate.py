class Participate:
    def __init__(
        self,
        ID_Module_Class,
        ID_Student,
        Process_Score=None,
        Test_Score=None,
        Theoretical_Score=None,
        Status_Studying=None,
    ) -> None:
        self.ID_Module_Class = ID_Module_Class
        self.ID_Student = ID_Student
        self.Process_Score = Process_Score
        self.Test_Score = Test_Score
        self.Theoretical_Score = Theoretical_Score
        self.Status_Studying = Status_Studying

    def __eq__(self, other: object) -> bool:
        if isinstance(other, Participate):
            return (
                self.ID_Module_Class == other.ID_Module_Class
                and self.ID_Student == other.ID_Student
            )

        return False

    def __ne__(self, other: object) -> bool:
        return not self.__eq__(other)

    def __lt__(self, other: object):
        if isinstance(other, Participate):
            return self.ID_Module_Class < other.ID_Module_Class or (
                self.ID_Module_Class == other.ID_Module_Class
                and self.ID_Student < other.ID_Student
            )

        return False

    def __str__(self) -> str:
        return "(" + ", ".join([self.ID_Student, self.ID_Module_Class]) + ")"

    def __repr__(self) -> str:
        return "(" + ", ".join([self.ID_Student, self.ID_Module_Class]) + ")"

    def __hash__(self) -> int:
        return hash(self.ID_Student + self.ID_Module_Class)

