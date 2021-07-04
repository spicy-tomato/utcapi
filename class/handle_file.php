<?php

    class HandleFile
    {
        private array $file_arr;

        public function __construct (array $fileArr)
        {
            $this->file_arr = $fileArr;
        }

        public function handleFile () : array
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $new_file_name_arr = null;

            foreach ($this->file_arr as $file) {
                $timeSplit = explode('.', microtime(true));

                $file_name = substr($file['name'], 0, strripos($file['name'], '.'));
                $expand = substr($file['name'], strripos($file['name'], '.'));;

                $new_file_name = $file_name . '_' . $timeSplit[0] . $timeSplit[1] . $expand;
                $new_file_name = preg_replace('/\s+/', '', $new_file_name);

                $location = dirname(__DIR__) . '/file_upload/' . $new_file_name;

                if (move_uploaded_file($file['tmp_name'], $location)) {
                    $new_file_name_arr[$file_name] = $new_file_name;
                }
                else {
                    $new_file_name_arr = null;
                }
            }

            return $new_file_name_arr;
        }
    }