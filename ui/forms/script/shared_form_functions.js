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

export function listerEnterKey(event) {
    if (event.keyCode === 13 &&
        this.value !== '') {
        attachLink()
    }
}

export function attachLink() {
    let textArea = document.getElementById('content');
    let textLink = document.getElementById('attach-link');
    let linkArea = document.getElementById('link-area');
    textArea.value = textArea.value + '\n<a>' + textLink.value + '</a>'

    let aTag = document.createElement('a')
    aTag.innerHTML = getFileName(textLink.value)
    aTag.href = textLink.value;
    aTag.className = textLink.value

    let iTag = document.createElement('i')
    iTag.id = textLink.value
    iTag.className = 'far fa-window-close'
    iTag.addEventListener('click', remove)

    let brTag = document.createElement('br')

    textLink.value = ''

    linkArea.append(aTag)
    linkArea.append(iTag)
    linkArea.append(brTag)
}

function getFileName(link)
{
    let decodedLink = decodeURI(link);
    let arrPath = decodedLink.split('/')
    let fileName = arrPath[arrPath.length-1]

    return fileName;
}

function remove() {
    let textArea = document.getElementById('content');
    textArea.value = textArea.value.replace('\n<a>'+this.id+'</a>', '')
    let aTag = document.getElementsByClassName(this.id)[0]

    aTag.remove()
    this.nextSibling.remove()
    this.remove()
}
