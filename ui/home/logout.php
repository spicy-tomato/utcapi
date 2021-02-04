<?php
    session_start();
    unset($_SESSION['department_name']);
    header('Location: ../login');
