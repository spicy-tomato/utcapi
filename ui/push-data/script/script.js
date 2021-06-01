
document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('submit_btn').addEventListener('click', uploadFile)
})

/*--------------------------------------*/

async function uploadFile() {
    if (fileUpload.files.length === 0) {
        raiseEmptyFieldError()
        return
    }

    let response;
    let formData = new FormData();

    for (let i = 0; i < fileUpload.files.length; i++) {
        formData.append('file'+i, fileUpload.files[i]);
    }
    let responseAsJson = await fetch('../../worker/handle_file_upload.php', {
        method: 'POST',
        body: formData
    });

    response = responseAsJson.json();

    if (await response === 'OK') {
        raiseSuccess()
    }
    else {
        raiseBackEndError()
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

function raiseBackEndError() {
    alertify.error('Có lỗi đã xảy ra, hãy thử lại sau!')
        .delay(3)
        .dismissOthers()
}