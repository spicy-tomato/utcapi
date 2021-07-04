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

participates_res = []
students_res = set()
exception_res = []

id_module_class = ""

###################################################################################################

# Get first index of sheet
# If index == 1, then `sheet` is first page of some module class
def firstIndex(sheet) -> int:
    i = 7
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


def addStudentsToParticipateSet(_list: list) -> None:
    for _student in _list:
        participates_res.append(
            Participate(ID_Module_Class=id_module_class, ID_Student=_student.ID_Student)
        )


###################################################################################################

file = r"../file_upload/" + file_name
workbook = xlrd.open_workbook(file)

got_module_class_id = False
prev_module_class_name = ""
students_in_prev_sheet = []

for sheet in workbook._sheet_list:
    first_index, first_row = firstIndex(sheet)

    is_first_page = True if first_index == 1 else False

    if is_first_page and not got_module_class_id and prev_module_class_name != "":
#         print(
#             prev_module_class_name, "\t\t\t\t", str(sheet).split(":")[0].split(" ")[-1]
#         )
        exception_res.append(prev_module_class_name)

    # Get module_class
    if is_first_page:
        # Get module class name at first page
        prev_module_class_name = sheet.cell(4, 0).value
        got_module_class_id = False

    elif not got_module_class_id:
        # Get id of module class
        id_module_class = sheet.cell(4, 0).value
        got_module_class_id = True

    # Get students in `sheet`
    students_in_sheet = readStudentData(sheet, first_row)

    # Add students in `sheet` to set
    students_res.update(students_in_sheet)

    # First page does not contain id of module class, then assign `student_in_sheet`
    # to `students_in_prev_sheet` and use it in next sheet
    if is_first_page:
        students_in_prev_sheet = students_in_sheet
    else:
        addStudentsToParticipateSet(students_in_prev_sheet)
        addStudentsToParticipateSet(students_in_sheet)

        students_in_prev_sheet = []


student_json = json.dumps(list(students_res), cls=MyEncoder)

participate_json = json.dumps(participates_res, cls=MyEncoder)

exception_json = json.dumps(exception_res, cls=MyEncoder)
if exception_json == '[""]':
    exception_json = "[]"

response_json = (
    '{"student_json": '
    + student_json
    + ', "participate_json": '
    + participate_json
    + ', "exception_json": '
    + exception_json
    + "}"
)

print(response_json, sep="\n")

# f = open(r"/home/snowflower/Documents/utcapi/worker/response_json.json", "w")
# f.write(response_json)
# f.close()
