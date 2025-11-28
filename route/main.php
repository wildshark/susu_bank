<?php
    try {
        $main = $_REQUEST['_main'];
        setcookie('_main', $main, time() + 3600);
        $agentID = isset($_SESSION['agentID']) ? $_SESSION['agentID'] : 0;
        $businessID = isset($_SESSION['businessID']) ? $_SESSION['businessID'] : 'Anonymous';
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous';
        if($agentID == 0){
            header('location: index.php');
            exit();
        }
        switch($_REQUEST['_main']){

            case'dashboard':
                $totalMembers = $client->countClientbyAgent($agentID) ?? 0;
                $totalContribution = $transaction->getTotalAmountByAgentId($agentID); 
                $totalPayout = $transaction->getTotalPayoutByAgentId($agentID);
                $currentTransactions = $transaction->getCurrentTransactionByAgentId($agentID);
                require_once($template['dashboard']);
                break;

            case'profile':
                $profile = $business->getBusinessById($agentID);
                $ref_id = $profile['businessId'];
                $businessName = $profile['businessName'];
                $firstName = $profile['firstName'];
                $middleName = $profile['midName'];
                $lastName = $profile['lastName'];
                $email = $profile['email'];
                $mobile = $profile['mobile'];
                $postalAddress = $profile['postalAddress'];
                $digitalAddress = $profile['digitalAddress'];
                $region = $profile['region'];
                $district = $profile['district'];

                $username = $profile['username'];

                require_once($template['profile']);
                break;

            case'members':
                $members = $client->getClientsByAgentId($agentID);
                require_once($template['members']);
                break;
            
            case'member-detail':
                $clientID = $_GET['id'];
                $_SESSION['clientID'] = $clientID;
                $data = $client->getClientById($clientID);
                $agentID = $data['agentId'];
                $accountNumber = $data['accountNumber'];
                $firstName = $data['firstName'];
                $middleName = $data['midName'];
                $lastName = $data['lastName'];
                $gender = $data['gender'];
                $dob = $data['dob'];
                $maritalStatus = $data['maritalStatus'];
                $nationality = $data['nationality'];
                $occupation = $data['occupation'];
                $mobile = $data['mobile'];
                $email = $data['email'];
                $postalAddress = $data['postalAddress'];
                $digitalAddress = $data['digitalAddress'];
                $contactAddress = $data['contactAddress'];
                $nkName = $data['nkName'];
                $nkRelationship = $data['nkRelationship'];
                $nkMobile = $data['nkMobile'];
                $passport = $data['passport'];
                $govtIDcard = $data['govtIDcard'];
                $status = $data['status'];

                $clientName = $firstName . ' ' . $lastName . ' ' . $midName;
                require_once($template['member-form']);
                break;

            
            case'contribution':
                $listAccountNumbers = $client->getClientsByAgentId($agentID);
                $ledgers = $ledger->getLedgerByAgentId($agentID);
                require_once($template['contribution']);
                break;

            case'contribution-detail':
                $clientID = $_REQUEST['id'];
                $profile = $client->getClientById($clientID);
                $summary = $ledger->getLedgerByClientId($clientID);
                $transactions = $transaction->getTransactionsByClientId($clientID);
                require_once($template['contribution-details']);
                break;
            
            case'print-out':
                $clientID = $_REQUEST['id'];
                $profile = $client->getClientById($clientID);
                $summary = $ledger->getLedgerByClientId($clientID);
                $transactions = $transaction->getTransactionsByClientId($clientID);
                require_once($template['print-out']);
                break;
            
            case'payout':
                $payout = $transaction->getPayoutMonthlyTransaction($agentID);
                require_once($template['payout']);
                break;
            
            case'payout-detail':
                $id = $_REQUEST['id'];
                $year = $_REQUEST['year'];
                $month = $_REQUEST['month'];
                $monthNum = getMonthNumberFromName($month);
                $payoutDetails = $transaction->getAllTransactionsByAgentAndDate($agentID, $year, $monthNum,'payout');
                if(isset($_REQUEST['export'])){
                    exportToCsv($payoutDetails, $filename = 'payout_details.csv');
                    exit;
                } 
                require_once($template['payout-detail']);
                break;
            
            case'staffs':
                $staffs = $staff->getStaffByAgentId($agentID);  
                require_once($template['staffs']);
                break;  
            
            case'staff-detail':
                $staffID = $_REQUEST['id'];
                $staff = $staff->getStaff($staffID);
                $firstName = $staff['fName'];
                $middleName = $staff['mName'];
                $lastName = $staff['lName']; 
                $dob = $staff['dob'];
                $gender = $staff['gender'];
                $maritalStatus = $staff['maritalStatus'];
                $nationality = $staff['nationality'];
                $occupation = $staff['occupation'];
                $mobile = $staff['mobile'];
                $email = $staff['email'];
                $postalAddress = $staff['postalAddress'];
                $digitalAddress = $staff['digitalAddress'];
                $contactAddress = $staff['contactAddress'];
                $username = $staff['username'];
                $status = $staff['status'];
                $agentId = $staff['agentId'];
                require_once($template['staff-form']);
                break;
            
            case'staff-transaction-details':
                $staffID = $_REQUEST['id'];
                $staff = $staff->getStaff($staffID);
                $firstName = $staff['fName'];
                $middleName = $staff['mName'];
                $lastName = $staff['lName']; 
                $dob = $staff['dob'];
                $gender = $staff['gender'];
                $maritalStatus = $staff['maritalStatus'];
                $nationality = $staff['nationality'];
                $occupation = $staff['occupation'];
                $mobile = $staff['mobile'];
                $email = $staff['email'];
                $postalAddress = $staff['postalAddress'];
                $digitalAddress = $staff['digitalAddress'];
                $contactAddress = $staff['contactAddress'];
                $username = $staff['username'];
                $status = $staff['status'];
                $agentId = $staff['agentId'];
                
                $transactions = $transaction->getTransactionsByStaffId($staffID);

                require_once($template['staff-transaction-details']);
                break;  
            
            case'report':               
                require_once($template['report']);
                break;
           
            default:
                require_once($template['dashboard']);
                break;

        }
    } catch (Exception $e) {
        // Handle exception
        handle_fatal_error($e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());

    }
?>