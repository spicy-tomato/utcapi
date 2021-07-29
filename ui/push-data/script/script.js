document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('submit_btn').addEventListener('click', uploadFile)
    document.getElementById('fileUpload').addEventListener('change', listFileName)
})

/*--------------------------------------*/

async function uploadFile() {
    if (fileUpload.files.length === 0) {
        raiseEmptyFieldError()
        return
    }

    let formData = new FormData();

    for (let i = 0; i < fileUpload.files.length; i++) {
        formData.append('file' + i, fileUpload.files[i]);
    }
    let responseAsJson = await fetch('../../worker/handle_file_upload.php', {
        method: 'POST',
        body: formData
    });

    let divTag = document.getElementById('file-exception');
    divTag.innerHTML = ''

    if (responseAsJson.status === 200) {
        raiseSuccess()
    }
    else if (responseAsJson.status === 201) {
        let response = await responseAsJson.json()

        displayFileException(response)
        raiseBackEndError(false, 3)
    }
    else {
        raiseBackEndError(true, 10)
    }
}

function listFileName() {
    let divTag = document.querySelector('#file-list')
    divTag.innerHTML = ''

    let innerHtml = ''
    for (let i = 0; i < fileUpload.files.length; i++) {
        innerHtml += '<span>' + fileUpload.files[i].name + '</span><br>'
    }

    divTag.innerHTML = innerHtml
}

function displayFileException(fileNameList) {
    let divTag = document.getElementById('file-exception');

    for (const fileName of fileNameList) {
        let aTag = document.createElement('a')
        aTag.innerHTML = fileName
        aTag.href = 'src/' + fileName;
        aTag.setAttribute('download', '');

        let brTag = document.createElement('br')

        divTag.append(aTag)
        divTag.append(brTag)
    }
}

/*---------------------------------------*/

function raiseSuccess() {
    alertify.confirm('Tải tệp lên thành công!')
        .setHeader('<i class="fas fa-info-circle"></i> Thông tin')
        .setting({
            'labels':
                {
                    ok: 'Tiếp tục tải tệp',
                    cancel: 'Về trang chủ'
                },
            'defaultFocusOff': true,
            'maximizable': false,
            'movable': false,
            'pinnable': false,
            'onok': () => window.location.reload(),
            'oncancel': () => window.location.replace(('../home/'))
        })
}

function raiseEmptyFieldError() {
    alertify.error(`Chưa có tệp nào được chọn`)
        .delay(3)
        .dismissOthers()
}

function raiseBackEndError(pureError, ttl) {
    let error = ''
    if (pureError) {
        error = 'Có lỗi đã xảy ra, hãy thử lại sau!';
    }
    else {
        error = 'Có ngoại lệ xảy ra trong quá trình nhập dữ liệu. ' +
            'Chi tiết ngoại lệ của mỗi file tải lên sẽ được ghi rõ trong các file hiển thị ở web với tên file tương ứng';
    }
    alertify.error(error)
        .delay(ttl)
        .dismissOthers()
}