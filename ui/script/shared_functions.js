export async function getSender(num) {
    try {
        const baseUrl = '../'.repeat(num) + 'shared/session.php?var=id_account'
        const init = {
            method: 'GET',
            cache: 'no-cache'
        }

        const response = await fetch(baseUrl, init)
        const responseJson = await response.json()

        return responseJson

    } catch (e) {
        console.log(e)
    }
}

//  Get data from database
export async function fetchData(url) {
    const init = {
        method: 'GET',
        cache: 'no-cache'
    }

    let response = await fetch(url, init)
    let responseJson
    if (response.status === 200) {
        responseJson = await response.json()
    }
    else {
        responseJson = ''
    }

    return responseJson
}

// post data
export async function postData(url, data) {
    const init = {
        method: 'POST',
        cache: 'no-cache',
        body: JSON.stringify(data)
    }

    const response = await fetch(url, init)

    return response
}