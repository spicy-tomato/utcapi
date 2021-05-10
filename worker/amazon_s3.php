<?php


    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    class AWS
    {
        private string $bucket_name;
        private Aws\S3\S3Client $s3;

        public function __construct ()
        {
            EnvIO::loadEnv();
            $this->bucket_name = $_ENV['BUCKET_NAME'];

            $this->s3 = new Aws\S3\S3Client([
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key' => $_ENV['AWS_ACCESS_KEY_ID'],
                    'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
                ]
            ]);
        }

        public function uploadFile ($file_name, $file_location)
        {
            $finfo     = new finfo(FILEINFO_MIME_TYPE);
            $file_mime = $finfo->file($file_location);

            try {
                $this->s3->putObject([
                    'Bucket' => $this->bucket_name,
                    'Key' => $file_name,
                    'SourceFile' => $file_location,
                    'ACL' => 'public-read',
                    'ContentType' => $file_mime
                ]);

            } catch (Aws\Exception\AwsException $e) {
            }
        }

        public function getDataFromFile ($ile_name)
        {
            $result = $this->s3->getObject([
                'Bucket' => 'utcapi-file-upload',
                'Key' => $ile_name,
                'Body' => 'this is the body!',
            ]);

            return $result['Body'];
        }
    }
