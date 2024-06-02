<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    /** @var mysqli $DBConnectObj */
    require_once "../includes/db_connection.php";

    class User {
        public $FirstNameStr, $LastNameStr, $EmailAddressStr, $UsernameStr, $PasswordStr, $MainContactNumberStr,
            $IdentificationNumberStr, $AccessBlockedBool, $UserRoleInt;
        private $DBConnectObj;

        public function __construct($DBConnectObj) {
            $this->DBConnectObj = $DBConnectObj;
        }

        // Methods
        function createUser($FirstNameStr, $LastNameStr, $EmailAddressStr, $UsernameStr, $PasswordStr,
                            $MainContactNumberStr, $IdentificationNumberStr, $AccessBlockedBool, $UserRoleInt) {
            $CreateUserStmt = $this->DBConnectObj->prepare("INSERT INTO account (FirstName, LastName, EmailAddress, 
                                        Username, Password, MainContactNumber, IdentificationNumber, AccessBlocked, UserRole) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $CreateUserStmt->bind_param("sssssssbi", $FirstNameStr, $LastNameStr, $EmailAddressStr, $UsernameStr,
                                        $PasswordStr, $MainContactNumberStr, $IdentificationNumberStr, $AccessBlockedBool, $UserRoleInt);

            if (!$CreateUserStmt->execute()) {
                die(json_encode(array('error' => 'CreateUser execute failed: ' . $CreateUserStmt->error)));
            }
            die(json_encode(['success' => true, 'User created with ID: '.$CreateUserStmt->insert_id]));
        }

        function loadUser($UserIdInt) {
            $loadPersonStmt = $this->DBConnectObj->prepare("SELECT * FROM account WHERE Id = ?");
            $loadPersonStmt->bind_param("i", $UserIdInt);

            if (!$loadPersonStmt->execute()) {
                die(json_encode(['error' =>"loadUser execute failed: " . $loadPersonStmt->error]));
            } else {
                $Result = $loadPersonStmt->get_result();

                if (!$Result) {
                    die(json_encode(['error' => 'Failed to get result from loadPersonStmt execution']));
                }
                $PersonArr = $Result->fetch_assoc();
                die(json_encode($PersonArr));
            }
        }

        // TODO: Add proper saveUser as done in createUser.php
        function saveUser($UserIdInt, $FirstNameStr, $LastNameStr, $EmailAddressStr, $UsernameStr, $PasswordStr,
                            $MainContactNumberStr, $IdentificationNumberStr, $AccessBlockedBool, $UserRoleInt) {

            $SaveUserStmt = $this->DBConnectObj->prepare("
                            INSERT INTO account (Id, FirstName, LastName, EmailAddress, Username, Password, MainContactNumber, 
                                                IdentificationNumber, AccessBlocked, UserRole)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE
                            Id = VALUES(Id),
                            FirstName = VALUES(FirstName), 
                            LastName = VALUES(LastName),
                            EmailAddress = VALUES(EmailAddress), 
                            Username = VALUES(Username),
                            Password = VALUES(Password),
                            MainContactNumber = VALUES(MainContactNumber),
                            IdentificationNumber = VALUES(IdentificationNumber),
                            AccessBlocked = VALUES(AccessBlocked),
                            UserRole = VALUES(UserRole)
                        ");
            $SaveUserStmt->bind_param("isssssssbi", $UserIdInt, $FirstNameStr, $LastNameStr, $EmailAddressStr, $UsernameStr,
                        $PasswordStr, $MainContactNumberStr, $IdentificationNumberStr, $AccessBlockedBool, $UserRoleInt);

            if (!$SaveUserStmt->execute()) {
                die(json_encode(["SaveUser execute failed: ".$SaveUserStmt->error]));
            }
            die(json_encode(["User saved with ID: ".$UserIdInt]));
        }

        function deleteUser($UserIdInt) {
            $DeleteUserStmt = $this->DBConnectObj->prepare("DELETE FROM account WHERE Id = ?");
            $DeleteUserStmt->bind_param("i", $UserIdInt);

            if (!$DeleteUserStmt->execute()) {
                die(json_encode(["DeleteUser execute failed: " . $DeleteUserStmt->error]));
            } else {
                die(json_encode(["User deleted with ID: " . $UserIdInt]));
            }
        }

        function loadAllUsers() {
            $LoadAllUsersStmt = $this->DBConnectObj->prepare("SELECT * FROM account");
            if (!$LoadAllUsersStmt->execute()) {
                die(json_encode(['error' => 'LoadAllUsers execute failed: ' . $LoadAllUsersStmt->error]));
            } else {
                $Result = $LoadAllUsersStmt->get_result();

                if (!$Result) {
                    die(json_encode(['error' => 'Failed to get result from LoadAllUsers execution']));
                }
                $UsersArr = [];
                while ($UserArr = $Result->fetch_assoc()) {
                    $UsersArr[] = $UserArr;
                }
                die(json_encode($UsersArr));
            }
        }

        function deleteAllUsers() {
            $DeleteAllUsersStmt = $this->DBConnectObj->prepare("DELETE FROM account");
            if (!$DeleteAllUsersStmt->execute()) {
                die(json_encode(["DeleteAllUsers execute failed: " . $DeleteAllUsersStmt->error]));
            } else {
                die(json_encode(["All users deleted"]));
            }
        }
    }

    $UserObj = new User($DBConnectObj);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Decode JSON input
        $inputData = json_decode(file_get_contents("php://input"), true);

        // Check if data is decoded properly
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            die(json_encode(['error' => 'Invalid JSON input']));
        }
        file_put_contents('log.txt', print_r($inputData, TRUE), FILE_APPEND);

        switch (true) {
            case isset($inputData['deletePerson']) && isset($inputData['UserId']):
                $UserIdInt = intval($inputData['UserId']);

                try {
                    $UserObj->deleteUser($UserIdInt);
                    http_response_code(200);
                    die(json_encode(['success' => true, 'message' => 'Person deleted successfully']));
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['Backend failed to delete user']);
                }
                break;

