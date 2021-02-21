import { postDataAndRaiseAlert } from '../../alerts.js'
import { getSender, fetchData } from '../../shared.js'

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

    document.getElementById('submit_btn').addEventListener('click', trySendNotification)

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
    let data = await fetchData('../../../api-v2/manage/get_module_class.php')

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
        if (fieldValue === '') {
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
    const data = {
        info: {
            title: $('#title').val(),
            content: $('#content').val(),
            typez: 'Type',
            sender: sender
        },
        class_list: getClassList()
    }

    const baseUrl = '../../../api-v2/manage/module_class_notification.php'

    let madeRequest = await postDataAndRaiseAlert(baseUrl, data, getInvalidField)

    if (madeRequest) {
        document.getElementById('submit_btn').removeEventListener('click', trySendNotification)
    }
}
