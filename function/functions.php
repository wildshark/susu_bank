<?php
/**
 * Determines the appropriate Bootstrap badge class based on a given status.
 *
 * @param string $status The status value (e.g., 'pending', 'completed').
 * @return string The corresponding CSS class for the badge.
 */
function isStatus($status){

    if($status =='pending'){
        return 'badge bg-warning text-dark';

    }elseif($status =='completed'){
        return 'badge bg-success';
    }else{
        return 'badge bg-danger';
    }
}

/**
 * Converts a value to a numeric type by removing all whitespace.
 *
 * @param mixed $value The value to be converted.
 * @return int|float The numeric representation of the value.
 */

function getNumeric($value){
    return preg_replace('/\s+/', '', (string) $value) + 0;
}

/**
 * Gets the full name of a month from its numeric representation.
 *
 * @param int|string $monthNumber The number of the month (1-12).
 * @return string The full month name (e.g., 'January') or 'Invalid Month' on failure.
 */

function getMonthName($monthNumber)
{
    $monthNumber = (int)$monthNumber;
    if ($monthNumber >= 1 && $monthNumber <= 12) {
        // Create a date object from the month number and format it to get the full month name
        return DateTime::createFromFormat('!m', $monthNumber)->format('F');
    }
    return 'Invalid Month';
}

/**
 * Gets the numeric representation of a month from its name.
 *
 * @param string $monthName The full or abbreviated name of the month (e.g., 'January', 'Jan').
 * @return string|int The month number (1-12) or 'Invalid Month' on failure.
 */

function getMonthNumberFromName($monthName)
{
    $timestamp = strtotime($monthName);
    if ($timestamp === false) {
        return 'Invalid Month';
    }
    return date('n', $timestamp);
}

function getListAccountNumber($listAccountNumbers){

    $data ='';
    if($listAccountNumbers){
        foreach($listAccountNumbers as $acct){

            $name = $acct['firstName']." ".$acct['lastName']." ".$acct['midName'];
            $account = $acct['accountNumber'];

            $data .= "<option>".$account." ".$name."</option>";


        }
    }
    return $data;
}

/**
 * Exports payout details to a CSV file.
 *
 * This function takes an array of payout details, generates a CSV file,
 * and sends it to the browser for download.
 *
 * @param array $payoutDetails An array of associative arrays, where each inner array
 *                             represents a row of payout data.
 * @param string $filename The name of the file to be downloaded.
 * @return void This function outputs a file and terminates the script.
 */
function exportToCsv($payoutDetails, $filename = 'payout_details.csv')
{
    // Clean any previous output buffer to prevent issues with headers
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Set the HTTP headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Open a file pointer to the output stream
    $output = fopen('php://output', 'w');

    // Write the CSV header row
    fputcsv($output, ['Date', 'Time', 'Account Number', 'Name', 'Method', 'Amount']);

    // Check if there is data to process
    if (!empty($payoutDetails)) {
        // Loop through the data and write each row to the CSV
        foreach ($payoutDetails as $row) {
            $name = $row['firstName'] . " " . $row['lastName'] . " " . $row['midName'];
            $time = date('h:i A', strtotime($row['cdate']));
            $amount = number_format($row['payout'], 2);
            $date =  $row['tranDate'];
            $account = $row['accountNumber'];
            $method = $row['tranMethod'];

            $csvRow = [$date, $time, $account, $name, $method, $amount];
            fputcsv($output, $csvRow);
        }
    }
    // Close the file pointer
    fclose($output);
    // Terminate the script to prevent any further output
    exit();
}
?>