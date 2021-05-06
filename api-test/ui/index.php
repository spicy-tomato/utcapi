<?php
    include_once './shared/functions.php';
    session_start();
    shield();
    header('Location: ./login');
