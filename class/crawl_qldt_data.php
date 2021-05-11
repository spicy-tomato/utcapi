<?php


    include_once $_SERVER['DOCUMENT_ROOT'] . '/utils/env_io.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/api-v2/manage/crawl/simple_html_dom.php';


    class CrawlQLDTData
    {
        private string $student_id;
        private string $student_password;
        private array $semester_arr = [];
        private string $hidden_student_id = '';
        private string $url = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/Login.aspx';
        private string $url_login = '';
        private string $url_student_mark = '';
        private string $view_state = '';
        private string $event_validation = '';
        private $ch;

        public function __construct (string $student_id, string $student_password)
        {
            $this->student_id       = $student_id;
            $this->student_password = $student_password;
        }

        public function getAll () : array
        {
            $this->ch = curl_init();
            $data     = [];

            $this->getAccessToken();
            $this->loginQLDT();
            if ($this->getFormRequireData()) {
                $data = $this->getDataMarks();
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

            $this->url_login        = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/' . $access_token . '/Login.aspx';
            $this->url_student_mark = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/' . $access_token . '/StudentMark.aspx';
        }

        private function loginQLDT ()
        {
            $form_login_request                = EnvIO::$form_login_request;
            $form_login_request['txtUserName'] = $this->student_id;
            $form_login_request['txtPassword'] = $this->student_password;

            $this->postRequest($this->url_login, $form_login_request);
        }

        private function getFormRequireData () : bool
        {
            $response = $this->getRequest($this->url_student_mark);

            $html = new simple_html_dom();
            $html->load($response);

            $flag = $html->find('input[id=hidStudentId]', 0);
            if (empty($flag)) {
                return false;
            }

            $this->view_state        = $html->find('input[name=__VIEWSTATE]', 0)->value;
            $this->event_validation  = $html->find('input[name=__EVENTVALIDATION]', 0)->value;
            $this->hidden_student_id = $html->find('input[id=hidStudentId]', 0)->value;

            $elements = $html->find('select[name=drpHK] option');
            unset($elements[0]);
            foreach ($elements as $e) {
                $this->semester_arr[] = $e->innertext;
            }

            return true;
        }

        private function getDataMarks ()
        {
            $form_get_mark_request                 = EnvIO::$form_get_mark_request;
            $form_get_mark_request['hidStudentId'] = $this->hidden_student_id;
            $data                                  = null;

            foreach ($this->semester_arr as $semester) {
                $form_get_mark_request['__VIEWSTATE']       = $this->view_state;
                $form_get_mark_request['__EVENTVALIDATION'] = $this->event_validation;
                $form_get_mark_request['hidStudentId']      = $this->hidden_student_id;
                $form_get_mark_request['drpHK']             = $semester;

                $response = $this->postRequest($this->url_student_mark, $form_get_mark_request);

                $html = new simple_html_dom();
                $html->load($response);
                $tr = $html->find('table[id=tblStudentMark] tr');

                for ($i = 1; $i < count($tr) - 1; $i++) {
                    $arr   = [];
                    $td    = explode('<br><br>', $tr[$i]->children(1)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$i]->children(2)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$i]->children(3)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$i]->children(8)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$i]->children(9)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$i]->children(10)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$i]->children(11)->innertext);
                    $arr[] = isset($td[1]) ? $td[1] : $td[0];
                    $td    = explode('<br><br>', $tr[$i]->children(12)->innertext);
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
    }
