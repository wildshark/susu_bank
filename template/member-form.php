<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Update Member</title>

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

    .form-section-title {
        font-weight: 500;
        color: var(--accent-200);
        margin-top: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--bg-300);
    }

    .current-file {
        font-size: 0.9em;
        color: var(--text-200);
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
            <?php include_once($template['menu'])?>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Header -->
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Update Member Profile: <?=$clientName?></h1>
                <p class="text-muted">Account Number: AC-<?=$accountNumber?></p>
            </div>
            <!-- Actions Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?_main=members" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Member List
                </a>
            </div>

            <!-- Update Form -->
            <div class="card">
                <h5 class="card-header"><i class="fas fa-user-edit me-2"></i>Edit Member Details</h5>
                <div class="card-body">
                    <!-- The form must have enctype="multipart/form-data" for file uploads -->
                    <form action="index.php" method="post" enctype="multipart/form-data">

                        <h6 class="form-section-title">Personal Details</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="firstName" class="form-label">First
                                    Name</label><input type="text" class="form-control" id="firstName" name="first_name"
                                    value="<?=$firstName?>" required></div>

                            <div class="col-md-4 mb-3"><label for="lastName" class="form-label">Last Name</label><input
                                    type="text" class="form-control" id="lastName" name="last_name"
                                    value="<?=$lastName?>" required></div>

                            <div class="col-md-4 mb-3"><label for="middleName" class="form-label">Other
                                    Name</label><input type="text" class="form-control" id="middleName"
                                    name="middle_name" value="<?=$middleName?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label class="form-label">Gender</label>
                                <div>
                                    <div class="form-check form-check-inline"><input class="form-check-input"
                                            type="radio" name="gender" id="male" value="male"
                                            <?=($gender == 'male') ? 'checked' : ''?>><label class="form-check-label"
                                            for="male">Male</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input"
                                            type="radio" name="gender" id="female" value="female"
                                            <?=($gender == 'female') ? 'checked' : ''?>><label class="form-check-label"
                                            for="female">Female</label></div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3"><label for="dob" class="form-label">Date of Birth</label><input
                                    type="date" class="form-control" id="dob" name="dob" value="<?=$dob?>" required>
                            </div>
                            <div class="col-md-4 mb-3"><label for="maritalStatus" class="form-label">Marital
                                    Status</label><select class="form-select" id="maritalStatus" name="marital_status">
                                    <option value="Single" <?=($maritalStatus == 'Single') ? 'selected' : ''?>>Single
                                    </option>
                                    <option value="Married" <?=($maritalStatus == 'Married') ? 'selected' : ''?>>Married
                                    </option>
                                    <option value="Divorced" <?=($maritalStatus == 'Divorced') ? 'selected' : ''?>>
                                        Divorced</option>
                                    <option value="Widowed" <?=($maritalStatus == 'Widowed') ? 'selected' : ''?>>Widowed
                                    </option>
                                </select></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="nationality"
                                    class="form-label">Nationality</label><input type="text" class="form-control"
                                    id="nationality" name="nationality" value="<?=$nationality?>" required></div>
                            <div class="col-md-6 mb-3"><label for="occupation"
                                    class="form-label">Occupation</label><input type="text" class="form-control"
                                    id="occupation" name="occupation" value="<?=$occupation?>"></div>
                        </div>

                        <h6 class="form-section-title">Contact Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="mobile" class="form-label">Mobile</label><input
                                    type="tel" class="form-control" id="mobile" name="mobile" value="<?=$mobile?>"
                                    required></div>
                            <div class="col-md-6 mb-3"><label for="email" class="form-label">Email</label><input
                                    type="email" class="form-control" id="email" name="email" value="<?=$email?>"
                                    required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label for="postalAddress" class="form-label">Postal
                                    Address</label><input type="text" class="form-control" id="postalAddress"
                                    name="postal_address" value="<?=$postalAddress?>"></div>
                            <div class="col-md-6 mb-3"><label for="digitalAddress" class="form-label">Digital
                                    Address</label><input type="text" class="form-control" id="digitalAddress"
                                    name="digital_address" value="<?=$digitalAddress?>"></div>
                        </div>
                        <div class="mb-3"><label for="homeAddress" class="form-label">Home Address</label><textarea
                                class="form-control" id="homeAddress" name="home_address"
                                rows="3"><?=$contactAddress?></textarea></div>

                        <h6 class="form-section-title">Next of Kin Details</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="nokName" class="form-label">Full Name</label><input
                                    type="text" class="form-control" id="nokName" name="nok_name" value="<?=$nkName?>"
                                    required></div>
                            <div class="col-md-4 mb-3"><label for="nokRelationship"
                                    class="form-label">Relationship</label><input type="text" class="form-control"
                                    id="nokRelationship" name="nok_relationship" value="<?=$nkRelationship?>" required>
                            </div>
                            <div class="col-md-4 mb-3"><label for="nokMobile" class="form-label">Mobile
                                    Number</label><input type="tel" class="form-control" id="nokMobile"
                                    name="nok_mobile" value="<?=$nkMobile?>" required></div>
                        </div>

                        <h6 class="form-section-title">Document Upload</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="passportUpload" class="form-label">Upload New Passport Photo</label>
                                <input class="form-control" type="file" id="passportUpload" name="passport_upload">
                                <div class="current-file mt-1"><i class="fas fa-file-image me-1"></i>Current:
                                    <strong>passport_photo.jpg</strong>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="idUpload" class="form-label">Upload New Government Issued ID</label>
                                <input class="form-control" type="file" id="idUpload" name="id_upload">
                                <div class="current-file mt-1"><i class="fas fa-id-card me-1"></i>Current:
                                    <strong>ghana_card.pdf</strong>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <button type="submit" name="_submit" value="update-member" class="btn btn-primary"><i
                                    class="fas fa-save me-2"></i>Save
                                Changes</button>
                            <button type="button" class="btn btn-danger"><i class="fas fa-trash me-2"></i>Delete
                                Member</button>
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
    </script>
</body>

</html>