<?php
    $HostStr = "102.222.124.15";
    $UserNameStr = "mymoyzdi_tyrique";
    $PasswordStr = "^efn7E#&8[GA";
    $DBNameStr = "mymoyzdi_moyo";

    $DBConnectObj = new mysqli($HostStr, $UserNameStr, $PasswordStr, $DBNameStr);

    // Check connection
    if ($DBConnectObj->connect_error) {
        die("Connection failed: " . $DBConnectObj->connect_error);
    }