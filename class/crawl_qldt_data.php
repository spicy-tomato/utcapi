<?php
    require dirname(__DIR__) . '/vendor/autoload.php';
    include_once dirname(__DIR__) . '/utils/env_io.php';
    include_once dirname(__DIR__) . '/api-v2/app/crawl/simple_html_dom.php';


    class CrawlQLDTData
    {
        private string $student_id;
        private string $qldt_password;
        private array $semester_arr = [];
        private string $hidden_student_id = '';
        private string $url = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/Login.aspx';
        private string $url_login = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $url_student_mark = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $url_student_exam_schedule = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $view_state = '';
        private string $event_validation = '';
        private int $status = 1;
        private bool $is_all = false;
        private $ch;

        public function __construct (string $student_id, string $qldt_password)
        {
            $this->student_id    = $student_id;
            $this->qldt_password = $qldt_password;

            $this->ch = curl_init();

            $this->getAccessToken();
            $this->loginQLDT();
        }

        public function getStatus () : int
        {
            return $this->status;
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

        public function loginQLDT ()
        {
            $form_login_request                = EnvIO::$form_login_request;
            $form_login_request['txtUserName'] = $this->student_id;
            $form_login_request['txtPassword'] = $this->qldt_password;

            $response = $this->postRequest($this->url_login, $form_login_request);

            $html = new simple_html_dom();
            $html->load($response);

            $flag = $html->find('select[id=drpCourse]', 0);
            if (empty($flag)) {
                $flag2 = $html->find('input[id=txtUserName]', 0);
                if (empty($flag2)) {
                    $this->status = -1;
                }
                else {
                    $this->status = 0;
                }
            }
        }

        public function getStudentModuleScore ($flag) : array
        {
            if ($flag == 'true') {
                $this->is_all = true;
            }

            if ($this->status == 1) {
                $this->getFormRequireDataOfStudentModuleScore();
                $data = $this->getDataModuleScore();
                $data = $this->_formatModuleScoreData($data);
            }
            else {
                $data[] = $this->status;
            }
            curl_close($this->ch);

            return $data;
        }

        private function getFormRequireDataOfStudentModuleScore ()
        {
            $response = $this->getRequest($this->url_student_mark);

            $html = new simple_html_dom();
            $html->load($response);

            $this->view_state        = $html->find('input[name=__VIEWSTATE]', 0)->value;
            $this->event_validation  = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
            $this->hidden_student_id = $html->find('input[id=hidStudentId]', 0)->value;

            $elements = $html->find('select[name=drpHK] option');
            if (!$this->is_all) {
                $latest_semester = $elements[count($elements) - 1]->innertext;
                if (strlen(trim($latest_semester, ' ')) == 7) {
                    $this->semester_arr[] = $elements[count($elements) - 2]->innertext;
                }
                else {
                    $this->semester_arr[] = $latest_semester;
                }

                return;
            }

            unset($elements[0]);
            foreach ($elements as $e) {
                $this->semester_arr[] = $e->innertext;
            }
        }

        private function getDataModuleScore ()
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
                    $arr = [];

                    $td    = explode('<br><br>', $tr[$j]->children(1)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    $td    = explode('<br><br>', $tr[$j]->children(2)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    $td    = explode('<br><br>', $tr[$j]->children(3)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    $td              = explode('<br><br>', $tr[$j]->children(8)->innertext);
                    $temp_evaluation = $td[1] ?? $td[0];
                    $arr[]           = $temp_evaluation == '&nbsp;' ? null : $temp_evaluation;

                    $td    = explode('<br><br>', $tr[$j]->children(9)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    $td    = explode('<br><br>', $tr[$j]->children(10)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    if (count($tr[$j]->children()) == 11) {
                        $arr[]             = null;
                        $arr[]             = null;
                        $data[$semester][] = $arr;

                        continue;
                    }

                    $td         = explode('<br><br>', $tr[$j]->children(11)->innertext);
                    $temp_score = $td[1] ?? $td[0];
                    $arr[]      = $temp_score == '&nbsp;' ? null : $temp_score;

                    $td         = explode('<br><br>', $tr[$j]->children(12)->innertext);
                    $temp_score = $td[1] ?? $td[0];
                    $arr[]      = $temp_score == '&nbsp;' ? null : $temp_score;

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

        public function getStudentExamSchedule ($semester) : array
        {
            if ($this->status == 1) {
                $this->semester_arr = $semester;
                $this->getFormRequireDataOfStudentExamSchedule();
                $data = $this->getDataExamSchedule();
            }
            else {
                $data[] = $this->status;
            }
            curl_close($this->ch);

            if (empty($data)) {
                foreach ($this->semester_arr as $key => $value) {
                    $data[$key] = [];
                    break;
                }
            }

            return $data;
        }

        private function getFormRequireDataOfStudentExamSchedule ()
        {
            $response = $this->getRequest($this->url_student_exam_schedule);

            $html = new simple_html_dom();
            $html->load($response);

            $this->view_state        = $html->find('input[name=__VIEWSTATE]', 0)->value;
            $this->event_validation  = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
            $this->hidden_student_id = $html->find('input[id=hidStudentId]', 0)->value;
            var_dump($this->semester_arr);

            $elements = $html->find('select[name=drpSemester] option');
            $data     = [];
            $flag     = false;
            for ($i = 0; $i < count($elements); $i++) {
                if (in_array($elements[$i]->innertext, $this->semester_arr)) {
                    $data[$elements[$i]->innertext] = $elements[$i]->value;
                    if (!$flag) {
                        $data[$elements[$i - 1]->innertext] = $elements[$i - 1]->value;
                        $flag                               = true;
                    }
                }
            }
            var_dump($data);

            $this->semester_arr = $data;
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
                $exam_type_by_shtmldom = $html->find('select[id=drpDotThi] option');

                $response = mb_convert_encoding($response, 'HTML-ENTITIES', "UTF-8");
                $dom      = new DOMDocument();
                @$dom->loadHTML($response);
                $exam_type_by_dom_document = $dom->getElementById('drpDotThi');

                $exam_type = [];
                $j         = 1;
                for ($i = 3; $i < $exam_type_by_dom_document->childNodes->count(); $i += 2) {
                    $exam_type[$i - (2 + $j)][] = $exam_type_by_dom_document->childNodes->item($i)->textContent;
                    $exam_type[$i - (2 + $j)][] = $exam_type_by_shtmldom[$i - ($j + 1)]->value;
                    $j++;
                }

                $exam_type_selected = $html->find('select[id=drpDotThi] option[selected=selected]', 0)->value;
                for ($i = count($exam_type) - 1; $i >= 0; $i--) {
                    if ($exam_type[$i][1] != $exam_type_selected) {
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
                        $arr              = [];
                        $temp_examination = $this->_formatWrongWord($exam_type[$i][0]);
                        $arr[]            = $this->_formatStringDataCrawled($temp_examination);

                        $arr[] = $this->student_id;

                        $arr[] = $this->_formatStringDataCrawled($tr[$j]->children(1)->innertext);

                        $arr[] = $this->_formatStringDataCrawled($tr[$j]->children(2)->innertext);

                        $arr[] = $this->_formatStringDataCrawled($tr[$j]->children(3)->innertext);

                        $temp_date = $this->_formatStringDataCrawled($tr[$j]->children(4)->innertext);
                        $arr[]     = $this->_formatDateDataCrawled($temp_date);

                        $arr[] = $this->_formatStringDataCrawled($tr[$j]->children(5)->innertext);

                        $arr[] = $this->_formatStringDataCrawled($tr[$j]->children(6)->innertext);

                        $arr[] = $this->_formatStringDataCrawled($tr[$j]->children(7)->innertext);

                        $temp_room = $this->_formatWrongWord($tr[$j]->children(8)->innertext);
                        $arr[]     = $this->_formatStringDataCrawled($temp_room);

                        $data[$semester_key][] = $arr;
                    }
                }

            }

            return $data;
        }

        private function _formatWrongWord ($str)
        {
            $str = preg_replace('/Kê/', 'Kế', $str);
            $str = preg_replace('/hoach/', 'hoạch', $str);
            $str = preg_replace('/Phong/', 'Phòng', $str);

            return $str;
        }

        private function _formatStringDataCrawled ($str) : string
        {
            $str = preg_replace('/\s+/', ' ', $str);
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

        private function _formatModuleScoreData ($data) : array
        {
            $num_of_semester = count($this->semester_arr);
            if (strlen(trim($this->semester_arr[0], ' ')) == 7) {
                foreach ($data[$this->semester_arr[0]] as &$module) {
                    $module[3] = 'DAT';
                    $score     = $module[5] != null ? $module[5] : ($module[6] != null ? $module[6] : $module[7]);
                    $module[5] = $score;
                    $module[6] = $score;
                    $module[7] = $score;

                    $data[$this->semester_arr[0]][] = $module;
                }
                unset($data[$this->semester_arr[0]]);
            }

            if (strlen(trim($this->semester_arr[$num_of_semester - 1], ' ')) == 7) {
                foreach ($data[$this->semester_arr[$num_of_semester - 1]] as &$module) {
                    $module[3] = 'DAT';
                    $score     = $module[5] != null ? $module[5] : ($module[6] != null ? $module[6] : $module[7]);
                    $module[5] = $score;
                    $module[6] = $score;
                    $module[7] = $score;

                    $data[$this->semester_arr[0]][] = $module;
                }
                unset($data[$this->semester_arr[$num_of_semester - 1]]);
            }

            foreach ($data as &$semester) {
                foreach ($semester as &$module) {
                    $module[1] = $this->_formatStringDataCrawled($module[1]);
                }
            }
//            var_dump($data);

            return $data;
        }
    }