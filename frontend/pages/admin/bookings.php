<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moyo Admin Dashboard</title>
    <link rel="icon" href="../../../frontend/assets/favicon.png" type="image/x-icon">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/admin_dashboard.css" rel="stylesheet">

</head>

<body>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-1">
            <ul class="nav flex-column">
                <!-- Todo: Add collapse functionality to the sidebar -->
                <li class="nav-item">
                    <a href="#" class="navbar-brand">Moyo Admin</a>
                </li>
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link">Users</a>
                </li>
                <li class="nav-item">
                    <a href="buses.php" class="nav-link">Buses</a>
                </li>
                <li class="nav-item">
                    <a href="trips.php" class="nav-link">Trips</a>
                </li>
                <li class="nav-item">
                    <a href="bookings.php" class="nav-link">Bookings</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
        <div class="col-md-11">
            <div class="row">
                <div class="col-md-12">
                    <!-- Todo: Remove Heading 1 -->
                    <!-- Todo: Implement Bus Types -->
                    <h1>Booking Management</h1>
                    <form role="form" id="tripForm" action="#" method="post">
                        <div class="form-row">
                            <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                <label for="busNumber">User:</label>
                                <select class="form-control" id="busNumber" name="title" required>
                                    <option>Select a Bus</option>
                                    <!-- Options populated dynamically -->
                                </select>
                                <div class="error-message text-danger" id="title-error"></div>
                            </div>
                            <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                <label for="departureLocation">Departure Location:</label>
                                <input type="text" class="form-control" id="departureLocation" name="departureLocation" placeholder="Departure Location" required>
                                <div class="error-message text-danger" id="departureLocation-error"></div>
                            </div>
                            <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                <label for="arrivalLocation">Arrival Location:</label>
                                <input type="text" class="form-control" id="arrivalLocation" name="arrivalLocation" placeholder="Arrival Location" required>
                                <div class="error-message text-danger" id="arrivalLocation-error"></div>
                            </div>
                            <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                <label for="departureTime">Departure Time:</label>
                                <input type="datetime-local" class="form-control" id="departureTime" name="departureTime" required>
                                <div class="error-message text-danger" id="departureTime-error"></div>
                            </div>
                            <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                <label for="arrivalTime">Arrival  Time:</label>
                                <input type="datetime-local" class="form-control" id="arrivalTime" name="arrivalTime" required>
                                <div class="error-message text-danger" id="arrivalTime-error"></div>
                            </div>
                            <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                <label for="price">Price:</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="price:" required>
                                <div class="error-message text-danger" id="price-error"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary col-md-3">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!-- Todo: Implement search functionality -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover table-responsive-xl mt-3">
                        <thead class="thead-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>User ID</th>
                            <th>Trip ID</th>
                            <th>Booking Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody class="text-center" id="bookingsTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="../../js/bookings_crud.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
