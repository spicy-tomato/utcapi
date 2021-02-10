let moduleClassIdList = []
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

    await loadData()

    //  Display selected tags
    moduleClassId.select2({
        data: moduleClassIdList,
        selectionAdapter: CustomSelectionAdapter,
        allowClear: false,
        selectionContainer: $('#list')
    })
})

//  Add selected module classes to form
function addClassToForm(){
    let seletedId = moduleClassId.val()

    if (seletedId.length === 0){
        return
    }

    let selectedClasses = seletedId.reduce((accumulator, currentValue) => currentValue[currentValue]['text'])
    console.log(selectedClasses)
}

//  Send notification info
async function postData() {
    const init = {
        method: 'POST',
        cache: 'no-cache',
        body: JSON.stringify()
    }
    const reponse = await fetch(url)
}