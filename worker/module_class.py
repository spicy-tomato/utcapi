class ModuleClass:
    def __init__(
        self,
        ID_Module_Class,
        Module_Class_Name,
        ID_Module,
        School_Year,
        Number_Plan=None,
        Number_Reality=None,
        ID_Teacher=None,
    ) -> None:
        self.ID_Module_Class = ID_Module_Class
        self.Module_Class_Name = Module_Class_Name
        self.Number_Plan = Number_Plan
        self.Number_Reality = Number_Reality
        self.School_Year = School_Year
        self.ID_Module = ID_Module
        self.ID_Teacher = ID_Teacher

    def __eq__(self, other: object) -> bool:
        if isinstance(other, ModuleClass):
            return self.ID_Module_Class == other.ID_Module_Class

        return False

    def __str__(self) -> str:
        return (
            "("
            + ", ".join(
                [
                    self.ID_Module_Class,
                    self.Module_Class_Name,
                    self.ID_Module,
                    self.School_Year,
                ]
            )
            + ")"
        )

    def __str__(self) -> str:
        return (
            "("
            + ", ".join(
                [
                    self.ID_Module_Class,
                    self.Module_Class_Name,
                    self.ID_Module,
                    self.School_Year,
                ]
            )
            + ")"
        )
