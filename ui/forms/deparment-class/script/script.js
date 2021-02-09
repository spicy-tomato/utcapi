const checkBox = document.querySelectorAll('input')
let checkBoxes1 = document.getElementsByName('academic_year')
let checkBoxes2 = document.getElementsByName('faculty')
let allClass = [];
let selectedClass = [];
let academic_year = [];
let faculty = [];
let conditions = [];

fetch('../../../api-v2/get_department_class.php')
    .then(function (response) {
        return response.json()
    })
    .then(function (responseAsJson) {
        allClass = responseAsJson;
    })
    .catch(function (error) {
        console.log('Looks like there was a problem: \n', error)
    })


for (let i = 0; i < checkBox.length; i++) {
    checkBox[i].addEventListener('change', function () {
        if (!this.checked && this.value !== 'all') {
            document.getElementsByClassName(this.name)[0].checked = false
        } else if (this.checked && this.value !== 'all') {
            let checkBoxes = document.getElementsByName(this.name)
            let flag = true;
            for (let j = 0; j < checkBoxes.length; j++) {
                if (!checkBoxes[j].checked) {
                    flag = false;
                    break;
                }
            }
            document.getElementsByClassName(this.name)[0].checked = flag;
        }

        if (!isChecked()) {
            reset();
            return;
        }

        getConditions();
    })
}

function reset() {
    document.querySelector(".class").innerHTML = "";
    selectedClass = [];
}

function isChecked() {
    let flag1 = false;
    let flag2 = false;
    for (let i = 0; i < checkBoxes1.length; i++) {
        if (checkBoxes1[i].checked) {
            flag1 = true;
            break;
        }
    }
    for (let i = 0; i < checkBoxes2.length; i++) {
        if (checkBoxes2[i].checked) {
            flag2 = true;
            break;
        }
    }

    return (flag1 && flag2);
}

function tickAll(src) {
    let checkBoxes = document.getElementsByName(src.classList[0])
    for (let i = 0; i < checkBoxes.length; i++) {
        if (checkBoxes[i].checked !== src.checked) {
            checkBoxes[i].checked = src.checked;
        }
    }
}


function getConditions() {
    academic_year = [];
    faculty = [];
    conditions = [];

    if (all_academic_year.checked) {
        if (all_faculty.checked) {
            academic_year = ["K54", "K55", "K56", "K57", "K58", "K59", "K60"]
            faculty = ['CK', 'CNTT', 'CT', 'DDT', 'GDQP', 'GDTC', 'KHCB', 'KTXD', 'LLCT', 'VTKT']
        } else {
            academic_year = ["K54", "K55", "K56", "K57", "K58", "K59", "K60"]

            for (let i = 0; i < checkBoxes2.length; i++) {
                if (checkBoxes2[i].checked) {
                    faculty.push(checkBoxes2[i].value);
                }
            }
        }
    } else {
        if (all_faculty.checked) {
            faculty = ['CK', 'CNTT', 'CT', 'DDT', 'GDQP', 'GDTC', 'KHCB', 'KTXD', 'LLCT', 'VTKT']

            for (let i = 0; i < checkBoxes1.length; i++) {
                if (checkBoxes1[i].checked) {
                    academic_year.push(checkBoxes1[i].value);
                }
            }
        } else {
            for (let i = 0; i < checkBoxes1.length; i++) {
                if (checkBoxes1[i].checked) {
                    academic_year.push(checkBoxes1[i].value);
                }
            }

            for (let i = 0; i < checkBoxes2.length; i++) {
                if (checkBoxes2[i].checked) {
                    faculty.push(checkBoxes2[i].value);
                }
            }

            if (Object.entries(academic_year).length === 0 || Object.entries(faculty).length === 0) {
                return
            }
        }
    }
    conditions = [academic_year, faculty]

    getClass()
}

function getClass() {
    let classWithConditions = [];

    for (let i = 0; i < allClass.length; i++) {
        if (conditions[0].lastIndexOf(allClass[i]["Academic_Year"]) !== -1 &&
            conditions[1].lastIndexOf(allClass[i]["ID_Faculty"]) !== -1) {
            classWithConditions.push([allClass[i]["ID_Class"], allClass[i]["Academic_Year"], allClass[i]["ID_Faculty"]]);
        }
    }
    createTable(classWithConditions)
}

/*
    function createTable()
    @data[x][0] return value of ID_Class
    @data[x][0] return value of Academic_Year
    @data[x][0] return value of ID_Faculty
 */

