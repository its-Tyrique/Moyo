<?php
    session_start();
    session_destroy();
    session_abort();
    header("Location: https://mymoyo.co.za");
    exit();
