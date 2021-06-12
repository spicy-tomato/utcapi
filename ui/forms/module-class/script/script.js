import {autoFillTemplate, resetInputDate, changeStatusButton} from '../../shared_form_functions.js'
import {getSender, fetchData} from '../../../script/shared_functions.js'
import {postDataAndRaiseAlert} from '../../../script/alerts.js'

let moduleClassIdList = []
let sender

const moduleClassId = $('#module-class-id')
const fieldList = {
    title: 'Tiêu đề',
    content: 'Nội dung',
    typez: 'Loại thông báo'
}

const templateNoti = {
    study: {
        title: 'Học tập',
        content: 'Nội dung thông báo học tập',
        typez: 0
    },
    fee: {
        title: 'Học phí',
        content: 'Nội dung thông báo học phí',
        typez: 1
    },
    extracurricular: {
        title: 'Thông báo ngoại khóa',
        content: 'Nội dung thông báo ngoại khóa',
        typez: 2
    },
    social_payment: {
        title: 'Chi trả xã hội',
        content: 'Nội dung thông báo chi trả xã hội',
        typez: 3
    },
    others: {
        title: 'Thông báo khác',
        content: 'Nội dung thông báo khác',
        typez: 4
    }
}

/*_________________________________________________*/

document.addEventListener('DOMContentLoaded', async () => {
    const CustomSelectionAdapter = $.fn.select2.amd.require('select2/selection/customSelectionAdapter')

    sender = await getSender(2)

    await loadData()

    document.getElementById('submit_btn').addEventListener('click', trySendNotification)
    document.getElementById('template').addEventListener('change', fillForms)
    document.getElementsByName('reset_button')[0].addEventListener('click', resetInputDate)
    document.getElementsByName('reset_button')[0].addEventListener('click', changeStatusButton)
    document.getElementsByName('reset_button')[1].addEventListener('click', resetInputDate)
    document.getElementsByName('reset_button')[1].addEventListener('click', changeStatusButton)
    document.getElementById('time-start').addEventListener('change', changeStatusButton)
    document.getElementById('time-end').addEventListener('change', changeStatusButton)

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

async function loadData() {
    let data = await fetchData('../../../api-v2/web/get_module_class.php')

    data.forEach((row, index) => {
        moduleClassIdList.push({id: index, text: row['ID_Module_Class']})
    })
}

/*_________________________________________________*/

function getClassList() {
    let selectedId = moduleClassId.val()

    if (selectedId.length === 0) {
        return []
    }

    let selectedClasses = selectedId.map((_class) => moduleClassIdList[_class].text)
    return selectedClasses
}

/*_________________________________________________*/

//  Display error if there are some unfulfilled fields
function getInvalidField(data) {
    for (const [field, fieldValue] of Object.entries(data.info)) {
        if (fieldValue === '' && fieldList[field] !== undefined) {
            return fieldList[field]
        }
    }

    if (data.class_list.length === 0) {
        return 'Mã học phần'
    }

    return null
}

/*_________________________________________________*/

async function trySendNotification() {
    let timeStartRsBtClass = document.getElementsByClassName('time-start')[0].classList[3]
    let timeEndRsBtClass = document.getElementsByClassName('time-end')[0].classList[3]

    const data = {
        info: {
            title: $('#title').val(),
            content: $('#content').val(),
            typez: $('#type').val(),
            time_start: timeStartRsBtClass === 'disable' ? '' : $('#time-start').val(),
            time_end: timeEndRsBtClass === 'disable' ? '' : $('#time-end').val(),
            sender: sender
        },
        class_list: getClassList()
    }

    const baseUrl = '../../../api-v2/web/push_module_class_notification.php'

    let madeRequest = await postDataAndRaiseAlert(baseUrl, data, getInvalidField, 'Tạo thông báo mới', '../../home/')

    if (madeRequest) {
        document.getElementById('submit_btn').removeEventListener('click', trySendNotification)
    }
}

/*-------------------------------------------------*/

function fillForms() {
    autoFillTemplate(templateNoti[template.value])
}