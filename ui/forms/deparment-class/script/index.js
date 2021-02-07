const checkBox = document.querySelectorAll("input");
let checkBoxes1 = document.getElementsByName("academic_year");
let checkBoxes2 = document.getElementsByName("faculty");
let _class = [];

for (let i = 0; i < checkBox.length; i++)
{
    checkBox[i].addEventListener("change", function ()
    {
        if (!this.checked && this.value !== "all")
        {
            document.getElementsByClassName(this.name)[0].checked = false;
        }
        else if (this.checked && this.value !== "all")
        {
            let checkBoxes = document.getElementsByName(this.name);
            let flag = true;
            for (let j = 0; j < checkBoxes.length; j++)
            {
                if (!checkBoxes[j].checked)
                {
                    flag = false;
                    break;
                }
            }
            document.getElementsByClassName(this.name)[0].checked = flag
        }

        if (!isChecked())
        {
            reset();
            return;
        }

        getClass();
    });
}
function reset()
{
    document.querySelector(".class").innerHTML = "";
    _class = [];
}

function isChecked()
{
    let flag1 = false;
    let flag2 = false;
    for (let i=0; i<checkBoxes1.length; i++)
    {
        if (checkBoxes1[i].checked)
        {
            flag1 = true;
            break;
        }
    }
    for (let i=0; i<checkBoxes2.length; i++)
    {
        if (checkBoxes2[i].checked)
        {
            flag2 = true;
            break;
        }
    }

    return (flag1 && flag2);
}

function tickAll(src)
{
    let checkBoxes = document.getElementsByName(src.classList[0]);
    for (let i = 0; i < checkBoxes.length; i++)
    {
        if (checkBoxes[i].checked !== src.checked)
        {
            checkBoxes[i].checked = src.checked;
        }
    }
}


function getClass()
{
    let url;
    let academic_year = {};
    let faculty = {};
    let conditions  = [];
    let tableHead = [];

    if (all_academic_year.checked)
    {
        if (all_faculty.checked)
        {
            url = "../../../api-v2/department_class.php/class?academic_year=all&faculty=all";
            // tableHead = ["CK", "CNTT", "CT", "DDT", "GDQP", "GDTC", "KHCB", "KTXD", "LLCT", "VTKT"]
        }
        else
        {
            url = "../../../api-v2/department_class.php/getclass?academic_year=all";

            for (let i = 0; i < checkBoxes2.length; i++)
            {
                if (checkBoxes2[i].checked)
                {
                    faculty[checkBoxes2[i].value] = checkBoxes2[i].value;
                    // tableHead.push(checkBoxes2[i].value);
                }
            }
        }
    }
    else
    {
        if (all_faculty.checked)
        {
            url = "../../../api-v2/department_class.php/getclass?faculty=all";
            // tableHead = ["CK", "CNTT", "CT", "DDT", "GDQP", "GDTC", "KHCB", "KTXD", "LLCT", "VTKT"]


            for (let i = 0; i < checkBoxes1.length; i++)
            {
                if (checkBoxes1[i].checked)
                {
                    academic_year[checkBoxes1[i].value] = checkBoxes1[i].value;
                }
            }
        }
        else
        {
            url = "../../../api-v2/department_class.php/getclass";

            for (let i = 0; i < checkBoxes1.length; i++)
            {
                if (checkBoxes1[i].checked)
                {
                    academic_year[checkBoxes1[i].value] = checkBoxes1[i].value;
                }
            }

            for (let i = 0; i < checkBoxes2.length; i++)
            {
                if (checkBoxes2[i].checked)
                {
                    faculty[checkBoxes2[i].value] = checkBoxes2[i].value;
                    // tableHead.push(checkBoxes2[i].value);
                }
            }

            if (Object.entries(academic_year).length === 0 || Object.entries(faculty).length === 0)
            {
                return;
            }
        }
    }
    conditions = [academic_year, faculty];

    fetchData(url, conditions, tableHead);
}

function fetchData(url, conditions, tableHead)
{
    fetch(url, {
        method: "POST",
        body: JSON.stringify(conditions)
    })
        .then(function(response)
        {
            return response.json();
        })
        .then(function(responseAsJson)
        {
            createTable(responseAsJson, tableHead);
        })
        .catch(function(error)
        {
            console.log('Looks like there was a problem: \n', error);
        });
}

