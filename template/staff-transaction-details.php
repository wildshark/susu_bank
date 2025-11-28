<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Account Statement</title>

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

    .profile-pic {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: .25rem;
        border: 3px solid var(--bg-200);
    }

    .detail-label {
        font-weight: 500;
        color: var(--text-200);
    }

    .kpi-card {
        background-color: var(--bg-200);
        border: none;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
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
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Account Statement for <?=$profile['firstName']." ".$profile['midName']." ".$profile['lastName']?></h1>
                <p class="text-muted">Account Number: AC-<?=$profile['accountNumber']?></p>
            </div>

            <!-- Actions Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?_main=contribution" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Contribution Ledger
                </a>
                <div>
                    <a href="?_main=print-out&id=<?=$_GET['id']?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print me-1"></i> Print
                        Statement</a>
                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-file-csv me-1"></i> Export
                        CSV</button>
                </div>
            </div>

            <!-- SESSION 1: Member Profile -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="fas fa-user-circle me-2"></i>Member Details</h5>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <img src="https://via.placeholder.com/150" class="profile-pic" alt="Passport Photo">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3"><span class="detail-label">Full Name:
                                    </span><b><?=$profile['firstName']." ".$profile['midName']." ".$profile['lastName']?></b>
                                </div>
                                <div class="col-md-6 mb-3"><span class="detail-label">Gender:
                                    </span><b><?=$profile['gender']?></b></div>
                                <div class="col-md-6 mb-3"><span class="detail-label">Nationality:
                                    </span><b><?=$profile['nationality']?></b></div>
                                <div class="col-md-6 mb-3"><span class="detail-label">Marital Status:
                                    </span><b><?=$profile['maritalStatus']?></b></div>
                                <div class="col-md-6 mb-3"><span class="detail-label">Mobile Number:
                                    </span><b><?=$profile['mobile']?></b></div>
                                <div class="col-md-6 mb-3"><span class="detail-label">Email:
                                    </span><b><?=$profile['email']?></b></div>
                                <div class="col-md-6 mb-3"><span class="detail-label">Postal Address:
                                    </span><b><?=$profile['postalAddress']?></b></div>
                                <div class="col-md-6 mb-3"><span class="detail-label">Digital Address:
                                    </span><b><?=$profile['digitalAddress']?></b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SESSION 2: Financial Summary Cards -->
            <div class="row">
                <div class="col-md-4">
                    <div class="kpi-card text-center">
                        <h5 class="card-title">Total Contributions</h5>
                        <p class="h3 text-success fw-bold">$<?=number_format($summary['dr'], 2)?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card text-center">
                        <h5 class="card-title">Total Payouts</h5>
                        <p class="h3 text-danger fw-bold">$<?=number_format($summary['cr'], 2)?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card text-center">
                        <h5 class="card-title">Number of Client</h5>
                        <p class="h3 text-primary fw-bold"><?=number_format($summary['client'],0)?></p>
                    </div>
                </div>
            </div>

            <!-- SESSION 3: Detailed Transaction History -->
            <div class="card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="transactions-tab" data-bs-toggle="tab"
                                data-bs-target="#transactions" type="button" role="tab" aria-controls="transactions"
                                aria-selected="true">
                                <i class="fas fa-history me-2"></i>Transaction History
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="client-tab" data-bs-toggle="tab" data-bs-target="#client"
                                type="button" role="tab" aria-controls="client" aria-selected="false">
                                <i class="fas fa-user-friends me-2"></i>Client Details
                            </button>
                        </li>
                    </ul>
                </div>
               
            </div>

            <div class="card mb-4">
                <h5 class="card-header"><i class="fas fa-history me-2"></i>Transaction History</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="statementTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Ref</th>
                                    <th>Details</th>
                                    <th>Type</th>
                                    <th>Contribution</th>
                                    <th>Payout</th>
                                    <th>Running Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(!$transactions){
                                        echo'';
                                    }else{
                                        foreach($transactions as $transaction){

                                            if($transaction['contribution'] == 0){
                                                $transaction['contribution'] = '-';
                                            }

                                            if($transaction['payout'] == 0){
                                                $transaction['payout'] = '-';
                                            }

                                            $css_contribution = isset($transaction['contribution']) ? 'text-success' : 'text-danger';
                                            $css_payout = isset($transaction['payout']) ? 'text-success' : 'text-danger';


                                            echo " <tr>
                                                <td>".$transaction['tranDate']."</td>
                                                <td>".$transaction['ref']."</td>
                                                <td>".$transaction['details']."</td>
                                                <td><span class='badge bg-success'>".$transaction['tranMethod']."</span></td>
                                                <td class='".$css_contribution."'>".$transaction['contribution']."</td>
                                                <td class='".$css_payout."'>".$transaction['payout']."</td>
                                                <td>".$transaction['balance']."</td>
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
        $('#statementTable').DataTable({
            "order": [
                [0, "desc"]
            ] // Order by the first column (Date) in descending order by default
        });
    });
    </script>

</body>

</html>