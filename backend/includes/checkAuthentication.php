<?php
    session_start();
    header('Content-Type: application/json');

    $response = array('loggedIn' => false);

    if (isset($_SESSION['user'])) {
        $response['loggedIn'] = true;
        $response['FullName'] = $_SESSION['user']['FullName'];
        $response['EmailAddress'] = $_SESSION['user']['EmailAddress'];
    } else {
        file_put_contents('Session Data:', print_r($_SESSION, TRUE));
    }

    die(json_encode($response));