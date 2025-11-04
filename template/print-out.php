<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Statement - AC-1001</title>

    <!-- Bootstrap 5.0 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom Print CSS -->
    <style>
        body {
            background-color: #f5f4f1; /* Light gray background for the screen view */
        }
        .statement-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2.5rem;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .statement-header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00668c;
        }
        .summary-table th, .summary-table td {
            text-align: center;
        }
        .transaction-table th, .transaction-table td {
            font-size: 0.9rem;
        }
        .text-right {
            text-align: right;
        }
        
        /* -- PRINT STYLES -- */
        @media print {
            body {
                background-color: #fff !important;
            }
            .statement-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }
            .no-print {
                display: none !important;
            }
            /* Ensure text is black and background is white for printing */
            * {
                color: #000 !important;
                background: #fff !important;
            }
            .table {
                /* Remove bootstrap's default color styles on print */
                --bs-table-striped-color: #000;
                --bs-table-striped-bg: #f9f9f9;
            }
        }
    </style>
</head>
<body>

    <!-- Action Buttons (Hidden on Print) -->
    <div class="container text-center py-3 no-print">
        <button class="btn btn-primary" onclick="window.print();"><i class="fas fa-print me-2"></i>Print Statement</button>
        <button class="btn btn-secondary" onclick="window.close();"><i class="fas fa-times me-2"></i>Close</button>
    </div>

    <!-- Statement Content -->
    <div class="statement-container">
        <!-- Header -->
        <header class="statement-header pb-3 mb-4 border-bottom">
            <div class="row">
                <div class="col-6">
                    <div class="logo"><i class="fas fa-users-cog me-2"></i>Susu Enterprise</div>
                    <p class="text-muted small mb-0">P.O. Box 123, Accra<br>+233 24 123 4567<br>contact@susu.com</p>
                </div>
                <div class="col-6 text-right">
                    <h1 class="h2">Account Statement</h1>
                </div>
            </div>
        </header>

        <!-- Member Details -->
        <section class="member-details mb-4">
            <div class="row">
                <div class="col-7">
                    <strong>Statement Issued To:</strong><br>
                    <?=$profile['firstName']." ".$profile['lastName']." ".$profile['midName']?><br>
                    <?=$profile['postalAddress']?><br>
                    <?=$profile['digitalAddress']?>
                </div>
                <div class="col-5 text-right">
                    <strong>Account Number:</strong> AC-<?=$profile['accountNumber']?><br>
                    <strong>Statement Date:</strong> <?=date('M d, Y')?><br>
                    <strong>Statement Period:</strong> <?=date('M d, Y',strtotime($profile['cdate']))?> - <?=date('M d, Y')?>
                </div>
            </div>
        </section>

        <!-- Financial Summary -->
        <section class="financial-summary mb-4">
            <table class="table table-bordered summary-table">
                <thead class="table-light">
                    <tr>
                        <th>Total Contributions</th>
                        <th>Total Payouts</th>
                        <th>Available Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><h5 class="mb-0 fw-bold text-success">$<?=number_format($summary['dr'],2)?></h5></td>
                        <td><h5 class="mb-0 fw-bold text-danger">$<?=number_format($summary['cr'],2)?></h5></td>
                        <td><h5 class="mb-0 fw-bold text-primary">$<?=number_format($summary['bal'],2)?></h5></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Transaction History -->
        <section class="transaction-history">
            <h5 class="mb-3">Transaction History</h5>
            <table class="table table-striped table-bordered transaction-table">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Ref</th>
                        <th scope="col">Details</th>
                        <th scope="col" class="text-right">Contribution</th>
                        <th scope="col" class="text-right">Payout</th>
                        <th scope="col" class="text-right">Running Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(!$transactions){
                            echo'';
                        }else{

                            foreach($transactions as $transaction){

                                $date =  $transaction['tranDate'];
                                $ref = $transaction['ref'];
                                $details = $transaction['details'];
                            
                                if($transaction['contribution'] == 0){
                                    $transaction['contribution'] = '-';
                                }

                                if($transaction['payout'] == 0){
                                    $transaction['payout'] = '-';
                                }

                                if($transaction['balance'] == 0){
                                    $transaction['balance'] ='-';
                                }

                                $css_contribution = isset($transaction['contribution']) ? 'text-success' : 'text-danger';
                                $css_payout = isset($transaction['payout']) ? 'text-danger' : 'text-dark';

                                echo"<tr>
                                    <td>$date</td>
                                    <td>$ref</td>
                                    <td>$details</td>
                                    <td class='text-right $css_contribution'>".$transaction['contribution']."</td>
                                    <td class='text-right $css_payout'>". $transaction['payout']."</td>
                                    <td class='text-right'>". $transaction['balance'] ."</td>
                                </tr>";

                            }
                        }
                    ?>
                    <!-- More rows as needed -->
                </tbody>
            </table>
        </section>

        <!-- Footer -->
        <footer class="statement-footer pt-4 mt-4 text-center text-muted border-top">
            <p class="small">Thank you for your business.<br>If you have any questions concerning this statement, please contact us at +233 24 123 4567.</p>
        </footer>
    </div>

</body>
</html>