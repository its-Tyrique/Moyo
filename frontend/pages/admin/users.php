<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="icon" href="../../../frontend/assets/favicon.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/admin_users.css" >
    <script src="../../js/users_crud.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-1">
                <ul class="nav flex-column nav-pills">
                    <!-- Todo: Add collapse functionality to the sidebar -->
                    <li class="nav-item">
                        <a href="#" class="navbar-brand">Moyo Admin</a>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="flex-sm-fill text-sm-center nav-link active">Users</a>
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
                        <h1>Users</h1>
                        <form role="form" id="userForm" action="#" method="post">
                            <div class="form-row">
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="firstName">First Name:</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                                    <div class="error-message text-danger" id="firstName-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="lastName">Last Name:</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                                    <div class="error-message text-danger" id="lastName-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="idNumber">ID Number:</label>
                                    <input type="text" class="form-control" id="idNumber" name="idNumber" placeholder="ID Number" required>
                                    <input type="hidden" id="gender" name="gender" value="">
                                    <input type="hidden" id="dateOfBirth" name="dateOfBirth" value="">
                                    <div class="error-message text-danger" id="idNumber-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="phone">Cellphone:</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Cellphone Number" required>
                                    <div class="error-message text-danger" id="phone-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="email">Email Address:</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                    <div class="error-message text-danger" id="email-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <div class="error-message text-danger" id="password-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="confirmPassword">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                                    <div class="error-message text-danger" id="confirmPassword-error"></div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-xl-3">
                                    <label for="role">Role:</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="1">User</option>
                                        <option value="2">Administrator</option>
                                    </select>
                                    <div class="error-message text-danger" id="role-error"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary col-md-3" id="createUserBtn">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped table-hover table-responsive-xl mt-3">
                            <thead class="thead-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email Address</th>
                                <th>Cell</th>
                                <th>Blocked</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody class="text-center" id="usersTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
