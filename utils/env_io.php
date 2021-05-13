<?php


    require dirname(__DIR__) . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    class EnvIO
    {
        public static array $form_login_request = [
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => '/wEPDwUKMTkwNDg4MTQ5MQ9kFgICAQ9kFgpmD2QWCgIBDw8WAh4EVGV4dAUuSOG7hiBUSOG7kE5HIFRIw5RORyBUSU4gVFLGr+G7nE5HIMSQ4bqgSSBI4buMQ2RkAgIPZBYCZg8PFgQfAAUNxJDEg25nIG5o4bqtcB4QQ2F1c2VzVmFsaWRhdGlvbmhkZAIDDxAPFgYeDURhdGFUZXh0RmllbGQFBmt5aGlldR4ORGF0YVZhbHVlRmllbGQFAklEHgtfIURhdGFCb3VuZGdkEBUBAlZOFQEgQUU1NjE5NjI2OUFGNDQ3NkI0MjIwNjdDOUI0MjQ1MDQUKwMBZxYBZmQCBA8PFgIeCEltYWdlVXJsBSgvQ01DU29mdC5JVS5XZWIuSW5mby9JbWFnZXMvVXNlckluZm8uZ2lmZGQCBQ9kFgYCAQ8PFgIfAAUGS2jDoWNoZGQCAw8PFgIfAGVkZAIHDw8WAh4HVmlzaWJsZWhkZAICD2QWBAIDDw9kFgIeBm9uYmx1cgUKbWQ1KHRoaXMpO2QCBw8PFgIfAGVkZAIEDw8WAh8GaGRkAgYPDxYCHwZoZBYGAgEPD2QWAh8HBQptZDUodGhpcyk7ZAIFDw9kFgIfBwUKbWQ1KHRoaXMpO2QCCQ8PZBYCHwcFCm1kNSh0aGlzKTtkAgsPZBYIZg8PFgIfAAUJRW1wdHlEYXRhZGQCAQ9kFgJmDw8WAh8BaGRkAgIPZBYCZg8PFgQfAAUNxJDEg25nIG5o4bqtcB8BaGRkAgMPDxYCHwAFtgU8YSBocmVmPSIjIiBvbmNsaWNrPSJqYXZhc2NyaXB0OndpbmRvdy5wcmludCgpIj48ZGl2IHN0eWxlPSJGTE9BVDpsZWZ0Ij4JPGltZyBzcmM9Ii9DTUNTb2Z0LklVLldlYi5JbmZvL2ltYWdlcy9wcmludC5wbmciIGJvcmRlcj0iMCI+PC9kaXY+PGRpdiBzdHlsZT0iRkxPQVQ6bGVmdDtQQURESU5HLVRPUDo2cHgiPkluIHRyYW5nIG7DoHk8L2Rpdj48L2E+PGEgaHJlZj0ibWFpbHRvOj9zdWJqZWN0PUhlIHRob25nIHRob25nIHRpbiBJVSZhbXA7Ym9keT1odHRwczovL3FsZHQudXRjLmVkdS52bi9DTUNTb2Z0LklVLldlYi5JbmZvL0xvZ2luLmFzcHgiPjxkaXYgc3R5bGU9IkZMT0FUOmxlZnQiPjxpbWcgc3JjPSIvQ01DU29mdC5JVS5XZWIuSW5mby9pbWFnZXMvc2VuZGVtYWlsLnBuZyIgIGJvcmRlcj0iMCI+PC9kaXY+PGRpdiBzdHlsZT0iRkxPQVQ6bGVmdDtQQURESU5HLVRPUDo2cHgiPkfhu61pIGVtYWlsIHRyYW5nIG7DoHk8L2Rpdj48L2E+PGEgaHJlZj0iIyIgb25jbGljaz0iamF2YXNjcmlwdDphZGRmYXYoKSI+PGRpdiBzdHlsZT0iRkxPQVQ6bGVmdCI+PGltZyBzcmM9Ii9DTUNTb2Z0LklVLldlYi5JbmZvL2ltYWdlcy9hZGR0b2Zhdm9yaXRlcy5wbmciICBib3JkZXI9IjAiPjwvZGl2PjxkaXYgc3R5bGU9IkZMT0FUOmxlZnQ7UEFERElORy1UT1A6NnB4Ij5UaMOqbSB2w6BvIMawYSB0aMOtY2g8L2Rpdj48L2E+ZGRkyoG6tKOetejm3INvwYKVBbOLz1ENP/MgKbfNBVLLTF4=',
            '__VIEWSTATEGENERATOR' => 'D620498B',
            '__EVENTVALIDATION' => '/wEdAA/vKet7wh+YewIm1y/+I4jpb8csnTIorMPSfpUKU79Fa8zr1tijm/dVbgMI0MJ/5MgoRSoZPLBHamO4n2xGfGAWHW/isUyw6w8trNAGHDe5T/w2lIs9E7eeV2CwsZKam8yG9tEt/TDyJa1fzAdIcnRuY3plgk0YBAefRz3MyBlTcHY2+Mc6SrnAqio3oCKbxYY85pbWlDO2hADfoPXD/5tdAxTm4XTnH1XBeB1RAJ3owlx3skko0mmpwNmsvoT+s7J0y/1mTDOpNgKEQo+otMEzMS21+fhYdbX7HjGORawQMqpdNpKktwtkFUYS71DzGv7vyGkQfdybHrb/DRlkBCRcuPrNRMkgMJV6Y3cQGV72Nw==',
            'PageHeader1$drpNgonNgu' => 'AE56196269AF4476B422067C9B424504',
            'PageHeader1$hidisNotify' => '0',
            'PageHeader1$hidValueNotify' => '.',
            'txtUserName' => '',
            'txtPassword' => '',
            'btnSubmit' => 'Đăng nhập',
            'hidUserId' => '',
            'hidUserFullName' => ''
        ];

        public static array $form_get_mark_request = [
            '__EVENTTARGET' => 'drpHK',
            '__EVENTARGUMENT' => '',
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => '',
            '__VIEWSTATEGENERATOR' => 'C7A1B26E',
            '__EVENTVALIDATION' => '',
            'PageHeader1$drpNgonNgu' => 'AE56196269AF4476B422067C9B424504',
            'PageHeader1$hidisNotify' => '0',
            'PageHeader1$hidValueNotify' => '.',
            'drpField' => '36E0D94B3AE842FEB692AC231A7C434A',
            'drpHK' => '',
            'drpFilter' => '1',
            'hidSymbolMark' => '0',
            'hidFieldId' => '36E0D94B3AE842FEB692AC231A7C434A',
            'hidFieldName' => 'Công nghệ thông tin',
            'hidStudentId' => ''
        ];

        public static array $form_get_exam_schedule_request = [
            '__EVENTTARGET' => 'drpSemester',
            '__EVENTARGUMENT' => '',
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => '',
            '__VIEWSTATEGENERATOR' => 'C663F6BA',
            '__EVENTVALIDATION' => '',
            'PageHeader1$drpNgonNgu' => 'AE56196269AF4476B422067C9B424504',
            'PageHeader1$hidisNotify' => '0',
            'PageHeader1$hidValueNotify' => '.',
            'drpSemester' => '',
            'drpDotThi' => '',
            'drpExaminationNumber' => '0',
            'hidShowShiftEndTime' => '0',
            'hidExamShowNote' => '',
            'hidStudentId' => '',
            'hidEsShowRoomCode' => '',
            'hidDangKyChungChiThuocHeRieng' => ''
        ];

        public static function loadEnv ()
        {
            try {
                $dotenv = Dotenv::createMutable(dirname(__DIR__));
                $dotenv->load();

            } catch (Exception $e) {
            }
        }

    }
