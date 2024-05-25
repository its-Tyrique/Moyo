<?php
    $HostStr = "";
    $UserNameStr = "";
    $PasswordStr = "";
    $DBNameStr = "";

    $DBConnectObj = new mysqli($HostStr, $UserNameStr, $PasswordStr, $DBNameStr);

    // Check connection
    if ($DBConnectObj->connect_error) {
        die("Connection failed: " . $DBConnectObj->connect_error);
    }
