<?php
    require 'simple_html_dom.php';
    ini_set('default_socket_timeout', 2);
    $streamContext = stream_context_create(array(
        'http' => array(
            'method' => 'GET',
            'timeout' => 900
        )
    ));
    $a = file_get_contents('https://qldt.utc.edu.vn/CMCSoft.IU.Web.Info/Login.aspx', 0, $streamContext);
echo $a;