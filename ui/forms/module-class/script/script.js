let moduleClassIdList = []
let sender

const moduleClassId = $('#module-class-id')
const fieldList = {
    title: 'Tiêu đề',
    content: 'Nội dung',
    typez: 'Loại thông báo'
}

/*_________________________________________________*/

document.addEventListener('DOMContentLoaded', async () => {
    const CustomSelectionAdapter = $.fn.select2.amd.require('select2/selection/customSelectionAdapter')

    sender = await getSender()
    await loadData()
    document.getElementById('submit').addEventListener('click', trySendNotification)

    //  Display selected tags
    moduleClassId.select2({
        data: moduleClassIdList,
        selectionAdapter: CustomSelectionAdapter,
        allowClear: false,
        selectionContainer: $('#list'),
        theme: 'bootstrap4'
    })
})

/*_________________________________________________*/

//  Get data from database
async function fetchData() {
    const baseUrl = '../../../api-v2/manage/get_module_class.php'
    const init = {
        method: 'GET',
        cache: 'no-cache'
    }

    let response = await fetch(baseUrl, init)
    let responseJson = await response.json()

    return responseJson
}

async function loadData() {
    let data = await fetchData()

    data.forEach((row, index) => {
        moduleClassIdList.push({id: index, text: row['ID_Module_Class']})
    })
}

/*_________________________________________________*/

async function getSender() {
    try {
        const baseUrl = '../../shared/session.php?var=department_id'
        const init = {
            method: 'GET',
            cache: 'no-cache'
        }

        const response = await fetch(baseUrl, init)
        const responseJson = await response.json()

        return responseJson

    } catch (e) {
        console.log(e)
    }
}

function getClassList() {
    let selectedId = moduleClassId.val()

    if (selectedId.length === 0) {
        return
    }

    let selectedClasses = selectedId.map((_class) => moduleClassIdList[_class].text)
    return selectedClasses
}

const varToString = varObj => Object.keys(varObj)[1]

//  Display error if there are some unfulfilled fields
function getInvalidField(data) {
    for (const [field, fieldValue] of Object.entries(data.info)) {
        if (fieldValue === '') {
            return fieldList[field]
        }
    }

    if (data.class_list === undefined) {
        return 'Mã học phần'
    }

    return null
}

/*_________________________________________________*/

//  Send notification info
async function postData(data) {
    const url = '../../../api-v2/manage/module_class_notification.php'

    const init = {
        method: 'POST',
        cache: 'no-cache',
        body: JSON.stringify(data)
    }

    const response = await fetch(url, init)
    const responseJson = await response.json()

    return responseJson
}

async function trySendNotification() {
    const data = {
        info: {
            title: $('#title').val(),
            content: $('#content').val(),
            typez: 'Type',
            sender: sender
        },
        class_list: getClassList()
    }

    let invalidField = getInvalidField(data)
    if (invalidField !== null) {
        raiseEmptyFieldError(invalidField)
        return
    }

    const response = await postData(data)
    if (response.toString() === 'OK') {
        raiseSuccess()
    }
    else {
        raiseBackEndError()
    }

    document.getElementById('submit').removeEventListener('click', trySendNotification)
}

function raiseSuccess() {
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
}

function raiseEmptyFieldError(field) {
    alertify.error(`Trường "${field}" không được để trống!`)
        .delay(3)
        .dismissOthers()
}

function raiseBackEndError() {
    alertify.error('Có lỗi đã xảy ra, hãy thử lại sau!')
        .delay(3)
        .dismissOthers()
}
