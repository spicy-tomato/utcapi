import {raiseEmptyFieldError} from '../../forms/alerts.js'

/*-----------------------------------------------*/

document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('submit_btn').addEventListener('click', uploadFile)
})

/*--------------------------------------*/

async function uploadFile() {
    if (fileUpload.files.length === 0) {
        raiseEmptyFieldError('File Upload')
        return
    }

    let response;
    let formData = new FormData();
    formData.append('flag', '1');

    for (let i = 0; i < fileUpload.files.length; i++) {
        formData.append('file'+i, fileUpload.files[i]);
    }
    let responseAsJson = await fetch('../../worker/handle_file_upload.php', {
        method: 'POST',
        body: formData
    });

    response = responseAsJson.json();

    if (await response === 'OK') {
        alert('The file has been uploaded successfully.');
    }
    else {
        alert('Failed to upload files.');
    }
}

/*---------------------------------------*/