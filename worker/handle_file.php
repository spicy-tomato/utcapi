<?php

    class HandleFile
    {
        private array $file_arr;

        public function __construct (array $fileArr)
        {
            $this->file_arr = $fileArr;
        }

        public function handleFile ()
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $new_file_name_arr = null;

            foreach ($this->file_arr as $file) {
                $nameSplit = explode('.', $file['name']);
                $timeSplit = explode('.', microtime(true));

                $new_file_name = $nameSplit[0] . '_' . $timeSplit[0] . $timeSplit[1] . '.' . $nameSplit[1];
                $new_file_name = preg_replace('/\s+/', '', $new_file_name);

                $location = dirname(__DIR__) . '/file_upload/' . $new_file_name;

                if (move_uploaded_file($file['tmp_name'], $location)) {
                    $new_file_name_arr[] = $new_file_name;
                }
                else {
                    $new_file_name_arr = null;
                }
            }

            return $new_file_name_arr;
        }
    }