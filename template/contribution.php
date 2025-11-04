<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Contributions</title>

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
                <h1 class="h2">Contribution Ledger</h1>
            </div>

            <!-- Main Content Area -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>All Transactions</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addSlipModal">
                                <i class="fas fa-plus-circle me-2"></i>New Transaction Slip
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="contributionsTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Account Number</th>
                                            <th>Name</th>
                                            <th>Contribution</th>
                                            <th>Payout</th>
                                            <th>Available Balance</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(!$ledgers){
                                                echo '';
                                            }else{
                                                foreach($ledgers as $row){
                                                    if($row['dr'] == 0){
                                                        $row['dr'] = '-';
                                                        $css ='';
                                                    }else{
                                                        $css = 'text-success fw-bold';
                                                    }

                                                    if($row['cr'] == 0){
                                                        $row['cr'] = '-';
                                                        $css ='';
                                                    }else{
                                                        $css = 'text-danger fw-bold';
                                                    }
                                                    $bal = isset($row['bal']) ? $row['bal'] : 0;
                                                    if($bal < 0){
                                                        $css = 'text-danger fw-bold';
                                                    }else{
                                                        $css = 'text-success fw-bold';
                                                    }

                                                    if($bal == 0){
                                                        $bal = '-';
                                                        $css = '';
                                                    }

                                                    $name = $row['firstName'] . ' ' . $row['midName'] . ' ' . $row['lastName'];
                                                    echo "<tr>
                                                        <td>" . htmlspecialchars($row['accountNumber']) . "</td>
                                                        <td>" . htmlspecialchars($name) . "</td>
                                                        <td class='$css'>" . htmlspecialchars($row['dr']) . "</td>
                                                        <td class='$css'>" . htmlspecialchars($row['cr']) . "</td>
                                                        <td class='$css'>" . htmlspecialchars($bal) . "</td>
                                                        <td><a href='?_main=contribution-detail&id=" . $row['clientId'] . "' class='btn btn-sm btn-info'>
                                                        <i class='fas fa-eye'></i></a></td>
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

    <!-- Add Transaction Slip Modal -->
    <div class="modal fade" id="addSlipModal" tabindex="-1" aria-labelledby="addSlipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSlipModalLabel">New Transaction Slip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post" enctype="application/x-www-form-urlencoded">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="slipDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="slipDate" name="slip_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="slipRef" class="form-label">Reference / Slip No.</label>
                                <input type="text" class="form-control" id="slipRef" name="slip_ref">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="accountNumber" class="form-label">Account Number</label>
                            <input type="text" list="accountNum" class="form-control" id="accountNumber"
                                name="account_number" placeholder="Enter member's account number" required>
                            <datalist id="accountNum">
                                <?=getListAccountNumber($listAccountNumbers);?>
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <label for="accountNumber" class="form-label">Transaction Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Enter transaction description" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Transaction Type</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="transaction_type"
                                            id="typeContribution" value="contribution" checked>
                                        <label class="form-check-label" for="typeContribution">Contribution</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="transaction_type"
                                            id="typePayout" value="payout">
                                        <label class="form-check-label" for="typePayout">Payout</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transactionMethod" class="form-label">Method of Transaction</label>
                                <select class="form-select" id="transactionMethod" name="transaction_method">
                                    <option selected disabled>Choose...</option>
                                    <option value="cash">Cash</option>
                                    <option value="momo">MoMo</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="slipAmount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="slipAmount" name="amount" step="0.01"
                                    placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="_submit" value="add-contribution" class="btn btn-primary">Submit
                                Slip</button>
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
        $('#contributionsTable').DataTable();
    });
    </script>

</body>

</html>```