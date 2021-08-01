let fileNameError = []
let group = [];
document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('submit_btn').addEventListener('click', uploadFile)
    document.getElementById('fileUpload').addEventListener('change', listFileName)
})

/*--------------------------------------*/

async function uploadFile() {
    let divTag = document.getElementById('file-exception');
    divTag.innerHTML = ''

    if (fileUpload.files.length === 0) {
        raiseEmptyFieldError()
        return
    }

    let formData = new FormData();

    for (let i = 0; i < fileUpload.files.length; i++) {
        formData.append('file' + i, fileUpload.files[i]);
    }
    let responseAsJson1 = await fetch('../../worker/handle_file_upload.php', {
        method: 'POST',
        body: formData
    });
    let status1 = responseAsJson1.status

    if (status1 !== 200 && status1 !== 201) {
        raiseBackEndError(true, 3)
        return
    }

    if (status1 === 201) {
        let response = await responseAsJson1.json()
        group = response[response.length - 1]
        response.pop()
        fileNameError = response
    }

    if (status1 === 200) {
        group = await responseAsJson1.json()
    }

    let flag = true
    for (const arr of group) {
        let responseAsJson = await fetch('../../api-v2/web/create_student_account.php', {
            method: 'POST',
            body: JSON.stringify(arr)
        });

        if (responseAsJson.status !== 200) {
            flag = false
            break;
        }
    }

    if (!flag) {
        raiseBackEndError(true, 3)
        return
    }

    if (fileNameError.length === 0) {
        if (flag) {
            raiseSuccess()
            return;
        }
        raiseBackEndError(true, 3)
    }
    else {
        raiseBackEndError(false, 10)
        displayFileException(fileNameError)
        fileNameError = []
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

function displayFileException() {
    let divTag = document.getElementById('file-exception');

    for (const fileName of fileNameError) {
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