import sys
import xlrd
import json

from datetime import datetime, timedelta

from module_class import ModuleClass
from my_encoder import MyEncoder
from participate import Participate
from schedule import Schedule
from student import Student

###################################################################################################

file_name = sys.argv[1]

module_classes_res = []
modules_res = set()
participates_res = []
schedules_res = []
students_res = set()

is_first_page = True
got_module_class = False

id_module_class = ""
module = ""
school_year = ""
module_class_name = ""

students_in_prev_sheet = []

###################################################################################################

# Get first index of sheet
# If index == 1, then `sheet` is first page of some module class
def firstIndex(sheet) -> int:
    i = 0
    while True:
        row_index = sheet.cell(i, 0).value

        if type(row_index) == float or type(row_index) == int:
            return int(row_index), i
        i += 1


# Read student data
def readStudentData(sheet, i) -> list:
    student_list = []

    while True:
        row_index = sheet.cell(i, 0).value

        if type(row_index) != float and type(row_index) != int:
            return student_list

        student_class = sheet.cell(i, 1).value
        student_id = sheet.cell(i, 2).value
        student_name = sheet.cell(i, 3).value + " " + sheet.cell(i, 4).value
        student_dob = sheet.cell(i, 5).value

        student_list.append(
            Student(
                ID_Student=student_id,
                Student_Name=student_name,
                ID_Class=student_class,
                DoB=student_dob,
            )
        )

        i += 1


def addStudentListToSet(_list: list) -> None:
    for _student in _list:
        participates_res.append(
            Participate(ID_Module_Class=id_module_class, ID_Student=_student.ID_Student)
        )


def addSchedule(sheet, id_module_class) -> None:
    def parseDate(string) -> datetime:
        try:
            return datetime.strptime(string, "%d/%m/%Y")
        except:
            return None

    def parseInt(string) -> int:
        try:
            return int(string)
        except:
            return None

    i = 6

    while True:
        cell_str = sheet.cell(i, 2).value

        days = cell_str.split()
        start_day = parseDate(days[1])
        end_day = parseDate(days[3])

        while True:
            i += 1

            cell_str = sheet.cell(i, 2).value

            if cell_str == "":
                return

            cell_str_arr = cell_str.split()

            dow = cell_str_arr[1]

            if parseInt(dow) == None:
                break

            curr_day = start_day + timedelta(days=int(dow) - 2)

            shift = int(cell_str_arr[3].split(",")[1]) % 3 + 1
            room = cell_str.split(", ")[1].split()[0]

            while curr_day <= end_day:
                s = Schedule(
                    ID_Module_Class=id_module_class,
                    ID_Room=room,
                    Shift_Schedules=shift,
                    Day_Schedules=str(curr_day),
                )

                schedules_res.append(s)

                # print(s)

                curr_day = curr_day + timedelta(days=7)


###################################################################################################

file = r"../file_upload/" + file_name
workbook = xlrd.open_workbook(file)

got_schedule = False

for sheet in workbook._sheet_list:
    first_index, first_row = firstIndex(sheet)

    is_first_page = True if first_index == 1 else False

    # Get module_class
    if is_first_page:
        # Get module class name at first page
        module_class_name = sheet.cell(4, 0).value

        # Get school year
        school_year = module_class_name.split()[-2].split("-")
        school_year = "-".join(school_year[1:])

        # Add new module
        module_name = sheet.cell(5, 0).value.split()[2:]
        module_name = " ".join(module_name)

        modules_res.add(module_name)

        got_module_class = False

    elif not got_module_class:
        # Get id of module class
        id_module_class = sheet.cell(4, 0).value

        # Get id of module
        id_module = sheet.cell(4, 0).value.split()[0].split("-")[0]

        # Add module class to set
        module_class = ModuleClass(
            ID_Module_Class=id_module_class,
            Module_Class_Name=module_class_name,
            School_Year=school_year,
            ID_Module=id_module,
        )

        module_classes_res.append(module_class)

        got_module_class = True

    # Get students in `sheet`
    students_in_sheet = readStudentData(sheet, first_row)

    # Add students in `sheet` to set
    students_res.update(students_in_sheet)

    # First page does not contain id of module class, then assign `student_in_sheet`
    # to `students_in_prev_sheet` and use it in next sheet
    if is_first_page:
        students_in_prev_sheet = students_in_sheet
        got_schedule = False
    else:
        addStudentListToSet(students_in_prev_sheet)
        addStudentListToSet(students_in_sheet)

        students_in_prev_sheet = []

    # Add schedules
    if not is_first_page and not got_schedule:
        addSchedule(sheet, id_module_class)
        got_schedule = True

    is_first_page = False

student_json = json.dumps(list(students_res), cls=MyEncoder)

module_class_json = json.dumps(module_classes_res, cls=MyEncoder)

module_json = json.dumps(list(modules_res), cls=MyEncoder)

participate_json = json.dumps(participates_res, cls=MyEncoder)

# print(*schedules_res, sep="\n")
schedule_json = json.dumps(schedules_res, cls=MyEncoder)

response_json = (
    '{"student_json": '
    + student_json
    + ', "module_class_json": '
    + module_class_json
    + ', "module_json": '
    + module_json
    + ', "participate_json": '
    + participate_json
    + ', "schedule_json": '
    + schedule_json
    + "}"
)

print(response_json)

# f = open(r"d:\xampp\htdocs\utc-student-app-excel-reader\response_json.json", "w")
# f.write(response_json)
# f.close()
