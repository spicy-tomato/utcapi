<?php

    require dirname(__DIR__) . '/vendor/autoload.php';
    include_once dirname(__DIR__) . '/utils/env_io.php';
    include_once dirname(__DIR__) . '/api-v2/manage/crawl/simple_html_dom.php';


    class CrawlQLDTData
    {
        private string $student_id;
        private string $student_password;
        private array $semester_arr = [];
        private string $hidden_student_id = '';
        private string $url = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/Login.aspx';
        private string $url_login = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $url_student_mark = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $url_student_exam_schedule = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $view_state = '';
        private string $event_validation = '';
        private $ch;

        public function __construct (string $student_id, string $student_password)
        {
            $this->student_id       = $student_id;
            $this->student_password = $student_password;

            $this->ch = curl_init();

            $this->getAccessToken();
            $this->loginQLDT();
        }

        public function getStudentMarks () : array
        {
            $status = $this->getFormRequireDataOfStudentMark();

            if ($status != -1 && $status != 0) {
                $data = $this->getDataMarks();
                $data = $this->_formatData($data);
            }
            else {
                $data[] = $status;
            }
            curl_close($this->ch);

            return $data;
        }

        private function getAccessToken ()
        {
            file_get_contents($this->url);
            $response_header = explode(' ', $http_response_header[3]);
            $location        = explode('/', $response_header[1]);
            $access_token    = $location[2];

            $this->url_login                 .= $access_token . '/Login.aspx';
            $this->url_student_mark          .= $access_token . '/StudentMark.aspx';
            $this->url_student_exam_schedule .= $access_token . '/StudentViewExamList.aspx';
        }

        private function loginQLDT ()
        {
            $form_login_request                = EnvIO::$form_login_request;
            $form_login_request['txtUserName'] = $this->student_id;
            $form_login_request['txtPassword'] = $this->student_password;

            $this->postRequest($this->url_login, $form_login_request);
        }

        private function getFormRequireDataOfStudentMark () : int
        {
            $response = $this->getRequest($this->url_student_mark);

            $html = new simple_html_dom();
            $html->load($response);

            $flag = $html->find('input[id=hidStudentId]', 0);
            if (empty($flag)) {
                $flag2 = $html->find('input[id=txtUserName]', 0);
                if (empty($flag2)) {
                    return -1;
                }
                return 0;
            }

            $this->view_state        = $html->find('input[name=__VIEWSTATE]', 0)->value;
            $this->event_validation  = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
            $this->hidden_student_id = $html->find('input[id=hidStudentId]', 0)->value;

            $elements = $html->find('select[name=drpHK] option');
            unset($elements[0]);
            foreach ($elements as $e) {
                $this->semester_arr[] = $e->innertext;
            }

            return 1;
        }

        private function getDataMarks ()
        {
            $form_get_mark_request                      = EnvIO::$form_get_mark_request;
            $form_get_mark_request['__VIEWSTATE']       = $this->view_state;
            $form_get_mark_request['__EVENTVALIDATION'] = $this->event_validation;
            $form_get_mark_request['hidStudentId']      = $this->hidden_student_id;
            $data                                       = null;

            foreach ($this->semester_arr as $semester) {
                $form_get_mark_request['drpHK'] = $semester;
                $response                       = $this->postRequest($this->url_student_mark, $form_get_mark_request);

                $html = new simple_html_dom();
                $html->load($response);
                $tr = $html->find('table[id=tblStudentMark] tr');

                for ($j = 1; $j < count($tr) - 1; $j++) {
                    $arr   = [];
                    $td    = explode('<br><br>', $tr[$j]->children(1)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$j]->children(2)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$j]->children(3)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$j]->children(8)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$j]->children(9)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$j]->children(10)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$j]->children(11)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$j]->children(12)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];

                    $data[$semester][] = $arr;
                }
            }

            return $data;
        }

        private function postRequest ($url, $post_form)
        {
            curl_setopt($this->ch, CURLOPT_URL, $url);
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($post_form));
            curl_setopt($this->ch, CURLOPT_COOKIEJAR, 'c.txt');
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($this->ch);

            return $response;
        }

        private function getRequest ($url)
        {
            curl_setopt($this->ch, CURLOPT_URL, $url);
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->ch, CURLOPT_COOKIEJAR, 'c.txt');
            $response = curl_exec($this->ch);

            return $response;
        }

        private function _formatData ($data) : array
        {
            if (strlen($this->semester_arr[0]) == 8) {
                foreach ($data[$this->semester_arr[0]] as $module) {
                    $data[$this->semester_arr[1]][] = $module;
                }
                unset($data[$this->semester_arr[0]]);
            }

            foreach ($data as &$semester) {
                foreach ($semester as &$module) {
                    $module[1] = preg_replace('/\s+/', ' ', $module[1]);
                    $module[1] = str_replace('- ', '-', $module[1]);
                    $module[1] = str_replace('- ', '-', $module[1]);
                }
            }

            return $data;
        }

        public function getStudentExamSchedule ($semester) : array
        {
            $this->semester_arr = $semester;
            $status             = $this->getFormRequireDataOfStudentExamSchedule();

            if ($status != -1 && $status != 0) {
                $data = $this->getDataExamSchedule();
            }
            else {
                $data[] = $status;
            }
            curl_close($this->ch);

            return $data;
        }

        private function getFormRequireDataOfStudentExamSchedule () : int
        {
            $response = $this->getRequest($this->url_student_exam_schedule);

            $html = new simple_html_dom();
            $html->load($response);

            $flag = $html->find('select[name=drpExaminationNumber]', 0);
            if (empty($flag)) {
                $flag2 = $html->find('input[id=txtUserName]', 0);
                if (empty($flag2)) {
                    return -1;
                }
                return 0;
            }

            $this->view_state        = $html->find('input[name=__VIEWSTATE]', 0)->value;
            $this->event_validation  = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
            $this->hidden_student_id = $html->find('input[id=hidStudentId]', 0)->value;

            $data     = [];
            $elements = $html->find('select[name=drpSemester] option');
            foreach ($elements as $e) {
                if (in_array($e->innertext, $this->semester_arr)) {
                    $data[$e->innertext] = $e->value;
                }
            }

            $this->semester_arr = $data;

            return 1;
        }

        private function getDataExamSchedule ()
        {
            $form_get_exam_schedule_request                      = EnvIO::$form_get_exam_schedule_request;
            $form_get_exam_schedule_request['hidStudentId']      = $this->hidden_student_id;
            $form_get_exam_schedule_request['__VIEWSTATE']       = $this->view_state;
            $form_get_exam_schedule_request['__EVENTVALIDATION'] = $this->event_validation;
            $data                                                = null;

            foreach ($this->semester_arr as $semester_key => $semester_value) {
                $form_get_exam_schedule_request['drpSemester'] = $semester_value;

                $response = $this->postRequest($this->url_student_exam_schedule, $form_get_exam_schedule_request);
                $html     = new simple_html_dom();
                $html->load($response);

                $exam_type         = [];
                $exam_type_element = $html->find('select[id=drpDotThi] option');
                for ($i = 1; $i < count($exam_type_element); $i++) {
                    $exam_type[$i - 1][] = $exam_type_element[$i]->plaintext;
                    $exam_type[$i - 1][] = $exam_type_element[$i]->value;
                }

                $exam_type_selected = $html->find('select[id=drpDotThi] option[selected=selected]', 0)->innertext;
                for ($i = count($exam_type) - 1; $i >= 0; $i--) {
                    if ($exam_type[$i][0] != $exam_type_selected) {
                        $form_get_exam_schedule_request['drpDotThi']         = $exam_type[$i][1];
                        $form_get_exam_schedule_request['__EVENTTARGET']     = 'drpDotThi';
                        $form_get_exam_schedule_request['__VIEWSTATE']       = $html->find('input[name=__VIEWSTATE]', 0)->value;
                        $form_get_exam_schedule_request['__EVENTVALIDATION'] = $html->find('input[name=__EVENTVALIDATION]', 0)->value;

                        $response = $this->postRequest($this->url_student_exam_schedule, $form_get_exam_schedule_request);
                        $html->load($response);

                        $form_get_exam_schedule_request['__EVENTTARGET'] = 'drpSemester';
                        $form_get_exam_schedule_request['drpDotThi']     = '';
                    }

                    $flag = $html->find('table[id=tblCourseList] tr', 1);
                    if (empty($flag)) {
                        continue;
                    }

                    $tr = $html->find('table[id=tblCourseList] tr');
                    for ($j = 1; $j < count($tr) - 1; $j++) {
                        $arr = [];
                        //                        $aa = ForceUTF8\Encoding::toUTF8($exam_type[$i][0]);
                        //                        $arr[] = ForceUTF8\Encoding::toUTF8($aa);
                        //                        $aa = iconv("ISO-8859-1", "UTF-8", $exam_type[$i][0]);
                        //                        $arr[] = htmlentities(trim(($exam_type[$i][0])), ENT_QUOTES, 'UTF-8');
                        //                        $arr[] = $aa;
                        $arr[]     = ($this->_formatStringDataCrawled($exam_type[$i][0]));
                        $arr[]     = $this->student_id;
                        $arr[]     = $this->_formatStringDataCrawled($tr[$j]->children(1)->innertext);
                        $arr[]     = $this->_formatStringDataCrawled($tr[$j]->children(2)->innertext);
                        $arr[]     = $this->_formatStringDataCrawled($tr[$j]->children(3)->innertext);
                        $temp_date = $this->_formatStringDataCrawled($tr[$j]->children(4)->innertext);
                        $arr[]     = $this->_formatDateDataCrawled($temp_date);
                        $arr[]     = $this->_formatStringDataCrawled($tr[$j]->children(5)->innertext);
                        $arr[]     = $this->_formatStringDataCrawled($tr[$j]->children(6)->innertext);
                        $arr[]     = $this->_formatStringDataCrawled($tr[$j]->children(7)->innertext);
                        $arr[]     = $this->_formatStringDataCrawled($tr[$j]->children(8)->innertext);

                        $data[$semester_key][] = $arr;
                    }
                }

            }

            return $data;
        }

        private function _formatStringDataCrawled ($str) : string
        {
            $str = preg_replace('/\s+/', ' ', $str);
            $str = preg_replace('/Kê/', 'Kế', $str);
            $str = preg_replace('/Phong/', 'Phòng', $str);
            $str = str_replace('- ', '-', $str);
            $str = str_replace(' -', '-', $str);
            $str = trim($str, ' ');

            return $str;
        }

        private function _formatDateDataCrawled ($date) : string
        {
            $date_split = explode('/', $date);
            $date       = $date_split[2] . '-' . $date_split[1] . '-' . $date_split[0];

            return $date;
        }

    }
