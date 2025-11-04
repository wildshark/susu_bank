<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Reports</title>

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

    #results-placeholder {
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-200);
        background-color: var(--bg-200);
        border-radius: .25rem;
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
                <h1 class="h2">Generate Reports</h1>
            </div>

            <!-- Report Parameters -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Report Parameters</h5>
                </div>
                <div class="card-body">
                    <form id="reportForm" action="index.php" method="get">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reportType" class="form-label">Report Type</label>
                                <select class="form-select" id="reportType" name="report_type">
                                    <option selected>Contribution Summary</option>
                                    <option>Payout Summary</option>
                                    <option>Member List</option>
                                    <option>Individual Account Statement</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="memberAccount" class="form-label">Member Account (Optional)</label>
                                <input type="text" class="form-control" id="memberAccount" name="member_account"
                                    placeholder="e.g., AC-1001">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-cogs me-2"></i>Generate
                            Report</button>
                    </form>
                </div>
            </div>

            <!-- Report Results -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Report Results</h5>
                    <div id="report-actions" style="display: none;">
                        <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-print me-1"></i>
                            Print</button>
                        <button class="btn btn-sm btn-outline-success"><i class="fas fa-file-csv me-1"></i> Export
                            CSV</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="results-placeholder">
                        <p class="text-center">Report results will be displayed here after generation.</p>
                    </div>
                    <div id="results-container" style="display: none;">
                        <div class="table-responsive">
                            <table id="reportResultsTable" class="table table-striped" style="width:100%">
                                <!-- Table Head and Body will be populated by a real app, here's a sample -->
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>Member Name</th>
                                        <th>Type</th>
                                        <th>Amount ($)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2025-10-28</td>
                                        <td>TXN756389</td>
                                        <td>Jane Smith</td>
                                        <td>Contribution</td>
                                        <td>100.00</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-10-27</td>
                                        <td>TXN756388</td>
                                        <td>John Doe</td>
                                        <td>Contribution</td>
                                        <td>50.00</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                    <tr>
                                        <td>2025-10-27</td>
                                        <td>TXN756387</td>
                                        <td>Peter Jones</td>
                                        <td>Payout</td>
                                        <td>-250.00</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                </tbody>
                            </table>
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

    $(document).ready(function() {
        // Simulation of report generation
        $('#reportForm').on('submit', function(e) {
            e.preventDefault(); // Prevent actual form submission

            const placeholder = $('#results-placeholder');
            const resultsContainer = $('#results-container');
            const reportActions = $('#report-actions');

            // 1. Hide current results and show loading message in placeholder
            resultsContainer.hide();
            reportActions.hide();
            placeholder.html(
                '<div class="spinner-border spinner-border-sm" role="status"></div><span class="ms-2">Generating report...</span>'
                ).show();

            // 2. Simulate a delay for fetching data
            setTimeout(() => {
                // 3. Hide placeholder and show the results table
                placeholder.hide();
                resultsContainer.show();
                reportActions.show();

                // 4. Initialize DataTables on the results table
                // We destroy any previous instance to re-initialize it with new data in a real app
                if ($.fn.DataTable.isDataTable('#reportResultsTable')) {
                    $('#reportResultsTable').DataTable().destroy();
                }
                $('#reportResultsTable').DataTable();

            }, 1500); // 1.5 second delay
        });
    });
    </script>

</body>

</html>