function createTable(data, tableHead)
{
    reset();

    if (data.length === 0)
    {
        return;
    }

    let html = "<p>"+data[0]["Academic_Year"]+"</p><table>";
    let tempAcademmic_Year = data[0]["Academic_Year"];
    let tempFaculty = "";
    let j = 0;

    for (let i=0; i<data.length; i++)
    {
        if (data[i]["Academic_Year"] !== tempAcademmic_Year)
        {
            for (; j<8; j++)
            {
                html += "<td></td>";
            }

            html += "</tr>";
            j = 0;
            html += "</table><p>"+data[i]["Academic_Year"]+"</p><table>";
            html += ""
            tempAcademmic_Year = data[i]["Academic_Year"];
            tempFaculty = "";
        }

        if (j === 0)
        {
            if (tempFaculty === data[i]["ID_Faculty"])
            {
                html += "<td></td>";
                j++;
            }
            else
            {
                tempFaculty = data[i]["ID_Faculty"];
                html += "<tr><td>"+tempFaculty+"</td>";
                html += "<td><input type=\"checkbox\" class=\""+tempAcademmic_Year+tempFaculty+" form-check-input\"";
                html += " value=\"all\" id=\""+tempAcademmic_Year+tempFaculty+"\" onclick=\"tickAllForClass(this)\">"
                html += "<label for=\""+tempAcademmic_Year+tempFaculty+"\" class=\"form-check-label\">Chọn tất cả</label></td>"
                j += 2;
            }
        }

        if (data[i]["ID_Faculty"] !== tempFaculty)
        {
            tempFaculty = data[i]["ID_Faculty"];

            for (; j<8; j++)
            {
                html += "<td></td>";
            }

            html += "<tr><td>"+tempFaculty+"</td>";
            html += "<td><input type=\"checkbox\" class=\""+tempAcademmic_Year+tempFaculty+" form-check-input\"";
            html += " value=\"all\" id=\""+tempAcademmic_Year+tempFaculty+"\" onclick=\"tickAllForClass(this)\">"
            html += "<label for=\""+tempAcademmic_Year+tempFaculty+"\" class=\"form-check-label\">Chọn tất cả</label></td>"
            j = 2;
        }
        html += "<td>";
        html += "<input type=\"checkbox\" class=\""+tempAcademmic_Year+tempFaculty;
        html += "\" name=\""+tempAcademmic_Year+tempFaculty+"\" value=\"";
        html += data[i]["ID_Class"]+"\" id=\""+data[i]["ID_Class"]+"\">"
        html += "<label for=\""+data[i]["ID_Class"]+"\">"+data[i]["ID_Class"]+"</label>";
        html += "</td>";

        if (j === 7)
        {
            j = 0;
            html += "</tr>"
            continue;
        }
        j++;
    }
    html += "</table>";
    document.querySelector(".class").innerHTML = html;

    createClass();
}

function createClass()
{
    let checkBoxClass = document.querySelectorAll("input");

    for (let i = 20; i < checkBoxClass.length; i++)
    {
        checkBoxClass[i].addEventListener("change", function ()
        {
            if (!this.checked && this.value !== "all")
            {
                _class.splice(-_class.lastIndexOf(this.value), 1);
                document.getElementsByClassName(this.name)[0].checked = false;
            }
            else if (this.checked && this.value !== "all")
            {
                _class.push(this.value);

                let checkBoxes = document.getElementsByName(this.name);
                let flag = true;
                for (let j = 0; j < checkBoxes.length; j++)
                {
                    if (!checkBoxes[j].checked)
                    {
                        flag = false;
                        break;
                    }
                }
                document.getElementsByClassName(this.name)[0].checked = flag
            }
        });
    }
}

function tickAllForClass(src)
{
    let checkBoxes = document.getElementsByName(src.classList[0]);
    console.log(checkBoxes.length)
    for (let i = 0; i < checkBoxes.length; i++)
    {
        if (checkBoxes[i].checked !== src.checked)
        {
            checkBoxes[i].checked = src.checked;
            if (src.checked)
            {
                _class.push(checkBoxes[i].value);
            }
            else
            {
                _class.splice(_class.lastIndexOf(checkBoxes[i].value), 1);
            }
        }
    }
}