<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Profile</title>

    <!-- Bootstrap 5.0 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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

    .input-group-text {
        background-color: var(--bg-200);
    }

    .password-toggle {
        cursor: pointer;
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
                <h1 class="h2">Administrator Profile</h1>
            </div>

            <!-- SESSION 1: Profile Information -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="fas fa-user-edit me-2"></i>Profile Information</h5>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="refId" class="form-label">Reference ID</label>
                                <input type="text" class="form-control" id="refId" name="ref_id" value="<?php echo $ref_id; ?>"
                                    readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="businessName" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="businessName" name="business_name"
                                    value="<?php echo $businessName; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="firstName" class="form-label">First
                                    Name</label><input type="text" class="form-control" id="firstName" name="first_name"
                                    value="<?php echo $firstName; ?>"></div>
                            <div class="col-md-4 mb-3"><label for="middleName" class="form-label">Middle
                                    Name</label><input type="text" class="form-control" id="middleName"
                                    name="middle_name" value="<?php echo $middleName; ?>"></div>
                            <div class="col-md-4 mb-3"><label for="lastName" class="form-label">Last Name</label><input
                                    type="text" class="form-control" id="lastName" name="last_name" value="<?php echo $lastName; ?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group"><span class="input-group-text"><i
                                            class="fas fa-envelope"></i></span><input type="email" class="form-control"
                                        id="email" name="email" value="<?php echo $email; ?>"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <div class="input-group"><span class="input-group-text"><i
                                            class="fas fa-phone"></i></span><input type="tel" class="form-control"
                                        id="mobile" name="mobile" value="<?php echo $mobile; ?>"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="postalAddress" class="form-label">Postal
                                    Address</label><input type="text" class="form-control" id="postalAddress"
                                    name="postal_address" value="<?php echo $postalAddress; ?>"></div>
                            <div class="col-md-6 mb-3"><label for="digitalAddress" class="form-label">Digital
                                    Address</label><input type="text" class="form-control" id="digitalAddress"
                                    name="digital_address" value="<?php echo $digitalAddress; ?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="region" class="form-label">Region</label><input
                                    type="text" class="form-control" id="region" name="region"
                                    value="<?php echo $region; ?>"></div>
                            <div class="col-md-6 mb-3"><label for="district" class="form-label">District</label><input
                                    type="text" class="form-control" id="district" name="district"
                                    value="<?php echo $district; ?>"></div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Profile
                            Changes</button>
                    </form>
                </div>
            </div>

            <!-- SESSION 2: Security Settings -->
            <div class="card">
                <h5 class="card-header"><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" value="<?=$username?>"
                                    readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="currentPassword" name="current_password"
                                    required>
                                <span class="input-group-text password-toggle"
                                    onclick="togglePassword('currentPassword')"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="new_password"
                                    required>
                                <span class="input-group-text password-toggle"
                                    onclick="togglePassword('newPassword')"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                                    required>
                                <span class="input-group-text password-toggle"
                                    onclick="togglePassword('confirmPassword')"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-key me-2"></i>Change
                            Password</button>
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

    // Password visibility toggle function
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        // Toggle the type attribute
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        // Toggle the icon
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
    </script>

</body>

</html>