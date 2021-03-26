class Schedule:
    def __init__(
        self,
        ID_Module_Class,
        ID_Room,
        Shift_Schedules,
        Day_Schedules,
        Number_Student=None,
    ) -> None:
        self.ID_Module_Class = ID_Module_Class
        self.ID_Room = ID_Room
        self.Shift_Schedules = Shift_Schedules
        self.Day_Schedules = Day_Schedules
        self.Number_Student = Number_Student

    def __str__(self) -> str:
        return (
            "("
            + ", ".join(
                [
                    self.ID_Module_Class,
                    self.ID_Room,
                    str(self.Shift_Schedules),
                    str(self.Day_Schedules),
                    str(self.Number_Student),
                ]
            )
            + ")"
        )

    def __repr__(self) -> str:
        return (
            "("
            + ", ".join(
                [
                    self.ID_Module_Class,
                    self.ID_Room,
                    str(self.Shift_Schedules),
                    str(self.Day_Schedules),
                    str(self.Number_Student),
                ]
            )
            + ")"
        )

    def __hash__(self) -> int:
        return hash(
            self.ID_Module_Class
            + self.ID_Room
            + self.Shift_Schedules
            + self.Day_Schedules
        )
