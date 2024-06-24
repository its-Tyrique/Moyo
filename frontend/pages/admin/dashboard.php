<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moyo Admin Dashboard</title>
    <link rel="icon" href="../../../frontend/assets/favicon.png" type="image/x-icon">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/admin_users.css" >

</head>

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
                        <a href="dashboard.php" class="flex-sm-fill text-sm-center nav-link active">Dashboard</a>
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
                        <a href="https://mymoyo.co.za/" class="nav-link">Logout</a>
                    </li>

                </ul>
            </div>
            <div class="col-md-11">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Todo: Remove Heading 1 -->
                        <h1>Dashboard</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Chart canvas -->
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <!-- Chart canvas for user trend -->
                        <canvas id="userTrendChart"></canvas>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Chart canvas for bus capacity -->
                        <canvas id="busCapacityChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <!-- Chart canvas for revenue -->
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../js/dashboard.js"></script><!-- Link to your JavaScript file -->
</body>

</html>
