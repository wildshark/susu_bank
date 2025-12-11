<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Staffs Management</title>

    <!-- Bootstrap 5.0 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS -->
    <style>
    :root {
        --primary-100: #d4eaf7;
        --primary-200: #b6ccd8;
        --primary-300: #3b3c3d;
        --accent-100: #71c4ef;
        --accent-200: #00668c;
        --text-100: #1d1c1c;
        --text-200: #313d44;
        --bg-100: #fffefb;
        --bg-200: #f5f4f1;
        --bg-300: #cccbc8;
    }

    body {
        background-color: var(--bg-100);
        color: var(--text-100);
        font-family: 'Roboto', sans-serif;
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--bg-100);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease-in-out;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid var(--primary-200);
        border-top: 5px solid var(--accent-100);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .main-container {
        display: flex;
    }

    .sidebar {
        width: 250px;
        background-color: var(--bg-200);
        min-height: 100vh;
        padding: 20px;
    }

    .sidebar .nav-link {
        color: var(--text-200);
        margin-bottom: 10px;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        color: var(--bg-100);
        background-color: var(--accent-200);
        border-radius: 5px;
    }

    .sidebar .nav-link .fas {
        color: var(--primary-300);
    }

    .sidebar .nav-link.active .fas,
    .sidebar .nav-link:hover .fas {
        color: var(--bg-100);
    }

    .content {
        flex-grow: 1;
        padding: 30px;
    }

    .card {
        background-color: var(--bg-100);
        border: 1px solid var(--bg-300);
    }

    .modal-content {
        background-color: var(--bg-100);
    }

    .form-section-title {
        font-weight: 500;
        color: var(--accent-200);
        margin-top: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--bg-300);
    }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3">
            <?php require_once($template['menu'])?>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Header -->
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Staffs Management</h1>
            </div>

            <!-- Main Content Area -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Staffs</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addMemberModal">
                                <i class="fas fa-plus-circle me-2"></i>Add New Staff
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="membersTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Joined Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if($staffs && count($staffs) > 0){
                                                foreach($staffs as $r){
                                                    // Example: adjust field names to match your $r array/object
                                                    $statusClass = ($r['status'] == 'active') ? 'bg-success' : 'bg-danger';
                                                    $name = $r['fName']." ".$r['lName']." ".$r['oName'];
                                                    $date = date('d-m-Y', strtotime($r['dob']));
                                                    $clientID = $r['staffId'];

                                                    echo "<tr>
                                                        <td>{$name}</td>
                                                        <td>{$r['email']}</td>
                                                        <td>{$r['mobile']}</td>
                                                        <td>{$date}</td>
                                                        <td><span class=\"badge {$statusClass}\">{$r['status']}</span></td>
                                                        <td>
                                                            <a href='?_main=staff-transaction-detail&id=$clientID' class=\"btn btn-sm btn-info\"><i class=\"fas fa-eye\"></i></a>
                                                            <a href='?_main=staff-detail&id=$clientID' class=\"btn btn-sm btn-warning\"><i class=\"fas fa-edit\"></i></a>
                                                            <a href='?_submit=del-staff&id=$clientID' class=\"btn btn-sm btn-danger\"><i class=\"fas fa-trash\"></i></button>
                                                        </td>
                                                    </tr>";
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">Add New Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- The form must have enctype="multipart/form-data" for file uploads -->
                    <form action="index.php" method="post" enctype="multipart/form-data">

                        <h6 class="form-section-title">Personal Details</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="firstName" class="form-label">First
                                    Name</label><input type="text" class="form-control" id="firstName" name="first_name"
                                    required></div>
                            <div class="col-md-4 mb-3"><label for="lastName" class="form-label">Last Name</label><input
                                    type="text" class="form-control" id="lastName" name="last_name" required></div>
                            <div class="col-md-4 mb-3"><label for="middleName" class="form-label">Other
                                    Name</label><input type="text" class="form-control" id="middleName"
                                    name="middle_name"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Gender</label>
                                <div>
                                    <div class="form-check form-check-inline"><input class="form-check-input"
                                            type="radio" name="gender" id="male" value="male"><label
                                            class="form-check-label" for="male">Male</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input"
                                            type="radio" name="gender" id="female" value="female"><label
                                            class="form-check-label" for="female">Female</label></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"><label for="dob" class="form-label">Date of Birth</label><input
                                    type="date" class="form-control" id="dob" name="dob" required></div>
                            <div class="col-md-6 mb-3"><label for="maritalStatus" class="form-label">Marital
                                    Status</label><select class="form-select" id="maritalStatus" name="marital_status">
                                    <option selected disabled>Choose...</option>
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Divorced</option>
                                    <option>Widowed</option>
                                </select></div>
                            <div class="col-md-6 mb-3"><label for="nationality"
                                    class="form-label">Nationality</label><input type="text" class="form-control"
                                    id="nationality" name="nationality" required></div>
                        </div>
                        <h6 class="form-section-title">Contact Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="mobile" class="form-label">Mobile</label><input
                                    type="tel" class="form-control" id="mobile" name="mobile" required></div>
                            <div class="col-md-6 mb-3"><label for="email" class="form-label">Email</label><input
                                    type="email" class="form-control" id="email" name="email" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="postalAddress" class="form-label">Postal
                                    Address</label><input type="text" class="form-control" id="postalAddress"
                                    name="postal_address"></div>
                            <div class="col-md-6 mb-3"><label for="digitalAddress" class="form-label">Digital
                                    Address</label><input type="text" class="form-control" id="digitalAddress"
                                    name="digital_address"></div>
                        </div>
                        <div class="mb-3"><label for="homeAddress" class="form-label">Home Address</label><textarea
                                class="form-control" id="homeAddress" name="home_address" rows="3"></textarea></div>

                        <h6 class="form-section-title">Login Credentials</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="username" class="form-label">Username</label><input
                                    type="text" class="form-control" id="username" name="username" required></div>
                            <div class="col-md-6 mb-3"><label for="password"
                                    class="form-label">Password</label><input type="text" class="form-control"
                                    id="password" name="password" required></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="_submit" value="add-staff" class="btn btn-primary">Save
                                Staff</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.0 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Custom JS -->
    <script>
    // Preloader function
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        preloader.style.opacity = '0';
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 500);
    });

    // Initialize DataTables
    $(document).ready(function() {
        $('#membersTable').DataTable();
    });
    </script>

</body>

</html>