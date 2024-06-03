<?php
    session_start();

    /** @var mysqli $DBConnectObj */
    require_once "../includes/db_connection.php";

    class Booking {
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

        function loadAllBookings() {
            $LoadAllBookingsStmt = $this->DBConnectObj->prepare("SELECT * FROM bookings");
            if (!$LoadAllBookingsStmt->execute()) {
                die(json_encode(['error' => 'LoadAllBooings execute failed: ' . $LoadAllBookingsStmt->error]));
            } else {
                $Result = $LoadAllBookingsStmt->get_result();

                if (!$Result) {
                    die(json_encode(['error' => 'Failed to get result from LoadAllBookingsStmt execution']));
                }
                $BookingsArr = [];
                while ($BookingArr = $Result->fetch_assoc()) {
                    $BookingsArr[] = $BookingArr;
                }
                die(json_encode($BookingsArr));
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

    $BookingObj = new Booking($DBConnectObj);

    /*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        switch (true) {
            case isset($_POST['deletePerson']) && isset($_POST['PersonId']):
                $UserIdInt = intval($_POST['PersonId']);

                try {
                    $UserObj->deletePerson($UserIdInt);
                    http_response_code(200);
                    die(json_encode(['success' => true, 'message' => 'Person deleted successfully']));
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to delete person']);
                }
                break;

            case isset($_POST['deleteAllRecords']):
                try {
                    $UserObj->deleteAllPeople();
                    http_response_code(200);
                    die(json_encode(['success' => true, 'message' => 'All records deleted successfully']));
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to delete all records']);
                }
                break;

            case isset($_POST['updatePerson']):
                $UserObj->savePerson($_POST['PersonId'], $_POST['FirstName'], $_POST['Surname'], $_POST['DateOfBirth'], $_POST['Email'], $AgeInt);
                break;

            case isset($_POST['FirstName']) && isset($_POST['Surname']) && isset($_POST['DateOfBirth']) && isset($_POST['Email']):
                $AgeInt = date_diff(date_create($_POST['DateOfBirth']), date_create('now'))->y;
                $UserObj->createPerson($_POST['FirstName'], $_POST['Surname'], $_POST['DateOfBirth'], $_POST['Email'], $AgeInt);
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request']);
                break;
        }
    }*/

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['loadPerson'], $_GET['PersonId'])) {
            $BookingIdInt = intval($_GET['PersonId']);
            $BookingObj->loadBus($BookingIdInt);
        } else {
            $BookingObj->loadAllBookings();
        }
    } else {
        die(json_encode(['error' => 'Invalid request method or no loadAllBuses parameter found in the request']));
    }

    $DBConnectObj->close();