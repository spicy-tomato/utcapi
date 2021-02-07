const moduleClassId = $('#module-class-id')
let moduleClassIdList = []
let CustomSelectionAdapter = $.fn.select2.amd.require('select2/selection/customSelectionAdapter')

//  Get data from database
async function fetchData() {
    const baseUrl = '../../../api-v2/get_module_class.php'
    const init = {
        method: 'GET'
    }

    let data = await fetch(baseUrl, init)
        .then((response) => response.json())

    return data
}

async function loadData() {
    let data = await fetchData()

    data.forEach((row, index) => {
        moduleClassIdList.push({id: index, text: row['ID_Module_Class']})
    })
}

$(document).ready(async () => {
    await loadData()

    //  Display selected tags
    moduleClassId.select2({
        data: moduleClassIdList,
        selectionAdapter: CustomSelectionAdapter,
        allowClear: false,
        selectionContainer: $('#list')
    }).on('select2:select', e => {
        moduleClassIdList.splice(e.params.data['id'], 1)
    })
})