//            case isset($inputData['deleteAllRecords']):
//                try {
//                    $UserObj->deleteAllUsers();
//                    http_response_code(200);
//                    die(json_encode(['success' => true, 'message' => 'All records deleted successfully']));
//                } catch (Exception $e) {
//                    http_response_code(500);
//                    echo json_encode(['error' => 'Failed to delete all records']);
//                }
//                break;

//            case isset($inputData['updatePerson']):
//                $UserObj->saveUser($inputData['PersonId'], $inputData['FirstName'], $inputData['Surname'], $inputData['DateOfBirth'], $inputData['Email'], $AgeInt);
//                break;

            case isset (
                    $inputData['firstName']) && isset($inputData['lastName']) && isset($inputData['idNumber'])
                    && isset($inputData['phone']) && isset($inputData['email']) && isset($inputData['password'])
                    && isset($inputData['confirmPassword']) && isset($inputData['role']
                ):
                try {
                    $UserObj->createUser($inputData['firstName'], $inputData['lastName'], $inputData['email'], $inputData['email'],
                        $inputData['password'], $inputData['phone'], $inputData['idNumber'], 0, $inputData['role']);
                } catch (Exception $e) {
                    http_response_code(500);
                    die(json_encode('Failed to create user'));
                }
                break;

            default:
                http_response_code(400);
                die(json_encode('Invalid request'));
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['loadPerson'], $_GET['PersonId'])) {
            $UserIdInt = intval($_GET['PersonId']);
            $UserObj->loadUser($UserIdInt);
        } else {
            $UserObj->loadAllUsers();
        }
    } else {
        die(json_encode(['error' => 'Invalid request method or no loadAllUsers parameter found in the request']));
    }

$DBConnectObj->close();