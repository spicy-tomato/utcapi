import { postData } from './shared_functions.js'

function raiseSuccess(okMessage ,linkHome) {
    alertify.confirm('Thêm thông báo thành công!')
        .setHeader('<i class="fas fa-info-circle"></i> Thông tin')
        .setting({
            'labels':
                {
                    ok: okMessage,
                    cancel: 'Về trang chủ'
                },
            'defaultFocusOff': true,
            'maximizable': false,
            'movable': false,
            'pinnable': false,
            'onok': () => window.location.reload(),
            'oncancel': () => window.location.replace((linkHome))
        })
}

export function raiseEmptyFieldError(field) {
    alertify.error(`"${ field }" không được để trống!`)
        .delay(3)
        .dismissOthers()
}

export function raiseBackEndError() {
    alertify.error('Có lỗi đã xảy ra, hãy thử lại sau!')
        .delay(3)
        .dismissOthers()
}

export async function postDataAndRaiseAlert(url, data, invalidFieldFunc, okMessage, linkHome) {
    let invalidField = invalidFieldFunc(data)

    if (invalidField !== null) {
        raiseEmptyFieldError(invalidField)
        return false
    }

    const response = await postData(url, data)
    if (response.status === 200) {
        raiseSuccess(okMessage, linkHome)
    }
    else {
        raiseBackEndError()
    }

    return true
}
