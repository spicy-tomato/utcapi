// import {postDataAndRaiseAlert, raiseEmptyFieldError} from '../../alerts.js'
// import {getSender, autoFillTemplate} from '../../shared.js'
//
// let sender
// let fileName = null
// const fieldList = {
//     title: 'Tiêu đề',
//     content: 'Nội dung',
//     typez: 'Loại thông báo'
// }
//
// const templateNoti = {
//     study: {
//         title: 'Học tập',
//         content: 'Nội dung thông báo học tập',
//         typez: 0
//     },
//     fee: {
//         title: 'Học phí',
//         content: 'Nội dung thông báo học phí',
//         typez: 1
//     },
//     extracurricular: {
//         title: 'Thông báo ngoại khóa',
//         content: 'Nội dung thông báo ngoại khóa',
//         typez: 2
//     },
//     social_payment: {
//         title: 'Chi trả xã hội',
//         content: 'Nội dung thông báo chi trả xã hội',
//         typez: 3
//     },
//     others: {
//         title: 'Thông báo khác',
//         content: 'Nội dung thông báo khác',
//         typez: 4
//     }
// }
//
// /*-----------------------------------------------*/
//
// document.addEventListener('DOMContentLoaded', async () => {
//
//     document.getElementById('submit_btn').addEventListener('click', trySendNotification)
//     document.getElementById('template').addEventListener('change', fillForms)
//     document.getElementById('confirm').addEventListener('click', uploadFile)
//     document.getElementsByName('reset_button')[0].addEventListener('click', resetInputDate)
//     document.getElementsByName('reset_button')[1].addEventListener('click', resetInputDate)
//
//     sender = await getSender()
// })
//
// /*--------------------------------------*/
//
// async function uploadFile() {
//     if (fileUpload.files.length === 0) {
//         raiseEmptyFieldError('File Upload')
//         return
//     }
//
//     let response;
//     let formData = new FormData();
//     formData.append('flag', '0');
//
//     for (let i = 0; i < fileUpload.files.length; i++) {
//         formData.append('file' + 1, fileUpload.files[i]);
//     }
//
//     let responseAsJson = await fetch('../../../worker/handle_file_upload.php', {
//         method: 'POST',
//         body: formData
//     });
//
//     response = responseAsJson.json();
//
//     if (response !== 'Failure') {
//         fileName = response
//         alert('The file has been uploaded successfully.');
//     }
//     else {
//         alert('Failed to upload files.');
//     }
// }
//
// /*---------------------------------------*/
//
// //  Display error if there are some unfulfilled fields
// function getInvalidField(data) {
//     for (const [field, fieldValue] of Object.entries(data.info)) {
//         if (fieldValue === '' && fieldList[field] !== undefined) {
//             return fieldList[field]
//         }
//     }
//
//     if (fileName === null) {
//         return 'File Upload'
//     }
//
//     return null
// }
//
// /*_________________________________________________*/
//
// async function trySendNotification() {
//     const data = {
//         info: {
//             title: $('#title').val(),
//             content: $('#content').val(),
//             typez: $('#type').val(),
//             time_start: $('#time_start').val(),
//             time_end: $('#time_end').val(),
//             sender: sender
//         },
//         file_name: fileName
//     }
//
//     const baseUrl = '../../../api-v2/manage/department_class_notification.php'
//
//     let madeRequest = await postDataAndRaiseAlert(baseUrl, data, getInvalidField)
//
//     if (madeRequest) {
//         document.getElementById('submit_btn').removeEventListener('click', trySendNotification)
//     }
// }
//
// function fillForms() {
//     autoFillTemplate(templateNoti[template.value])
// }
//
//
// function resetInputDate() {
//     let elemID = this.classList[2]
//     document.getElementById(elemID).value = ''
// }
