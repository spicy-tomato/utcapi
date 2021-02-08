const checkBox = document.querySelectorAll('input')
let checkBoxes1 = document.getElementsByName('academic_year')
let checkBoxes2 = document.getElementsByName('faculty')

for (let i = 0; i < checkBox.length; i++) {
    checkBox[i].addEventListener('change', function () {
        if (!this.checked && this.value !== 'all') {
            document.getElementsByClassName(this.name)[0].checked = false
        }
        else if (this.checked && this.value !== 'all') {
            let checkBoxes = document.getElementsByName(this.name)
            let flag = true
            for (let j = 0; j < checkBoxes.length; j++) {
                if (!checkBoxes[j].checked) {
                    flag = false
                    break
                }
            }
            document.getElementsByClassName(this.name)[0].checked = flag
        }

        if (!isChecked()) {
            if (document.querySelector('table')) {
                document.querySelector('table').remove()
            }
            return
        }

        getClass()
    })
}

function isChecked() {
    let flag1 = false
    let flag2 = false
    for (let i = 0; i < checkBoxes1.length; i++) {
        if (checkBoxes1[i].checked) {
            flag1 = true
            break
        }
    }
    for (let i = 0; i < checkBoxes2.length; i++) {
        if (checkBoxes2[i].checked) {
            flag2 = true
            break
        }
    }

    return (flag1 && flag2)
}

function tickAll(src) {
    let checkBoxes = document.getElementsByName(src.classList[0])
    for (let i = 0; i < checkBoxes.length; i++) {
        checkBoxes[i].checked = src.checked
    }
}


function getClass() {
    let url = ''
    let academic_year = {}
    let faculty = {}
    let conditions = []
    let tableHead = []

    if (all_academic_year.checked) {
        if (all_faculty.checked) {
            url = '../../../api-v2/manage/get_department_class.php/class?academic_year=all&faculty=all'
            tableHead = ['CK', 'CNTT', 'CT', 'DDT', 'GDQP', 'GDTC', 'KHCB', 'KTXD', 'LLCT', 'VTKT']
        }
        else {
            url = '../../../api-v2/manage/get_department_class.php/getclass?academic_year=all'

            for (let i = 0; i < checkBoxes2.length; i++) {
                if (checkBoxes2[i].checked) {
                    faculty[checkBoxes2[i].value] = checkBoxes2[i].value
                    tableHead.push(checkBoxes2[i].value)
                }
            }
        }
    }
    else {
        if (all_faculty.checked) {
            url = '../../../api-v2/manage/get_department_class.php/getclass?faculty=all'
            tableHead = ['CK', 'CNTT', 'CT', 'DDT', 'GDQP', 'GDTC', 'KHCB', 'KTXD', 'LLCT', 'VTKT']


            for (let i = 0; i < checkBoxes1.length; i++) {
                if (checkBoxes1[i].checked) {
                    academic_year[checkBoxes1[i].value] = checkBoxes1[i].value
                }
            }
        }
        else {
            url = '../../../api-v2/manage/get_department_class.php/getclass'

            for (let i = 0; i < checkBoxes1.length; i++) {
                if (checkBoxes1[i].checked) {
                    academic_year[checkBoxes1[i].value] = checkBoxes1[i].value
                }
            }

            for (let i = 0; i < checkBoxes2.length; i++) {
                if (checkBoxes2[i].checked) {
                    faculty[checkBoxes2[i].value] = checkBoxes2[i].value
                    tableHead.push(checkBoxes2[i].value)
                }
            }

            if (Object.entries(academic_year).length === 0 || Object.entries(faculty).length === 0) {
                return
            }
        }
    }
    conditions = [academic_year, faculty]

    fetchData(url, conditions, tableHead)
}

function fetchData(url, conditions, tableHead) {
    fetch(url, {
        method: 'POST',
        body: JSON.stringify(conditions)
    })
        .then(function (response) {
            return response.json()
        })
        .then(function (responseAsJson) {
            createTable(responseAsJson, tableHead)
        })
        .catch(function (error) {
            console.log('Looks like there was a problem: \n', error)
        })
}

function createTable(data, tableHead) {
    if (document.querySelector('table')) {
        document.querySelector('table').remove()
    }

    let table = document.createElement('table')

    let html = '<tr>'
    for (let i = 0; i < tableHead.length; i++) {
        html += '<th>' + tableHead[i] + '</th>'
    }
    html += '</tr>'
    let aca_year = ''

    while (data.length !== 0) {
        html += '<tr>'
        for (let i = 0; i < tableHead.length; i++) {

            if (data.length > 0 && i === 0) {
                aca_year = data[0]['Academic_Year']
            }

            for (let j = 0; j < data.length; j++) {

                if (data[j]['Academic_Year'] !== aca_year) {
                    break
                }
                if (data[j]['ID_Faculty'] === tableHead[i]) {
                    html += '<td>'
                    html += '<input type="checkbox" class="class" name="class" value="'
                    html += data[j]['ID_Class'] + '" id="' + data[j]['ID_Class'] + '">'
                    html += '<label for="' + data[j]['ID_Class'] + '">' + data[j]['ID_Class'] + '</label>'
                    html += '</td>'
                    data.splice(j, 1)
                    j--
                    i++
                }
            }
            if (i < tableHead.length) {
                html += '<td>____________</td>'
            }
        }
        html += '</tr>'
    }
    table.innerHTML = html
    document.querySelector('.class').appendChild(table)
}