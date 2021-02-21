export async function getSender() {
    try {
        const baseUrl = '../../shared/session.php?var=department_id'
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
    let responseJson = await response.json()

    return responseJson
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
