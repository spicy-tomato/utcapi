<?php

    require dirname(__DIR__) . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    class EnvIO
    {
        public static array $faculty_class_info = [
            'MXD' => [':class_name' => 'Lớp Máy xây dựng', ':id_faculty' => 'CK'],
            'CDT' => [':class_name' => 'Lớp Cơ điện tử', ':id_faculty' => 'CK'],
            'CNCTCK' => [':class_name' => 'Lớp Công nghệ chế tạo cơ khí', ':id_faculty' => 'CK'],
            'TDHTKCK' => [':class_name' => 'Lớp Tự động hoá thiết kế cơ khí', ':id_faculty' => 'CK'],
            'CKOTO' => [':class_name' => 'Lớp Cơ khí ô tô', ':id_faculty' => 'CK'],
            'VLVH.CNTT' => [':class_name' => 'Lớp Công nghệ thông tin (Hệ vừa làm vừa học)', ':id_faculty' => 'CNTT'],
            'CNTT' => [':class_name' => 'Lớp Công nghệ thông tin', ':id_faculty' => 'CNTT'],
            'DBO' => [':class_name' => 'Lớp Đường bộ', ':id_faculty' => 'CT'],
            'CH2' => [':class_name' => 'Lớp Kỹ thuật xây dựng cầu - hầm', ':id_faculty' => 'CT'],
            'DHMETRO' => [':class_name' => 'Lớp Kỹ thuật xây dựng đường hầm và metro', ':id_faculty' => 'CT'],
            'CDBO' => [':class_name' => 'Lớp Kỹ thuật cầu đường bộ', ':id_faculty' => 'CT'],
            'CDS' => [':class_name' => 'Lớp Kỹ thuật cầu đường sắt', ':id_faculty' => 'CT'],
            'CTGTCC' => [':class_name' => 'Lớp Công trình giao thông công chính', ':id_faculty' => 'CT'],
            'DSDT' => [':class_name' => 'Lớp Kỹ thuật đường sắt đô thị', ':id_faculty' => 'CT'],
            'KTGIS' => [':class_name' => 'Lớp Kỹ thuật GIS và trắc địa công trình giao thông', ':id_faculty' => 'CT'],
            'TDH' => [':class_name' => 'Lớp Tự động hoá thiết kế cầu đường', ':id_faculty' => 'CT'],
            'CH' => [':class_name' => 'Lớp Kỹ thuật xây dựng cầu - hầm', ':id_faculty' => 'CT'],
            'KTXDCTGT(QT)' => [':class_name' => 'Lớp Kỹ thuật xây dựng công trình giao thông (QT)', ':id_faculty' => 'CT'],
            'KTDTTHCN' => [':class_name' => 'Lớp Kỹ thuật điện tử và tin học công nghiệp', ':id_faculty' => 'DDT'],
            'KTĐK&TDH' => [':class_name' => 'Lớp Trang bị điện trong công nghiệp và giao thông', ':id_faculty' => 'DDT'],
            'TBD' => [':class_name' => 'Lớp Kỹ thuật xây dựng cầu - hầm', ':id_faculty' => 'DDT'],
            'KCXD' => [':class_name' => 'Lớp Kết cấu xây dựng', ':id_faculty' => 'KTXD'],
            'KTHTDT' => [':class_name' => 'Lớp Kỹ thuật hạ tầng đô thị', ':id_faculty' => 'KTXD'],
            'XDDDCN' => [':class_name' => 'Lớp Xây dựng dân dụng và công nghiệp', ':id_faculty' => 'KTXD'],
            'VLCNXDGT' => [':class_name' => 'Lớp Vật liệu và công nghệ xây dựng', ':id_faculty' => 'KTXD'],
            'QTDNVT' => [':class_name' => 'Lớp Quản trị doanh nghiệp vận tải', ':id_faculty' => 'VTKT'],
            'KTVTDL' => [':class_name' => 'Lớp Kinh tế vận tải và du lịch', ':id_faculty' => 'VTKT'],
            'KTVTOTO' => [':class_name' => 'Lớp Kinh tế vận tải ô tô', ':id_faculty' => 'VTKT'],
            'QTDNBCVT' => [':class_name' => 'Lớp Quản trị doanh nghiệp bưu chính viễn thông', ':id_faculty' => 'VTKT']
        ];

        public static array $faculties = [
            'CT' => [
                'Kỹ thuật xây dựng công trình giao thông (59)',
                'Kỹ thuật xây dựng Cầu - Đường bộ',
                'Kỹ thuật xây dựng Đường bộ',
                'Kỹ thuật Giao thông đường bộ',
                'Kỹ thuật xây dựng Đường sắt đô thị',
                'Kỹ thuật xây dựng Đường sắt',
                'Kỹ thuật xây dựng Cầu hầm',
                'Kỹ thuật xây dựng Đường hầm - Metro',
                'Kỹ thuật xây dựng Cầu - Đường sắt',
                'Địa kỹ thuật xây dựng Công trình giao thông',
                'Công trình Giao thông đô thị',
                'Kỹ thuật xây dựng Đường ô tô & Sân bay',
                'Kỹ thuật xây dựng Cầu đường ô tô & Sân bay',
                'Công trình Giao thông công chính',
                'Tự động hóa Thiết kế cầu đường',
                'Kỹ thuật GIS và Trắc địa CTGT',
                'Kỹ thuật xây dựng Công trình thủy'],

            'QLXD' => [
                'Kinh tế xây dựng Công trình giao thông',
                'Kinh tế quản lý khai thác cầu đường',
                'Quản lý xây dựng'],

            'VTKT' => [
                'Kinh tế vận tải du lịch',
                'Kinh tế vận tải hàng không',
                'Kinh tế vận tải ô tô',
                'Kinh tế vận tải đường sắt',
                'Kinh tế vận tải thủy bộ',
                'Điều khiển các quá trình vận tải',
                'Khai thác và quản lý đường sắt đô thị',
                'Tổ chức quản lý khai thác cảng hàng không',
                'Vận tải đa phương thức',
                'Vận tải đường sắt',
                'Vận tải kinh tế đường bộ và thành phố',
                'Vận tải thương mại quốc tế',
                'Quy hoạch và quản lý GTVT đô thị',
                'Vận tải và kinh tế đường sắt',
                'Logistics',
                'Quản trị doanh nghiệp vận tải',
                'Quản trị doanh nghiệp xây dựng',
                'Quản trị kinh doanh GTVT',
                'Quản trị doanh nghiệp Bưu chính viễn thông',
                'Quản trị Logistics',
                'Kế toán',
                'Kinh tế Bưu chính viễn thông'],

            'KTXD' => [
                'Xây dựng dân dụng và Công nghiệp',
                'Kết cấu xây dựng',
                'Kỹ thuật hạ tầng đô thị',
                'Vật liệu và Công nghiệp xây dựng'],

            'DDT' => [
                'Trang thiết bị trong Công nghiệp và Giao thông',
                'Hệ thống điện Giao thông và Công nghiệp',
                'Kỹ thuật điều khiển và Tự động hóa GT',
                'Kỹ thuật tín hiệu Đường sắt',
                'Tự động hóa',
                'Thông tin tín hiệu',
                'Kỹ thuật điện tử và Tin học công nghiệp',
                'Kỹ thuật thông tin và truyền thông',
                'Kỹ thuật viễn thông'],

            'CK' => [
                'Đầu máy toa xe',
                'Cơ giới hóa xây dựng cầu đường',
                'Cơ khí giao thông công chính',
                'Đầu máy',
                'Kỹ thuật Máy động lực',
                'Máy xây dựng',
                'Tàu điện Metro',
                'Thiết bị mặt đất Cảng hàng không',
                'Toa xe',
                'Công nghệ chế tạo cơ khí',
                'Cơ điện tử',
                'Tự động hóa thiết kế cơ khí',
                'Kỹ thuật ô tô',
                'Kỹ thuật nhiệt lạnh',
                'Điều hòa không khí và thông gió công trình XD'],

            'CNTT' => [
                'Công nghệ thông tin'],

            'MT&ATGT' => [
                'Kỹ thuật An toàn giao thông',
                'Kỹ thuật môi trường'],

            'KHCB' => [
                'Toán ứng dụng']
        ];

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

        public static function loadEnv()
        {
            try {
                $dotenv = Dotenv::createMutable(dirname(__DIR__));
                $dotenv->load();

            } catch (Exception $e) {
            }
        }

    }
