class Module:
    def __init__(
        self,
        ID_Module,
        Module_Name,
        Credit,
        Semester,
        Theory,
        Practice,
        Project,
        Optionz,
        ID_Department,
    ) -> None:
        self.ID_Module = ID_Module
        self.Module_Name = Module_Name
        self.Credit = Credit
        self.Semester = Semester
        self.Theory = Theory
        self.Practice = Practice
        self.Project = Project
        self.Optionz = Optionz
        self.ID_Department = ID_Department

    def __eq__(self, other: object) -> bool:
        if isinstance(other, Module):
            return self.ID_Module == other.ID_Module

        return False
