import { postData } from './shared_function.js'

function raiseSuccess() {
    alertify.confirm('Thêm thông báo thành công!')
        .setHeader('<i class="fas fa-info-circle"></i> Thông tin')
        .setting({
            'labels':
                {
                    ok: 'Tạo thông báo mới',
                    cancel: 'Về trang chủ'
                },
            'defaultFocusOff': true,
            'maximizable': false,
            'movable': false,
            'pinnable': false,
            'onok': () => window.location.reload(),
            'oncancel': () => window.location.replace(('../../home/'))
        })
}

export function raiseEmptyFieldError(field) {
    alertify.error(`Trường "${ field }" không được để trống!`)
        .delay(3)
        .dismissOthers()
}

function raiseBackEndError() {
    alertify.error('Có lỗi đã xảy ra, hãy thử lại sau!')
        .delay(3)
        .dismissOthers()
}

export async function postDataAndRaiseAlert(url, data, invalidFieldFunc) {
    let invalidField = invalidFieldFunc(data)

    if (invalidField !== null) {
        raiseEmptyFieldError(invalidField)
        return false
    }

    const response = await postData(url, data)
    if (response.toString() === 'OK') {
        raiseSuccess()
    }
    else {
        raiseBackEndError()
    }

    return true
}
