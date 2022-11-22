<?php

    session_start();
    
    session_unset();

    header('Location: user_login.php');

?>