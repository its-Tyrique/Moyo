<?php
session_start();

/** @var mysqli $DBConnectObj */
require_once "../includes/db_connection.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw GET data
    $inputData = json_decode(file_get_contents("php://input"), true);

    // Validate input data
    if (!is_array($inputData)) {
        // Log raw POST data
        file_put_contents("post_data.txt", file_get_contents("php://input"));
        die(json_encode(array("error" => "Invalid input")));
    }

    // Variables to store user input
    $EmailStr = htmlspecialchars($inputData['email']);
    $PasswordStr = htmlspecialchars($inputData['password']);

    // Debugging: Check the received email and password
    file_put_contents("debug.txt", "Email: $EmailStr, Password: $PasswordStr\n", FILE_APPEND);

    $LoadUserStmt = $DBConnectObj->prepare("SELECT * FROM account WHERE EmailAddress = ?");
    if ($LoadUserStmt === false) {
        file_put_contents("debug.txt", "Prepare statement failed: " . $DBConnectObj->error . "\n", FILE_APPEND);
        die(json_encode(array("error" => "Database error")));
    }
    $LoadUserStmt->bind_param("s", $EmailStr);
    if ($LoadUserStmt->execute() === false) {
        file_put_contents("debug.txt", "Execute statement failed: " . $LoadUserStmt->error . "\n", FILE_APPEND);
        die(json_encode(array("error" => "Database error")));
    }
    $LoadUserResult = $LoadUserStmt->get_result();

    // Debugging: Check the number of rows returned
    file_put_contents("debug.txt", "Number of rows: " . $LoadUserResult->num_rows . "\n", FILE_APPEND);

    if ($LoadUserResult->num_rows > 0) {
        $UserRow = $LoadUserResult->fetch_assoc();

        // Debugging: Check the fetched user row
        file_put_contents("debug.txt", "UserRow: " . print_r($UserRow, true) . "\n", FILE_APPEND);

        if (password_verify($PasswordStr, $UserRow['Password'])) {
            // Create user session data array
            $_SESSION['user'] = [
                'Id' => $UserRow['Id'],
                'FullName' => $UserRow['FullName'],
                'FirstName' => $UserRow['FirstName'],
                'LastName' => $UserRow['LastName'],
                'EmailAddress' => $UserRow['EmailAddress'],
                'Username' => $UserRow['Username'],
                'ProfilePicturePath' => $UserRow['ProfilePicturePath'],
                'MainContactNumber' => $UserRow['MainContactNumber'],
                'Title' => $UserRow['Title'],
                'DateOfBirth' => $UserRow['DateOfBirth'],
                'IdentificationNumber' => $UserRow['IdentificationNumber'],
                'Gender' => $UserRow['Gender'],
                'AccessBlocked' => $UserRow['AccessBlocked'],
                'UserRole' => $UserRow['UserRole']
            ];

            die(json_encode(array(
                "success" => true,
                "message" => "Login Successful. Redirecting...",
                "userRole" => $UserRow['UserRole']
            )));
        }
        // TODO: (if login failed) Increment login attempts
    } else {
        die(json_encode(array("error" => "Invalid email or password.")));
    }
}
?>