function createTable(data) {
    reset();

    if (data.length === 0) {
        return;
    }

    let html = "<legend>" + data[0][1] + "</legend><table>";
    let tempAcademmic_Year = data[0][1];
    let tempFaculty = "";
    let j = 0;

    for (let i = 0; i < data.length; i++) {
        if (data[i][1] !== tempAcademmic_Year) {
            for (; j < 8; j++) {
                html += "<td></td>";
            }
            j = 0;

            html += "</tr>";
            html += "</table><legend>" + data[i][1] + "</legend><table>";
            tempAcademmic_Year = data[i][1];
            tempFaculty = "";
        }

        if (j === 0) {
            if (tempFaculty === data[i][2]) {
                html += "<td></td>";
                j++;
            } else {
                tempFaculty = data[i][2];
                html += "<tr><td><div class=\"form-check form-check-inline\">" + tempFaculty + "</div></td>";
                html += "<td><div class=\"form-check form-check-inline\">";
                html += "<input type=\"checkbox\" ";
                html += "class=\"" + tempAcademmic_Year + tempFaculty + " form-check-input\"";
                html += "id=\"" + tempAcademmic_Year + tempFaculty + "\" ";
                html += " value=\"all\" ";
                html += "\" onclick=\"tickAllForClass(this)\">";
                html += "<label for=\"" + tempAcademmic_Year + tempFaculty + "\" ";
                html += "class=\"form-check-label\">Chọn tất cả</label>";
                html += "</div></td>";
                j += 2;
            }
        }

        if (data[i][2] !== tempFaculty) {
            tempFaculty = data[i][2];

            for (; j < 8; j++) {
                html += "<td></td>";
            }

            html += "<tr><td><div class=\"form-check form-check-inline\">" + tempFaculty + "</div></td>";
            html += "<td><div class=\"form-check form-check-inline\">";
            html += "<input type=\"checkbox\" ";
            html += "class=\"" + tempAcademmic_Year + tempFaculty + " form-check-input\"";
            html += "id=\"" + tempAcademmic_Year + tempFaculty + "\" ";
            html += "value=\"all\" ";
            html += "\" onclick=\"tickAllForClass(this)\">";
            html += "<label for=\"" + tempAcademmic_Year + tempFaculty + "\" ";
            html += "class=\"form-check-label\">Chọn tất cả</label>";
            html += "</div></td>";
            j = 2;
        }

        html += "<td><div class=\"form-check form-check-inline\">";
        html += "<input type=\"checkbox\" ";
        html += "class=\"" + tempAcademmic_Year + tempFaculty + "\" ";
        html += "id=\"" + data[i][0] + "\"";
        html += "name=\"" + tempAcademmic_Year + tempFaculty + "\" ";
        html += "value=\"" + data[i][0] + "\">";
        html += "<label for=\"" + data[i][0] + "\" ";
        html += "class=\"form-check-label\">" + data[i][0] + "</label></div></td>";

        if (j === 7) {
            j = 0;
            html += "</tr>"
            continue;
        }
        j++;
    }
    html += "</table>";
    document.querySelector(".class").innerHTML = html;

    addEventToClass();
}

function addEventToClass() {
    let checkBoxClass = document.querySelectorAll("input");

    for (let i = 20; i < checkBoxClass.length; i++) {
        checkBoxClass[i].addEventListener("change", function () {
            if (!this.checked && this.value !== "all") {
                selectedClass.splice(selectedClass.lastIndexOf(this.value), 1);
                document.getElementsByClassName(this.name)[0].checked = false;
            } else if (this.checked && this.value !== "all") {
                selectedClass.push(this.value);

                let checkBoxes = document.getElementsByName(this.name);
                let flag = true;
                for (let j = 0; j < checkBoxes.length; j++) {
                    if (!checkBoxes[j].checked) {
                        flag = false;
                        break;
                    }
                }
                document.getElementsByClassName(this.name)[0].checked = flag
            }
        });
    }
}

function tickAllForClass(src) {
    let checkBoxes = document.getElementsByName(src.classList[0]);

    for (let i = 0; i < checkBoxes.length; i++) {
        if (checkBoxes[i].checked !== src.checked) {
            checkBoxes[i].checked = src.checked;
            if (src.checked) {
                selectedClass.push(checkBoxes[i].value);
            } else {
                selectedClass.splice(selectedClass.lastIndexOf(checkBoxes[i].value), 1);
            }
        }
    }
}