<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Payout Details</title>

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
                <h1 class="h2">Payout Details: <?=$month?> <?=$year?></h1>
                <p class="text-muted">A detailed list of all payout transactions for the selected month.</p>
            </div>

            <!-- Actions Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?_main=payout" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Summary
                </a>
                <div>
                    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-print me-1"></i> Print</button>
                    <a href="?_main=payout-detail&id=<?=$id?>&year=<?=$year?>&month=<?=$month?>&export=true" class="btn btn-sm btn-outline-success"><i class="fas fa-file-csv me-1"></i> Export
                        CSV</a>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Transaction List</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="payoutDetailsTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Account Number</th>
                                            <th>Name</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(!$payoutDetails){
                                                echo'';
                                            }else{
                                                foreach($payoutDetails as $row){

                                                    $tranDate = $row['tranDate'];
                                                    $sysDate = $row['cdate'];
                                                    $accountNumber = $row['accountNumber'];
                                                    $name = $row['firstName']." ".$row['lastName']." ".$row['midName'];
                                                    $tranMethod = $row['tranMethod'];
                                                    $amt = number_format($row['payout'],2);

                                                    echo "
                                                    <tr>
                                                        <td>{$tranDate}</td>
                                                        <td>".date('h:i A', strtotime($sysDate))."</td>
                                                        <td>{$accountNumber}</td>
                                                        <td>{$name}</td>
                                                        <td>{$tranMethod}</td>
                                                        <td class='text-danger fw-bold'>-{$amt}</td>
                                                    </tr>
                                                    ";

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
        $('#payoutDetailsTable').DataTable();
    });
    </script>

</body>

</html>