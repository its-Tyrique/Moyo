<?php
    session_start();

    /** @var mysqli $DBConnectObj */
    require_once "../includes/db_connection.php";

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Ensure the user is logged in
        if (!isset($_SESSION['user'])) {
            die(json_encode(array("success" => false, "message" => "User not logged in")));
        }

        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!is_array($inputData)) {
            file_put_contents("post_data.txt", file_get_contents("php://input"));
            die(json_encode(["error" => "Invalid input"]));
        }

        $trips = $inputData['trips'];
        $userId = $_SESSION['user']['Id'];

        try {
            $DBConnectObj->begin_transaction();

            $createBookingQuery = "INSERT INTO bookings (Account, Trip) VALUES (?, ?)";
            $CreateBookingStmt = $DBConnectObj->prepare($createBookingQuery);

            if (!$CreateBookingStmt) {
                throw new Exception("Prepare statement failed: " . $DBConnectObj->error);
            }

            foreach ($trips as $trip) {
                // Check if Trip ID exists in the trip table
                $tripCheckQuery = "SELECT Id FROM trip WHERE Id = ?";
                $tripCheckStmt = $DBConnectObj->prepare($tripCheckQuery);
                $tripCheckStmt->bind_param('i', $trip['tripId']);
                $tripCheckStmt->execute();
                $tripCheckStmt->store_result();

                if ($tripCheckStmt->num_rows === 0) {
                    throw new Exception("Trip ID " . $trip['tripId'] . " does not exist.");
                }

                $tripCheckStmt->close();

                for ($i = 0; $i < $trip['ticketCount']; $i++) {
                    $CreateBookingStmt->bind_param('ii', $userId, $trip['tripId']);
                    if (!$CreateBookingStmt->execute()) {
                        throw new Exception("Execute failed: " . $CreateBookingStmt->error);
                    }
                }
            }

            $DBConnectObj->commit();
            $CreateBookingStmt->close();
            $DBConnectObj->close();
            die(json_encode(['success' => true]));

        } catch (Exception $e) {
            $DBConnectObj->rollback();
            die(json_encode(['success' => false, 'message' => $e->getMessage()]));
        }
    }
