<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once __DIR__ . '/utils/env_io.php';
    include_once __DIR__ . '/api-v2/manage/crawl/simple_html_dom.php';
    $ch = curl_init();

    $url                       = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/Login.aspx';
    $url_login                 = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
    $url_student_mark          = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
    $url_student_exam_schedule = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';

//    file_get_contents($url);
//    $response_header = explode(' ', $http_response_header[3]);
//    $location        = explode('/', $response_header[1]);
//    $access_token    = $location[2];
$access_token = '(S(3vwbcqssv0cc30sz2d5girih))';
    $url_login                 .= $access_token . '/Login.aspx';
    $url_student_mark          .= $access_token . '/StudentMark.aspx';
    $url_student_exam_schedule .= $access_token . '/StudentViewExamList.aspx';

    $form_login_request                = EnvIO::$form_login_request;
    $form_login_request['txtUserName'] = '191201402';
    $form_login_request['txtPassword'] = md5('21/05/2001');
    curl_setopt($ch, CURLOPT_URL, $url_login);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form_login_request));
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'c.txt');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
echo $response;
//    curl_setopt($ch, CURLOPT_URL, $url_student_exam_schedule);
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_COOKIEJAR, 'c.txt');
//    $response = curl_exec($ch);
//    $html     = new simple_html_dom();
//    $html->load($response);
//
//    $view_state        = $html->find('input[name=__VIEWSTATE]', 0)->value;
//    $event_validation  = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
//    $hidden_student_id = $html->find('input[id=hidStudentId]', 0)->value;
//
//    $form_get_exam_schedule_request                      = EnvIO::$form_get_exam_schedule_request;
//    $form_get_exam_schedule_request['hidStudentId']      = $hidden_student_id;
//    $form_get_exam_schedule_request['__VIEWSTATE']       = $view_state;
//    $form_get_exam_schedule_request['__EVENTVALIDATION'] = $event_validation;
//
//    $semester_arr                  = ['1_2019_2020'];
//    $elements                      = $html->find('select[name=drpSemester] option');
//    $data                          = [];
//    $flag                          = false;
//    $data[$elements[2]->innertext] = $elements[2]->value;
//    for ($i = 0; $i < count($elements); $i++) {
//        if (in_array($elements[$i]->innertext, $semester_arr)) {
//            $data[$elements[$i]->innertext] = $elements[$i]->value;
//            if (!$flag) {
//                $data[$elements[$i - 1]->innertext] = $elements[$i - 1]->value;
//                $flag                               = true;
//            }
//        }
//    }
//    $semester_arr                                  = $data;
//    $form_get_exam_schedule_request['drpSemester'] = $semester_arr['1_2019_2020'];
//    curl_setopt($ch, CURLOPT_URL, $url_student_exam_schedule);
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form_get_exam_schedule_request));
//    curl_setopt($ch, CURLOPT_COOKIEJAR, 'c.txt');
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $response = curl_exec($ch);
//    $response = mb_convert_encoding($response, 'HTML-ENTITIES', "UTF-8");
//    $html->load($response);
//
//    $string = $html->find('select[id=drpDotThi] option[selected=selected]', 0)->innertext;
//
//    $dom = new DOMDocument();
//    @$dom->loadHTML($response);
//    $aa = $dom->getElementById('drpDotThi');
//    for ($i = 0; $i < $aa->childNodes->count(); $i++)
//    {
//        var_dump($aa->childNodes->item($i)->textContent);
//    }