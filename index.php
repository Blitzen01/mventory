<?php

    $login_page = 'web_content/login.php';
    $error_page = 'web_content/error.php';

    if (file_exists($login_page)) {
        header('Location: ' . $login_page);
        exit;
    } else {
        header('Location: ' . $error_page);
        exit;
    }

?>