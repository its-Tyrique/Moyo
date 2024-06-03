<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ../Index.php?message=not_authenticated");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <a href="../booking.html">Booking</a>
</body>
</html>