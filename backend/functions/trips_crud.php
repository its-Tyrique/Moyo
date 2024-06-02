<?php
    /** @var mysqli $DBConnectObj */
    require_once "../includes/db_connection.php";

    class Trip {
        public $BusNumberStr, $DepartureLocationStr, $ArrivalLocationStr, $DepartureTime, $ArrivalTime, $PriceFlt;
        private $DBConnectObj;

        public function __construct($DBConnectObj) {
            $this->DBConnectObj = $DBConnectObj;
        }

        // Methods
        function createTrip($BusNumberInt, $DepartureLocationStr, $ArrivalLocationStr, $DepartureTime, $ArrivalTime, $PriceFlt) {
            $createTripStmt = $this->DBConnectObj->prepare("INSERT INTO trip (Bus, DepartureLocation, ArrivalLocation, 
                                                            DepartureTime, ArrivalTime, Price) 
                                                            VALUES (?, ?, ?, ?, ?, ?)");
            $createTripStmt->bind_param("issssd", $BusNumberInt, $DepartureLocationStr, $ArrivalLocationStr, $DepartureTime, $ArrivalTime, $PriceFlt);

            if (!$createTripStmt->execute()) {
                die(json_encode(array('error' => 'CreateTrip execute failed: ' . $createTripStmt->error)));
            }
            die(json_encode(['success' => true, 'Trip created with ID: '.$createTripStmt->insert_id]));
        }

        function loadTrip($TripIdInt) {
            $LoadTripStmt = $this->DBConnectObj->prepare("SELECT * FROM trip WHERE Id = ?");
            $LoadTripStmt->bind_param("i", $TripIdInt);

            if (!$LoadTripStmt->execute()) {
                die(json_encode(['error' =>"loadTrip execute failed: " . $LoadTripStmt->error]));
            } else {
                $Result = $LoadTripStmt->get_result();

                if (!$Result) {
                    die(json_encode(['error' => 'Failed to get result from LoadTripStmt execution']));
                }
                $TripArr = $Result->fetch_assoc();
                die(json_encode($TripArr));
            }
        }

        function saveTrip($TripIdInt, $BusNumberStr, $DepartureLocationStr, $ArrivalLocationStr, $DepartureTime, $ArrivalTime, $PriceFlt) {
            $SaveBusStmt = $this->DBConnectObj->prepare("
                                    INSERT INTO trip (Id, Bus, DepartureLocation, ArrivalLocation, DepartureTime, ArrivalTime, Price)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE
                                    Id = VALUES(Id),
                                    Bus = VALUES(Bus), 
                                    DepartureLocation = VALUES(DepartureLocation),
                                    ArrivalLocation = VALUES(ArrivalLocation),
                                    DepartureTime = VALUES(DepartureTime),
                                    ArrivalTime = VALUES(ArrivalTime),
                                    Price = VALUES(Price)
                                    
                                ");
            $SaveBusStmt->bind_param("issssssf", $TripIdInt, $BusNumberStr, $DepartureLocationStr, $ArrivalLocationStr, $DepartureTime, $ArrivalTime, $PriceFlt);

            if (!$SaveBusStmt->execute()) {
                die(json_encode(["saveTrip execute failed: ".$SaveBusStmt->error]));
            }
            die(json_encode(["Trip saved with ID: ".$TripIdInt]));
        }

        function deleteTrip($TripIdInt) {
            $DeleteBusStmt = $this->DBConnectObj->prepare("DELETE FROM trip WHERE Id = ?");
            $DeleteBusStmt->bind_param("i", $TripIdInt);

            if (!$DeleteBusStmt->execute()) {
                die(json_encode(["DeleteTrip execute failed: " . $DeleteBusStmt->error]));
            } else {
                die(json_encode(["Trip deleted with ID: " . $TripIdInt]));
            }
        }

        function loadAllTrips() {
            $LoadAllTripStmt = $this->DBConnectObj->prepare("SELECT * FROM trip");
            if (!$LoadAllTripStmt->execute()) {
                die(json_encode(['error' => 'LoadAllBuses execute failed: ' . $LoadAllTripStmt->error]));
            } else {
                $Result = $LoadAllTripStmt->get_result();

                if (!$Result) {
                    die(json_encode(['error' => 'Failed to get result from LoadAllTripsStmt execution']));
                }
                $TripsArr = [];
                while ($TripArr = $Result->fetch_assoc()) {
                    $TripsArr[] = $TripArr;
                }
                die(json_encode($TripsArr));
            }
        }

        function deleteAllTrips() {
            $DeleteAllTripsStmt = $this->DBConnectObj->prepare("DELETE FROM trip");
            if (!$DeleteAllTripsStmt->execute()) {
                die(json_encode(["DeleteAllTrips execute failed: " . $DeleteAllTripsStmt->error]));
            } else {
                die(json_encode(["All trips deleted"]));
            }
        }
    }

    $TripObj = new Trip($DBConnectObj);

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
            case isset($inputData['deletePerson']) && isset($inputData['TripId']):
                $TripIdInt = intval($inputData['TripId']);

                try {
                    $TripObj->deleteTrip($TripIdInt);
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

            case isset(
                    $inputData['busNumber']) && isset($inputData['departureLocation']) && isset($inputData['arrivalLocation'])
                    && isset($inputData['departureTime']) && isset($inputData['arrivalTime']) && isset($inputData['price']
                ):
                try {
                    $TripObj->createTrip($inputData['busNumber'], $inputData['departureLocation'], $inputData['arrivalLocation'],
                        $inputData['departureTime'], $inputData['arrivalTime'], $inputData['price']);
                } catch (Exception $e) {
                    http_response_code(500);
                    die(json_encode('Failed to create trip'));
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
            $TripObj->loadTrip($UserIdInt);
        } else {
            $TripObj->loadAllTrips();
        }
    } else {
        die(json_encode('Invalid request method or no loadAllTrips parameter found in the request'));
    }

    $DBConnectObj->close();