let moduleClassIdList = []
let sender
const moduleClassId = $('#module-class-id')

//  Get data from database
async function fetchData() {
    const baseUrl = '../../../api-v2/manage/get_module_class.php'
    const init = {
        method: 'GET',
        cache: 'no-cache'
    }

    let response = await fetch(baseUrl, init)
        .then((response) => response.json())

    return response
}

async function loadData() {
    let data = await fetchData()

    data.forEach((row, index) => {
        moduleClassIdList.push({id: index, text: row['ID_Module_Class']})
    })
}

$(document).ready(async () => {
    const CustomSelectionAdapter = $.fn.select2.amd.require('select2/selection/customSelectionAdapter')

    sender = await getSender()
    await loadData()
    document.getElementById('submit').addEventListener('click', function (){
        tryPostData()
    })

    //  Display selected tags
    moduleClassId.select2({
        data: moduleClassIdList,
        selectionAdapter: CustomSelectionAdapter,
        allowClear: false,
        selectionContainer: $('#list')
    })
})

/*_________________________________________________*/

async function getSender() {
    const baseUrl = '../../shared/session.php?var=department_id'
    const init = {
        method: 'GET'
    }

    let response = await fetch(baseUrl, init)
        .then((response) => response.json())

    return response
}

function getClassList() {
    let seletedId = moduleClassId.val()

    if (seletedId.length === 0) {
        return
    }

    let selectedClasses = seletedId.map((_class) => moduleClassIdList[_class].text)
    return selectedClasses
}

//  Send notification info
async function postData(data) {
    const url = '../../../api-v2/manage/module_class_notification.php'

    const init = {
        method: 'POST',
        cache: 'no-cache',
        body: JSON.stringify(data)
    }

    const response = await fetch(url, init)
        .then((response) => response.json())

    return response
}

function tryPostData() {
    const data = {
        info: {
            title: $('#title').val(),
            content: $('#content').val(),
            typez: 'Type',
            sender: sender
        },
        class_list: getClassList()
    }

    let shouldPostData = true
    console.log(data)

    for (const [field, fieldValue] of Object.entries(data.info)) {
        if (fieldValue === '') {
            toastr.info('Trường ' + field + ' không được để trống!', {
                "debug": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "fadeIn": 300,
                "fadeOut": 1000,
                "timeOut": 5000,
                "extendedTimeOut": 1000
            })
            return
        }
    }

    if (shouldPostData) {
        postData(data).then(response => console.log(response))
    }
}