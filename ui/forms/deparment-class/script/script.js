let sender
const fieldList = {
    title: 'Tiêu đề',
    content: 'Nội dung',
    typez: 'Loại thông báo'
}
const checkBox = document.querySelectorAll('input')
let checkBoxes1 = document.getElementsByName('academic_year')
let checkBoxes2 = document.getElementsByName('faculty')
let allClass = []
let selectedClass = []
let academicYears = []
let faculties = []

/*_________________________________________________*/

document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('all_academic_year').addEventListener('click', tickAllForAcademic_YearAndFaculty)
    document.getElementById('all_faculty').addEventListener('click', tickAllForAcademic_YearAndFaculty)
    document.getElementsByName('button')[0].addEventListener('click', trySendNotification)

    await fetchData()

    addEventForAcademic_YearAndFaculty()

    sender = await getSender()
})

/*_________________________________________________*/

function tickAllForAcademic_YearAndFaculty() {
    let checkBoxes = document.getElementsByName(this.classList[0])
    for (let i = 0; i < checkBoxes.length; i++) {
        if (checkBoxes[i].checked !== this.checked) {
            checkBoxes[i].checked = this.checked
        }
    }
}

async function fetchData() {
    try {
        const baseUrl = '../../../api-v2/manage/get_department_class.php'
        const init = {
            method: 'GET',
            cache: 'no-cache'
        }

        let response = await fetch(baseUrl, init)
        allClass = await response.json()

    } catch (e) {
        console.log(e)
    }
}

function addEventForAcademic_YearAndFaculty() {
    for (let i = 1; i < checkBox.length; i++) {
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
                reset()
                return
            }

            getConditions()
        })
    }
}

