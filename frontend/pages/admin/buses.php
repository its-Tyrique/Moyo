<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <link rel="icon" href="../../../frontend/assets/favicon.png" type="image/x-icon">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/admin_users.css" >

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
                        <h1>Bus Management</h1>
                        <form role="form" id="busForm" action="#" method="post">
                            <div class="form-row">
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="busNumber">Bus Number:</label>
                                    <input type="text" class="form-control" id="busNumber" name="busNumber" placeholder="Bus Number" required>
                                    <div class="error-message text-danger" id="busNumber-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="busCapacity">Capacity:</label>
                                    <input type="number" class="form-control" id="busCapacity" name="busCapacity" placeholder="Capacity" required>
                                    <div class="error-message text-danger" id="busCapacity-error"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary col-md-3" id="createBusBtn">Create</button>
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
                                <th>Bus Number</th>
                                <th>Capacity</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody class="text-center" id="busesTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="../../js/buses_crud.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
