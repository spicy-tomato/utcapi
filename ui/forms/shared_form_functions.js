export function autoFillTemplate(templateNoti) {
    title.value = templateNoti.title
    type.selectedIndex = templateNoti.typez
    content.value = templateNoti.content
}

export async function postData(url, data) {
    const init = {
        method: 'POST',
        cache: 'no-cache',
        body: JSON.stringify(data)
    }

    const response = await fetch(url, init)
    const responseJson = await response.json()

    return responseJson
}

export function changeStatusButton() {
    if (this.type === 'date') {
        document.getElementsByClassName(this.id)[0].classList.remove('disable')
    }
    else {
        this.classList.add('disable')
    }
}

export function resetInputDate() {
    let elemID = this.classList[2]
    document.getElementById(elemID).setAttribute('data-date', 'Invalid date')
}

