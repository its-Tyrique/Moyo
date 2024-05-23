<?php
    /** @var mysqli $DBConnectObj */
    require_once "../includes/db_connection.php";

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $inputData = json_decode(file_get_contents("php://input"), true);

        // Validate input data
        if (!is_array($inputData)) {
            // Log raw POST data
            file_put_contents("post_data.txt", file_get_contents("php://input"));
            die(json_encode(array("error" => "Invalid input")));
        }

        // Variables to store user input
        $FirstNameStr = htmlspecialchars($inputData['firstName']);
        $LastNameStr = htmlspecialchars($inputData['lastName']);
        $TitleStr = htmlspecialchars($inputData['title']);
        $IdNumberStr = htmlspecialchars($inputData['idNumber']);
        $PhoneStr = htmlspecialchars($inputData['phone']);
        $EmailStr = htmlspecialchars($inputData['email']);
        $PasswordStr = htmlspecialchars($inputData['password']);
        $ConfirmPasswordStr = htmlspecialchars($inputData['confirmPassword']);
        $GenderStr = htmlspecialchars($inputData['gender']);
        $DateOfBirthStr = htmlspecialchars($inputData['dateOfBirth']);

        $UsernameStr = $EmailStr;
        $FullNameStr = $FirstNameStr . " " . $LastNameStr;

        // Check if passwords match
        if ($PasswordStr !== $ConfirmPasswordStr) {
            die(json_encode(array("error" => "Passwords do not match")));
        }

        // Set default profile picture
        $ProfilePictureStr = "../assets/user.svg";

        // Hash the password
        $HashedPassword = password_hash($PasswordStr, PASSWORD_BCRYPT);

        /*
            TODO: check if email already exits then do not send verification email
                , but still display success to the user (security measure)
        */

        // TODO: send verification email

        // Prepare SQL statement
        $CreateUserStmt = $DBConnectObj->prepare("
            INSERT INTO account (
                Fullname, FirstName, LastName, Title, IdentificationNumber, MainContactNumber, EmailAddress, Password, Gender, 
                                 DateOfBirth, ProfilePicturePath, Username, UserRole
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,1)
        ");

        // TODO: remove error message and replace with a generic error message

        // Check if the statement was prepared successfully
        if (!$CreateUserStmt) {
            die(json_encode(array("error" => "Failed to prepare statement: " . $DBConnectObj->error)));

        }

        // Bind parameters
        $CreateUserStmt->bind_param("ssssssssssss",
            $FullNameStr, $FirstNameStr, $LastNameStr, $TitleStr, $IdNumberStr, $PhoneStr, $EmailStr,
            $HashedPassword, $GenderStr, $DateOfBirthStr, $ProfilePictureStr, $UsernameStr
        );

        // Execute statement
        if ($CreateUserStmt->execute()) {
            echo json_encode(array("success" => "User created successfully"));
        } else {
            echo json_encode(array("error" => "Failed to create user: " . $CreateUserStmt->error));
        }

        // Close statement
        $CreateUserStmt->close();
    } else {
        echo json_encode(array("error" => "Invalid request method"));
    }

    // Close database connection
    $DBConnectObj->close();

