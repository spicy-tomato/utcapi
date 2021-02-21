<?php
    include_once './shared/functions.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    shield();
    header('Location: ./login');
