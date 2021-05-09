<?php


    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    class AWS
    {
        private string $bucket_name;
        private string $access_key_id;
        private string $secret_access_key;

        public function __construct ()
        {
            EnvIO::loadEnv();
            $this->bucket_name       = $_ENV['BUCKET_NAME'];
            $this->access_key_id     = $_ENV['AWS_ACCESS_KEY_ID'];
            $this->secret_access_key = $_ENV['AWS_SECRET_ACCESS_KEY'];
        }

        public function upload ($file_name)
        {
            $file_location = '../file_upload/' . $file_name;

            $finfo     = new finfo(FILEINFO_MIME_TYPE);
            $file_mime = $finfo->file($file_location);

            $s3 = new Aws\S3\S3Client([
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key' => $this->access_key_id,
                    'secret' => $this->secret_access_key,
                ]
            ]);


            try {
                $s3->putObject([
                    'Bucket' => $this->bucket_name,
                    'Key' => $file_name,
                    'SourceFile' => $file_location,
                    'ACL' => 'public-read',
                    'ContentType' => $file_mime
                ]);

            } catch (Aws\Exception\AwsException $e) {
            }
        }
    }
