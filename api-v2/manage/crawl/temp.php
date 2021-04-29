<?php
    require 'simple_html_dom.php';
    //The url you wish to send the POST request to
    file_get_contents('https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/Login.aspx');
    $nameSplit = explode(' ', $http_response_header[3]);
    $location  = explode('/', $nameSplit[1]);

    $access_token     = $location[2];
    $url_login        = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/' . $access_token . '/Login.aspx';
    $url_student_mark = 'https://qldt.utc.edu.vn/CMCSoft.IU.Web.info/' . $access_token . '/StudentMark.aspx';
    //The data you want to send via POST
    $form_login_request = [
        '__EVENTTARGET' => '',
        '__EVENTARGUMENT' => '',
        '__LASTFOCUS' => '',
        '__VIEWSTATE' => '/wEPDwUKMTkwNDg4MTQ5MQ9kFgICAQ9kFgpmD2QWCgIBDw8WAh4EVGV4dAUuSOG7hiBUSOG7kE5HIFRIw5RORyBUSU4gVFLGr+G7nE5HIMSQ4bqgSSBI4buMQ2RkAgIPZBYCZg8PFgQfAAUNxJDEg25nIG5o4bqtcB4QQ2F1c2VzVmFsaWRhdGlvbmhkZAIDDxAPFgYeDURhdGFUZXh0RmllbGQFBmt5aGlldR4ORGF0YVZhbHVlRmllbGQFAklEHgtfIURhdGFCb3VuZGdkEBUBAlZOFQEgQUU1NjE5NjI2OUFGNDQ3NkI0MjIwNjdDOUI0MjQ1MDQUKwMBZxYBZmQCBA8PFgIeCEltYWdlVXJsBSgvQ01DU29mdC5JVS5XZWIuSW5mby9JbWFnZXMvVXNlckluZm8uZ2lmZGQCBQ9kFgYCAQ8PFgIfAAUGS2jDoWNoZGQCAw8PFgIfAGVkZAIHDw8WAh4HVmlzaWJsZWhkZAICD2QWBAIDDw9kFgIeBm9uYmx1cgUKbWQ1KHRoaXMpO2QCBw8PFgIfAGVkZAIEDw8WAh8GaGRkAgYPDxYCHwZoZBYGAgEPD2QWAh8HBQptZDUodGhpcyk7ZAIFDw9kFgIfBwUKbWQ1KHRoaXMpO2QCCQ8PZBYCHwcFCm1kNSh0aGlzKTtkAgsPZBYIZg8PFgIfAAUJRW1wdHlEYXRhZGQCAQ9kFgJmDw8WAh8BaGRkAgIPZBYCZg8PFgQfAAUNxJDEg25nIG5o4bqtcB8BaGRkAgMPDxYCHwAFtgU8YSBocmVmPSIjIiBvbmNsaWNrPSJqYXZhc2NyaXB0OndpbmRvdy5wcmludCgpIj48ZGl2IHN0eWxlPSJGTE9BVDpsZWZ0Ij4JPGltZyBzcmM9Ii9DTUNTb2Z0LklVLldlYi5JbmZvL2ltYWdlcy9wcmludC5wbmciIGJvcmRlcj0iMCI+PC9kaXY+PGRpdiBzdHlsZT0iRkxPQVQ6bGVmdDtQQURESU5HLVRPUDo2cHgiPkluIHRyYW5nIG7DoHk8L2Rpdj48L2E+PGEgaHJlZj0ibWFpbHRvOj9zdWJqZWN0PUhlIHRob25nIHRob25nIHRpbiBJVSZhbXA7Ym9keT1odHRwczovL3FsZHQudXRjLmVkdS52bi9DTUNTb2Z0LklVLldlYi5JbmZvL0xvZ2luLmFzcHgiPjxkaXYgc3R5bGU9IkZMT0FUOmxlZnQiPjxpbWcgc3JjPSIvQ01DU29mdC5JVS5XZWIuSW5mby9pbWFnZXMvc2VuZGVtYWlsLnBuZyIgIGJvcmRlcj0iMCI+PC9kaXY+PGRpdiBzdHlsZT0iRkxPQVQ6bGVmdDtQQURESU5HLVRPUDo2cHgiPkfhu61pIGVtYWlsIHRyYW5nIG7DoHk8L2Rpdj48L2E+PGEgaHJlZj0iIyIgb25jbGljaz0iamF2YXNjcmlwdDphZGRmYXYoKSI+PGRpdiBzdHlsZT0iRkxPQVQ6bGVmdCI+PGltZyBzcmM9Ii9DTUNTb2Z0LklVLldlYi5JbmZvL2ltYWdlcy9hZGR0b2Zhdm9yaXRlcy5wbmciICBib3JkZXI9IjAiPjwvZGl2PjxkaXYgc3R5bGU9IkZMT0FUOmxlZnQ7UEFERElORy1UT1A6NnB4Ij5UaMOqbSB2w6BvIMawYSB0aMOtY2g8L2Rpdj48L2E+ZGRkyoG6tKOetejm3INvwYKVBbOLz1ENP/MgKbfNBVLLTF4=',
        '__VIEWSTATEGENERATOR' => 'D620498B',
        '__EVENTVALIDATION' => '/wEdAA/vKet7wh+YewIm1y/+I4jpb8csnTIorMPSfpUKU79Fa8zr1tijm/dVbgMI0MJ/5MgoRSoZPLBHamO4n2xGfGAWHW/isUyw6w8trNAGHDe5T/w2lIs9E7eeV2CwsZKam8yG9tEt/TDyJa1fzAdIcnRuY3plgk0YBAefRz3MyBlTcHY2+Mc6SrnAqio3oCKbxYY85pbWlDO2hADfoPXD/5tdAxTm4XTnH1XBeB1RAJ3owlx3skko0mmpwNmsvoT+s7J0y/1mTDOpNgKEQo+otMEzMS21+fhYdbX7HjGORawQMqpdNpKktwtkFUYS71DzGv7vyGkQfdybHrb/DRlkBCRcuPrNRMkgMJV6Y3cQGV72Nw==',
        'PageHeader1$drpNgonNgu' => 'AE56196269AF4476B422067C9B424504',
        'PageHeader1$hidisNotify' => '0',
        'PageHeader1$hidValueNotify' => '.',
        'txtUserName' => '191212716',
        'txtPassword' => md5('15/08/20011'),
        'btnSubmit' => 'Đăng nhập',
        'hidUserId' => '',
        'hidUserFullName' => ''
    ];

    //url-ify the data for the POST
    $fields_string = http_build_query($form_login_request);
    //    echo http_build_query($fields);
    //open connection
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url_login);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'c.txt');
    $result = curl_exec($ch);

