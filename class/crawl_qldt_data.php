<?php
    require dirname(__DIR__) . '/vendor/autoload.php';
    include_once dirname(__DIR__) . '/utils/env_io.php';
    include_once dirname(__DIR__) . '/api-v2/app/crawl/simple_html_dom.php';


    class CrawlQLDTData
    {
        private string $student_id;
        private string $qldt_password;
        private string $major = '';
        private array $school_year_arr = [];
        private array $form_crawl_request = [];
        private string $url = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/Login.aspx';
        private string $url_login = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $url_student_mark = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private string $url_student_exam_schedule = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/';
        private int $status = 1;
        private bool $is_all = false;
        private $home_page;
        private $ch;

        public function __construct (string $student_id, string $qldt_password)
        {
            $this->student_id    = $student_id;
            $this->qldt_password = $qldt_password;

            $this->ch = curl_init();

            $this->_getAccessToken();
            $this->loginQLDT();
        }

        public function getStatus () : int
        {
            return $this->status;
        }

        private function _getAccessToken ()
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

            $response = $this->_postRequest($this->url_login, $form_login_request);

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
            else {
                $this->home_page = $response;

                $response = mb_convert_encoding($response, 'HTML-ENTITIES', "UTF-8");
                $dom      = new DOMDocument();
                @$dom->loadHTML($response);
                $field_content = $dom->getElementById('drpField')->childNodes->item(1)->textContent;
                $this->major   = explode(' - ', $field_content)[1];
            }
        }

        public function getStudentInfo () : array
        {
            $html = new simple_html_dom();
            $html->load($this->home_page);

            $info      = $html->find('span[id=lblStudent]', 0)->innertext;
            $info_list = explode(' - ', $info);

            $data['student_name']  = $info_list[1];
            $data['academic_year'] = $info_list[3];

            $str_length = 0;
            foreach (EnvIO::$faculties as $faculty => $arr) {
                foreach ($arr as $a) {
                    if (strpos($info_list[2], $a) != false &&
                        strlen($a) > $str_length) {
                        $data['id_faculty'] = $faculty;
                        $str_length         = strlen($a);
                    }
                }
            }

            return $data;
        }

        public function getStudentModuleScore ($flag) : array
        {
            if ($flag == 'true') {
                $this->is_all = true;
            }

            $this->_getFormRequireDataOfStudentModuleScore();
            $data = $this->_getDataModuleScore();
            $data = $this->_formatModuleScoreData($data);

            curl_close($this->ch);

            return $data;
        }

        private function _getFormRequireDataOfStudentModuleScore ()
        {
            $response = $this->_getRequest($this->url_student_mark);
            //echo $response;

            $html = new simple_html_dom();
            $html->load($response);

            $this->form_crawl_request                      = EnvIO::$form_get_mark_request;
            $this->form_crawl_request['__VIEWSTATE']       = $html->find('input[name=__VIEWSTATE]', 0)->value;
            $this->form_crawl_request['__EVENTVALIDATION'] = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
            $this->form_crawl_request['hidStudentId']      = $html->find('input[id=hidStudentId]', 0)->value;
            $this->form_crawl_request['drpField']          = $html->find('select[name=drpField] option', 0)->value;
            $this->form_crawl_request['hidFieldId']        = $html->find('input[id=hidFieldId]', 0)->value;
            $this->form_crawl_request['hidFieldName']      = $this->major;

            $elements = $html->find('select[name=drpHK] option');
            if (!$this->is_all) {
                $latest_school_year = $elements[count($elements) - 1]->innertext;
                if (strlen(trim($latest_school_year, ' ')) == 7) {
                    $this->school_year_arr[] = $elements[count($elements) - 2]->innertext;
                }
                else {
                    $this->school_year_arr[] = $latest_school_year;
                }

                return;
            }

            unset($elements[0]);
            foreach ($elements as $e) {
                $this->school_year_arr[] = $e->innertext;
            }
        }

        private function _getDataModuleScore ()
        {
            $data = null;

            foreach ($this->school_year_arr as $school_year) {
                $this->form_crawl_request['drpHK'] = $school_year;
                $response                          = $this->_postRequest($this->url_student_mark, $this->form_crawl_request);

                $html = new simple_html_dom();
                $html->load($response);
                $tr = $html->find('table[id=tblStudentMark] tr');

                for ($j = 1; $j < count($tr) - 1; $j++) {
                    $arr = [];

                    $td    = explode('<br><br>', $tr[$j]->children(1)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    $td    = explode('<br><br>', $tr[$j]->children(2)->innertext);
                    $str   = $td[1] ?? $td[0];
                    $arr[] = $this->_formatStringDataCrawled($str);

                    $td    = explode('<br><br>', $tr[$j]->children(3)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    $td              = explode('<br><br>', $tr[$j]->children(8)->innertext);
                    $temp_evaluation = $td[1] ?? $td[0];
                    $arr[]           = $temp_evaluation == '&nbsp;' ? null : $temp_evaluation;

                    $td    = explode('<br><br>', $tr[$j]->children(9)->innertext);
                    $arr[] = $td[1] ?? $td[0];

                    //-------------------------------------------------------------
                    $temp_data = $tr[$j]->children(10)->innertext;
                    $td3       = explode('<br><br><br>', $temp_data);
                    $td2       = explode('<br><br>', $temp_data);
                    $td1       = explode('<br>', $temp_data);

                    $temp_score = $td3[1] ?? $td3[0];
                    if (strpos($temp_score, '<br>') !== false) {
                        $temp_score = $td2[1] ?? $td2[0];
                    }
                    if (strpos($temp_score, '<br>') !== false) {
                        $temp_score = $td1[1] ?? $td1[0];
                    }
                    $arr[] = $temp_score == '&nbsp;' ? null : $temp_score;
                    //------------------------------------------------------------

                    if (count($tr[$j]->children()) == 11) {
                        $arr[]                = null;
                        $arr[]                = null;
                        $data[$school_year][] = $arr;

                        continue;
                    }

                    //-------------------------------------------------------------
                    $temp_data = $tr[$j]->children(11)->innertext;
                    $td3       = explode('<br><br><br>', $temp_data);
                    $td2       = explode('<br><br>', $temp_data);
                    $td1       = explode('<br>', $temp_data);

                    $temp_score = $td3[1] ?? $td3[0];
                    if (strpos($temp_score, '<br>') !== false) {
                        $temp_score = $td2[1] ?? $td2[0];
                    }
                    if (strpos($temp_score, '<br>') !== false) {
                        $temp_score = $td1[1] ?? $td1[0];
                    }
                    $arr[] = $temp_score == '&nbsp;' ? null : $temp_score;
                    //------------------------------------------------------------

                    if (count($tr[$j]->children()) == 12) {
                        $arr[]                = null;
                        $data[$school_year][] = $arr;

                        continue;
                    }

                    //-------------------------------------------------------------
                    $temp_data = $tr[$j]->children(12)->innertext;
                    $td3       = explode('<br><br><br>', $temp_data);
                    $td2       = explode('<br><br>', $temp_data);
                    $td1       = explode('<br>', $temp_data);

                    $temp_score = $td3[1] ?? $td3[0];
                    if (strpos($temp_score, '<br>') !== false) {
                        $temp_score = $td2[1] ?? $td2[0];
                    }
                    if (strpos($temp_score, '<br>') !== false) {
                        $temp_score = $td1[1] ?? $td1[0];
                    }
                    $arr[] = $temp_score == '&nbsp;' ? null : $temp_score;
                    //------------------------------------------------------------

                    $data[$school_year][] = $arr;
                }
            }

            return $data;
        }

        private function _postRequest ($url, $post_form)
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

        private function _getRequest ($url)
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
            $this->school_year_arr = $semester;
            $this->_getFormRequireDataOfStudentExamSchedule();
            $data = $this->_getDataExamSchedule();

            curl_close($this->ch);

            if (empty($data)) {
                foreach ($this->school_year_arr as $key => $value) {
                    $data[$key] = [];
                    break;
                }
            }

            return $data;
        }

        private function _getFormRequireDataOfStudentExamSchedule ()
        {
            $response = $this->_getRequest($this->url_student_exam_schedule);

            $html = new simple_html_dom();
            $html->load($response);

            $this->form_crawl_request                      = EnvIO::$form_get_exam_schedule_request;
            $this->form_crawl_request['__VIEWSTATE']       = $html->find('input[name=__VIEWSTATE]', 0)->value;
            $this->form_crawl_request['__EVENTVALIDATION'] = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
            $this->form_crawl_request['hidStudentId']      = $html->find('input[id=hidStudentId]', 0)->value;

            $elements = $html->find('select[name=drpSemester] option');
            $data     = [];
            $flag     = false;
            for ($i = 0; $i < count($elements); $i++) {
                if (in_array($elements[$i]->innertext, $this->school_year_arr)) {
                    $data[$elements[$i]->innertext] = $elements[$i]->value;
                    if (!$flag) {
                        $data[$elements[$i - 1]->innertext] = $elements[$i - 1]->value;
                        $flag                               = true;
                    }
                }
            }

            $this->school_year_arr = $data;
        }

        private function _getDataExamSchedule ()
        {
            $data = null;

            foreach ($this->school_year_arr as $school_year_key => $school_year_value) {
                $this->form_crawl_request['drpSemester'] = $school_year_value;

                $response = $this->_postRequest($this->url_student_exam_schedule, $this->form_crawl_request);
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
                        $this->form_crawl_request['drpDotThi']         = $exam_type[$i][1];
                        $this->form_crawl_request['__EVENTTARGET']     = 'drpDotThi';
                        $this->form_crawl_request['__VIEWSTATE']       = $html->find('input[name=__VIEWSTATE]', 0)->value;
                        $this->form_crawl_request['__EVENTVALIDATION'] = $html->find('input[name=__EVENTVALIDATION]', 0)->value;

                        $response = $this->_postRequest($this->url_student_exam_schedule, $this->form_crawl_request);
                        $html->load($response);

                        $this->form_crawl_request['__EVENTTARGET'] = 'drpSemester';
                        $this->form_crawl_request['drpDotThi']     = '';
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

                        $data[$school_year_key][] = $arr;
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
            $num_of_school_year = count($this->school_year_arr);

            if (strlen(trim($this->school_year_arr[0], ' ')) == 7) {
                foreach ($data[$this->school_year_arr[0]] as &$module) {
                    $module[3] = 'DAT';
                    $score     = $module[5] != null ? $module[5] : ($module[6] != null ? $module[6] : $module[7]);
                    $module[5] = $score;
                    $module[6] = $score;
                    $module[7] = $score;

                    $data[$this->school_year_arr[1]][] = $module;
                }
                unset($data[$this->school_year_arr[0]]);
            }

            if (strlen(trim($this->school_year_arr[$num_of_school_year - 1], ' ')) == 7) {
                foreach ($data[$this->school_year_arr[$num_of_school_year - 1]] as &$module) {
                    $module[3] = 'DAT';
                    $score     = $module[5] != null ? $module[5] : ($module[6] != null ? $module[6] : $module[7]);
                    $module[5] = $score;
                    $module[6] = $score;
                    $module[7] = $score;

                    $data[$this->school_year_arr[0]][] = $module;
                }
                unset($data[$this->school_year_arr[$num_of_school_year - 1]]);
            }

            return $data;
        }
    }