async function getSender() {
    const baseUrl = '../../shared/session.php?var=department_id'
    const init = {
        method: 'GET'
    }

    let response = await fetch(baseUrl, init)
    let responseAsJson = await response.json()

    return responseAsJson
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

function reset() {
    document.querySelector('.class').innerHTML = ''
    selectedClass = []
}

function getConditions() {
    academicYears = []
    faculties = []

    if (all_academic_year.checked) {
        if (all_faculty.checked) {
            academicYears = ['K54', 'K55', 'K56', 'K57', 'K58', 'K59', 'K60']
            faculties = ['CK', 'CNTT', 'CT', 'DDT', 'GDQP', 'GDTC', 'KHCB', 'KTXD', 'LLCT', 'VTKT']
        }
        else {
            academicYears = ['K54', 'K55', 'K56', 'K57', 'K58', 'K59', 'K60']

            for (let i = 0; i < checkBoxes2.length; i++) {
                if (checkBoxes2[i].checked) {
                    faculties.push(checkBoxes2[i].value)
                }
            }
        }
    }
    else {
        if (all_faculty.checked) {
            faculties = ['CK', 'CNTT', 'CT', 'DDT', 'GDQP', 'GDTC', 'KHCB', 'KTXD', 'LLCT', 'VTKT']

            for (let i = 0; i < checkBoxes1.length; i++) {
                if (checkBoxes1[i].checked) {
                    academicYears.push(checkBoxes1[i].value)
                }
            }
        }
        else {
            for (let i = 0; i < checkBoxes1.length; i++) {
                if (checkBoxes1[i].checked) {
                    academicYears.push(checkBoxes1[i].value)
                }
            }

            for (let i = 0; i < checkBoxes2.length; i++) {
                if (checkBoxes2[i].checked) {
                    faculties.push(checkBoxes2[i].value)
                }
            }
        }
    }

    getClass()
}

function getClass() {
    let data = {}

    for (let _class of allClass) {
        let {Academic_Year: academicYearOfClass, ID_Faculty: facultyOfClass, ID_Class: idOfClass} = _class

        if (academicYears.lastIndexOf(academicYearOfClass) !== -1 &&
            faculties.lastIndexOf(facultyOfClass) !== -1) {

            if (data[academicYearOfClass] === undefined) {
                data[academicYearOfClass] = {}
            }

            if (data[academicYearOfClass][facultyOfClass] === undefined) {
                data[academicYearOfClass][facultyOfClass] = []
            }

            data[academicYearOfClass][facultyOfClass].push(idOfClass)
        }
        createTable(data)
    }
}

function createTable(data) {
    reset()

    if (Object.entries(data).length === 0) {
        return
    }

    for (const [_academicYear, _faculty] of Object.entries(data)) {
        let area = createAcademicYearArea(_academicYear)
        let innerTable = createInnerTable()

        for (const [facultyElement, __class] of Object.entries(_faculty)) {
            let rowTag = createRow()
            let columnFacultyTag = createColumn()
            let facultyTag = createFacultyTag(facultyElement)
            let checkAllTag = createCheckAllTag(_academicYear, facultyElement)
            let columnCheckAllTag = createColumn()

            columnFacultyTag.appendChild(facultyTag)
            rowTag.appendChild(columnFacultyTag)

            columnCheckAllTag.appendChild(checkAllTag)
            rowTag.appendChild(columnCheckAllTag)

            let counter = 2
            for (const _singleClass of __class) {
                selectedClass.push(_singleClass)

                let classTag = createClassTag(_singleClass, _academicYear, facultyElement)
                let columnClassTag = createColumn()

                if (counter >= 8) {
                    innerTable.appendChild(rowTag)

                    rowTag = createRow()

                    rowTag.appendChild(createColumn())
                    rowTag.appendChild(createColumn())

                    counter = 2
                }
                columnClassTag.appendChild(classTag)
                rowTag.appendChild(columnClassTag)

                counter++
            }
            if (counter < 7) {
                for (; counter < 8; counter++) {
                    rowTag.appendChild(createColumn())
                }
            }

            innerTable.appendChild(rowTag)
        }

        document.querySelector('.class').appendChild(area)
        document.querySelector('.class').appendChild(innerTable)
    }

    addEventToClass()
    document.getElementsByClassName('class')[0].scrollIntoView(false)
}

function createAcademicYearArea(academicYear) {
    let tag = document.createElement('legend')
    tag.innerHTML = academicYear

    return tag
}

function createInnerTable() {
    let table = document.createElement('table')
    table.className = 'form-group'

    return table
}

function createRow() {
    return document.createElement('tr')
}

function createColumn() {
    return document.createElement('td')
}

function createFacultyTag(faculty) {
    let tag = document.createElement('div')
    tag.innerHTML = faculty
    tag.className = 'form-check form-check-inline'

    return tag
}

function createCheckAllTag(academicYear, faculty) {
    let tag = document.createElement('div')
    tag.className = 'form-check form-check-inline'

    let checkbox = document.createElement('input')
    checkbox.type = 'checkbox'
    checkbox.className = academicYear + faculty + ` form-check-input`
    checkbox.id = academicYear + faculty
    checkbox.value = 'all'
    checkbox.addEventListener('click', tickAllForClass)
    checkbox.checked = true

    let label = document.createElement('label')
    label.htmlFor = academicYear + faculty
    label.className = 'form-check-label text-nowrap'
    label.innerHTML = 'Chọn tất cả'

    tag.appendChild(checkbox)
    tag.appendChild(label)

    return tag
}

function createClassTag(_class, academicYear, faculty) {
    let tag = document.createElement('div')
    tag.className = 'form-check form-check-inline'

    let checkbox = document.createElement('input')
    checkbox.type = 'checkbox'
    checkbox.className = academicYear + faculty + ` form-check-input`
    checkbox.id = _class
    checkbox.name = academicYear + faculty
    checkbox.value = _class
    checkbox.checked = true

    let label = document.createElement('label')
    label.htmlFor = _class
    label.className = 'form-check-label text-nowrap'
    label.innerHTML = _class

    tag.appendChild(checkbox)
    tag.appendChild(label)

    return tag
}

function tickAllForClass() {
    let checkBoxes = document.getElementsByName(this.classList[0])

    for (let i = 0; i < checkBoxes.length; i++) {
        if (checkBoxes[i].checked !== this.checked) {
            checkBoxes[i].checked = this.checked
            if (this.checked) {
                selectedClass.push(checkBoxes[i].value)
            }
            else {
                selectedClass.splice(selectedClass.lastIndexOf(checkBoxes[i].value), 1)
            }
        }
    }
}

function addEventToClass() {
    let checkBoxClass = document.querySelectorAll('input')

    for (let i = 20; i < checkBoxClass.length; i++) {
        checkBoxClass[i].addEventListener('change', function () {
            if (!this.checked && this.value !== 'all') {
                selectedClass.splice(selectedClass.lastIndexOf(this.value), 1)

                document.getElementsByClassName(this.name)[0].checked = false
            }
            else if (this.checked && this.value !== 'all') {
                selectedClass.push(this.value)

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
        })
    }
}

async function postData(data) {
    const url = '../../../api-v2/manage/department_class_notification.php'

    const init = {
        method: 'POST',
        cache: 'no-cache',
        body: JSON.stringify(data)
    }

    const response = await fetch(url, init)
    let responseAsJson = await response.json()

    return responseAsJson
}

function canPostData(data) {
    for (const [field, fieldValue] of Object.entries(data.info)) {
        if (fieldValue === '') {
            alertify.error(`Trường "${fieldList[field]}" không được để trống!`)
                .delay(3)
                .dismissOthers()

            return false
        }
    }

    if (selectedClass.length === 0) {
        alertify.error('Checkbox chọn lớp không được để trống!')
            .delay(3)
            .dismissOthers()

        return false
    }

    return true
}

async function trySendNotification() {
    const data = {
        info: {
            title: title.value,
            content: content.value,
            typez: 'Type',
            sender: sender
        },
        class_list: selectedClass
    }

    if (canPostData(data)) {
        if (await postData(data) === 'OK') {
            alertify.confirm('Thêm thông báo thành công!')
                .setHeader('<i class="fas fa-info-circle"></i> Thông tin')
                .setting({
                    'labels':
                        {
                            ok: 'Tạo thông báo mới',
                            cancel: 'Về trang chủ'
                        },
                    'defaultFocusOff': true,
                    'maximizable': false,
                    'movable': false,
                    'pinnable': false,
                    'onok': () => window.location.reload(),
                    'oncancel': () => window.location.replace(('../../home/'))
                })

            document.getElementsByName('button')[0].removeEventListener('click', trySendNotification)
        }
        else {
            alertify.error('Có lỗi đã xảy ra, hãy thử lại sau!')
                .delay(3)
                .dismissOthers()
        }
    }
}