//    curl_setopt($ch, CURLOPT_URL, $url_student_mark);
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_COOKIEJAR, 'c.txt');
//    $result = curl_exec($ch);
//    echo $result;
    $html = new simple_html_dom();
    $html->load($result);
        $hidden_id = $html->find('input[name=txtUserName]', 0);
        if (!empty($hidden_id))
        {
            echo 1111111;
        }
        $view_state = $html->find('input[name=__VIEWSTATE]', 0)->value;
        $event_validation = $html->find('input[name=__EVENTVALIDATION]', 0)->value;

    //        echo $hidden_id;
    //    $aa = $html->find('select[name=drpHK] option');
    //    unset($aa[0]);
    //    echo $aa[1]->innertext;
    //
    //    foreach ($aa as $a)
    //    {
    //        echo $a->innertext;
    //    }

//    $tr = $html->find('table[id=tblStudentMark] tr');
//        $tr = $html->find('td[class=labelheader]');
//
//        echo $tr[0]->innertext;

//        echo 111;

    //    echo $aa[0]->children(0)->innertext;
    //    foreach ($tr as $item)
    //    {
    //        echo $item->innertext;
    //        echo '<br>';
    //    }
    //        $a = "ffdf";
    //        var_dump(explode(" ", $a));
//    echo count($tr);
//    foreach ($tr as $item) {
//        $a = explode("<br><br>", $item->children(1)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        $a = explode("<br><br>", $item->children(2)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        $a = explode("<br><br>", $item->children(3)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        $a = explode("<br><br>", $item->children(8)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        $a = explode("<br><br>", $item->children(9)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        $a = explode("<br><br>", $item->children(10)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        $a = explode("<br><br>", $item->children(1)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        $a = explode("<br><br>", $item->children(12)->innertext);
//        echo isset($a[1]) ? $a[1] : $a[0];
//        echo '<br>';
//    }
    //    echo count($tr);
    //    var_dump($aa);
    //    echo $result;
//        $fields2 = [
//            '__EVENTTARGET' => 'drpHK',
//            '__EVENTARGUMENT' => '',
//            '__LASTFOCUS' => '',
//            '__VIEWSTATE' => $view_state,
//            '__VIEWSTATEGENERATOR' => 'C7A1B26E',
//            '__EVENTVALIDATION' => $event_validation,
//            'PageHeader1$drpNgonNgu'     => 'AE56196269AF4476B422067C9B424504',
//            'PageHeader1$hidisNotify' => '0',
//            'PageHeader1$hidValueNotify' => '.',
//            'drpField' => '36E0D94B3AE842FEB692AC231A7C434A',
//            'drpHK' => '19_20_1 ',
//            'drpFilter' => '1',
//            'hidSymbolMark' => '0',
//            'hidFieldId' => '36E0D94B3AE842FEB692AC231A7C434A',
//            'hidFieldName' => 'Công nghệ thông tin',
//            'hidStudentId' => $hidden_id
//        ];
//        $fields_string = http_build_query($fields2);
//        curl_setopt($ch, CURLOPT_URL, $url_student_mark);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//        curl_setopt($ch, CURLOPT_COOKIEJAR, 'c.txt');
//
//        $result = curl_exec($ch);
//        echo $result;

    curl_close($ch);
