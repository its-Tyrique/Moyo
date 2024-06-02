<?php
    /** @var mysqli $DBConnectObj */
    require_once "../includes/db_connection.php";

    class Bus {
        public $BusNumberStr, $BusCapacityInt;
        private $DBConnectObj;

        public function __construct($DBConnectObj) {
            $this->DBConnectObj = $DBConnectObj;
        }

        // Methods
        function createBus($BusNumberStr, $BusCapacityInt) {
            $CreateBusStmt = $this->DBConnectObj->prepare("INSERT INTO bus (BusNumber, Capacity) 
                                            VALUES (?, ?)");
            $CreateBusStmt->bind_param("si", $BusNumberStr, $BusCapacityInt);

            if (!$CreateBusStmt->execute()) {
                die(json_encode(array('error' => 'CreateBus execute failed: ' . $CreateBusStmt->error)));
            }
            die(json_encode(['success' => true, 'Bus created with ID: '.$CreateBusStmt->insert_id]));
        }

        function loadBus($BusIdInt) {
            $LoadBusStmt = $this->DBConnectObj->prepare("SELECT * FROM bus WHERE Id = ?");
            $LoadBusStmt->bind_param("i", $BusIdInt);

            if (!$LoadBusStmt->execute()) {
                die(json_encode(['error' =>"loadBus execute failed: " . $LoadBusStmt->error]));
            } else {
                $Result = $LoadBusStmt->get_result();

                if (!$Result) {
                    die(json_encode(['error' => 'Failed to get result from loadBusStmt execution']));
                }
                $PersonArr = $Result->fetch_assoc();
                die(json_encode($PersonArr));
            }
        }

        function saveBus($BusIdInt, $BusNumberStr, $BusCapacityInt) {
            $SaveBusStmt = $this->DBConnectObj->prepare("
                                INSERT INTO bus (Id, BusNumber, Capacity)
                                VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE
                                Id = VALUES(Id),
                                BusNumber = VALUES(BusNumber), 
                                Capacity = VALUES(Capacity)
                            ");
            $SaveBusStmt->bind_param("isi", $BusIdInt, $BusNumberStr, $BusCapacityInt);

            if (!$SaveBusStmt->execute()) {
                die(json_encode(["SaveBus execute failed: ".$SaveBusStmt->error]));
            }
            die(json_encode(["Bus saved with ID: ".$BusIdInt]));
        }

        function deleteBus($BusIdInt) {
            $DeleteBusStmt = $this->DBConnectObj->prepare("DELETE FROM bus WHERE Id = ?");
            $DeleteBusStmt->bind_param("i", $BusIdInt);

            if (!$DeleteBusStmt->execute()) {
                die(json_encode(["DeleteBus execute failed: " . $DeleteBusStmt->error]));
            } else {
                die(json_encode(["Bus deleted with ID: " . $BusIdInt]));
            }
        }

        function loadAllBuses() {
            $LoadAllBusesStmt = $this->DBConnectObj->prepare("SELECT * FROM bus");
            if (!$LoadAllBusesStmt->execute()) {
                die(json_encode(['error' => 'LoadAllBuses execute failed: ' . $LoadAllBusesStmt->error]));
            } else {
                $Result = $LoadAllBusesStmt->get_result();

                if (!$Result) {
                    die(json_encode(['error' => 'Failed to get result from LoadAllBusesStmt execution']));
                }
                $BusesArr = [];
                while ($BusArr = $Result->fetch_assoc()) {
                    $BusesArr[] = $BusArr;
                }
                die(json_encode($BusesArr));
            }
        }

        function deleteAllBuses() {
            $DeleteAllBusesStmt = $this->DBConnectObj->prepare("DELETE FROM bus");
            if (!$DeleteAllBusesStmt->execute()) {
                die(json_encode(["DeleteAllBuses execute failed: " . $DeleteAllBusesStmt->error]));
            } else {
                die(json_encode(["All buses deleted"]));
            }
        }
    }

    $BusObj = new Bus($DBConnectObj);

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
            case isset($inputData['deletePerson']) && isset($inputData['BusId']):
                $BusIdInt = intval($inputData['BusId']);

                try {
                    $BusObj->deleteBus($BusIdInt);
                    http_response_code(200);
                    die(json_encode(['success' => true, 'message' => 'Person deleted successfully']));
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to delete person']);
                }
                break;

//            case isset($_POST['deleteAllRecords']):
//                try {
//                    $UserObj->deleteAllPeople();
//                    http_response_code(200);
//                    die(json_encode(['success' => true, 'message' => 'All records deleted successfully']));
//                } catch (Exception $e) {
//                    http_response_code(500);
//                    echo json_encode(['error' => 'Failed to delete all records']);
//                }
//                break;

//            case isset($_POST['updatePerson']):
//                $UserObj->savePerson($_POST['PersonId'], $_POST['FirstName'], $_POST['Surname'], $_POST['DateOfBirth'], $_POST['Email'], $AgeInt);
//                break;

            case isset (
                    $inputData['busNumber']) && isset($inputData['busCapacity']
                ):
                try {
                    $BusObj->createBus($inputData['busNumber'], $inputData['busCapacity']);
                } catch (Exception $e) {
                    http_response_code(500);
                    die(json_encode('Failed to create bus'));
                }
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request']);
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['loadPerson'], $_GET['PersonId'])) {
            $UserIdInt = intval($_GET['PersonId']);
            $BusObj->loadBus($UserIdInt);
        } else {
            $BusObj->loadAllBuses();
        }
    } else {
        die(json_encode('Invalid request method or no loadAllBuses parameter found in the request'));
    }

    $DBConnectObj->close();