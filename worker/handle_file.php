<?php

    class HandleFile
    {
        private array $fileArr;

        public function __construct (array $fileArr)
        {
            $this->fileArr = $fileArr;
        }

        public function handleFile ()
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $newFileNameArr = null;

            foreach ($this->fileArr as $file) {
                $nameSplit = explode('.', $file['name']);
                $timeSplit = explode('.', microtime(true));

                $newFileName = $nameSplit[0] . '_' . $timeSplit[0] . $timeSplit[1] . '.' . $nameSplit[1];

                $location = '../file_upload/' . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $location)) {
                    $newFileNameArr[] = $newFileName;
                }
                else {
                    $newFileNameArr = null;
                }
            }

            return $newFileNameArr;
        }
    }