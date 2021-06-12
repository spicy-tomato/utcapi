import {getSender, fetchData} from '../../script/shared_functions.js'
import {postDataAndRaiseAlert} from '../../script/alerts.js'

let sender
let data
let selectedNoti = []
let num = 0

document.addEventListener('DOMContentLoaded', async () => {
    sender = await getSender(1)
    await lazyLoad()
})

let observer = new IntersectionObserver(async function (e) {
    if (e[0].intersectionRatio === 1) {
        let element = document.getElementById('observe')
        observer.unobserve(element)
        element.removeAttribute('id')

        await lazyLoad()
    }
}, {
    threshold: [0, 1]
})

async function lazyLoad() {
    data = await fetchData('../../api-v2/web/get_notification_for_sender.php?id_sender=' + sender + '&num=' + num)
    num += 15
    if (data === '') {
        return;
    }

    createScrollList()
}

function createScrollList() {
    let i = 1
    let selectTag = document.getElementById('noti-select')
    for (const e of data) {
        let rowTag = createRow()
        let colTag1 = createColumn()
        let colTag2 = createColumn()
        let colTag3 = createColumn()
        let colTag4 = createColumn()

        colTag1.append(createCheckBox(e.ID_Notification))
        colTag2.append(createLabel(e.Title, e.ID_Notification))
        colTag3.append(createLabel(e.Content, e.ID_Notification))
        colTag4.append(createLabel(formatDate(e.Time_Create), e.ID_Notification))

        rowTag.appendChild(colTag1)
        rowTag.appendChild(colTag2)
        rowTag.appendChild(colTag3)
        rowTag.appendChild(colTag4)

        selectTag.appendChild(rowTag)
        if (i === 12) {
            rowTag.id = 'observe'
            observer.observe(rowTag)
        }
        i++
    }
}

function formatDate(date) {
    let arr = date.split(' ')
    let arr2 = arr[0].split('-')
    let formatedDate = arr2[2] + '-' + arr2[1] + '-' + arr2[0] + ' ' + arr[1]

    return formatedDate
}


function createCheckBox(index) {
    let checkbox = document.createElement('input')
    checkbox.type = 'checkbox'
    checkbox.className = `form-check-input class-checkbox`
    checkbox.id = index
    checkbox.value = index
    checkbox.checked = false
    checkbox.addEventListener('click', checkBoxEvent)
    return checkbox;
}

function createLabel(text, index) {
    let tag = document.createElement('label')
    tag.htmlFor = index
    tag.className = 'label form-check-label'
    tag.innerHTML = text

    return tag
}

function createRow() {
    return document.createElement('tr')
}

function createColumn(_class) {
    return document.createElement('td')

}

function checkBoxEvent() {
    if (this.checked === true) {
        selectedNoti.push(this.value)
    }
    else {
        selectedNoti.splice(selectedNoti.lastIndexOf(this.value), 1)
    }
}

/*--------------------------------------*/
function getInvalidField(data) {
    if (data.length === 0) {
        return 'Checkbox thông báo'
    }

    return null
}

CustomConfirm('button', async function (confirmed, element) {
    if (confirmed) {
        await postDataAndRaiseAlert('../../api-v2/web/delete_notification.php', selectedNoti, getInvalidField, 'Tiếp tục xóa thông báo', '../home/')
    }
});

