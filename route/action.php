<?php
    try {
        $main = $_REQUEST['_submit'];
        setcookie('_submit', $main, time() + 3600);
        switch($_REQUEST['_submit'])
        {

            case "login":
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                if ($username && $password) {
                    $user = $business->login($username, $password);
                    if ($user) {
                        // Successful login
                        $_SESSION['agentID'] = $user['agentId'];
                        $_SESSION['businessID'] = $user['businessId'];
                        $_SESSION['username'] = $user['username'];
                        // Set other session variables as needed
                        setcookie('businessID', $user['businessId'], time() + 3600);
                        setcookie('username', $user['username'], time() + 3600);

                        // Log the login event
                        //$staflogs->logEvent($user['staff_id'], 'login', 'User logged in');
                        header("Location: index.php?_main=dashboard");
                        exit();
                    } else {
                        // Invalid credentials
                        $_SESSION['error'] = "Invalid username or password.";
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    // Missing username or password
                    $_SESSION['error'] = "Please enter both username and password.";
                    header("Location: index.php");
                    exit();
                }
                break;

            case "logout":
                require_once($function['logout']);
                break;

            case "forgot-password":
                $email = $_POST['email'] ?? '';
                if ($email) {
                    $user = $administrator->findByEmail($email);
                    if ($user) {
                        // Generate a password reset token and send email
                        $token = bin2hex(random_bytes(16));
                        $administrator->setResetToken($user['id'], $token);
                        // Send email logic here (omitted for brevity)
                        $_SESSION['message'] = "Password reset instructions have been sent to your email.";
                    } else {
                        $_SESSION['error'] = "No account found with that email address.";
                    }
                } else {
                    $_SESSION['error'] = "Please enter your email address.";
                }
                break;

            case "reset-password":
                require_once($function['reset-password']);
                break;

            case "2f-auth":
                require_once($function['2f-auth']);
                break;

            case "change-password":
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                if ($currentPassword && $newPassword) {
                    $user = $authentication->findById($_SESSION['admin_id']);
                    if ($user && $authentication->verifyPassword($currentPassword, $user['password'])) {
                        $authentication->updatePassword($user['id'], $newPassword);
                        $_SESSION['message'] = "Password changed successfully.";
                    } else {
                        $_SESSION['error'] = "Current password is incorrect.";
                    }
                } else {
                    $_SESSION['error'] = "Please fill in all fields.";
                }
                break;

            case"add-member":
                $data['agentId'] = $_SESSION['agentID'];
                $data['accountNumber'] = time();
                $data['firstName'] = $_POST['first_name'];
                $data['midName'] = $_POST['middle_name'];
                $data['lastName'] =$_POST['last_name'];
                $data['gender'] = $_POST['gender'];
                $data['dob'] = $_POST['dob'];
                $data['maritalStatus'] = $_POST['marital_status'];
                $data['nationality'] = $_POST['nationality'];
                $data['occupation'] = $_POST['occupation'];
                $data['mobile'] = $_POST['mobile'];
                $data['email'] = $_POST['email'];
                $data['postalAddress'] = $_POST['postal_address'];
                $data['digitalAddress'] = $_POST['digital_address'];
                $data['contactAddress'] = $_POST['home_address'];
                $data['nkName'] = $_POST['nok_name'];
                $data['nkRelationship'] = $_POST['nok_relationship'];
                $data['nkMobile'] = $_POST['nok_mobile'];
                $data['passport'] = '';
                $data['govtIDcard'] = '';
                $data['status'] ='active';
                $clientId = $client->createClient($data);
                if($clientId == false){
                    $url['_main'] = $_COOKIE['_main'];
                    $url['err'] = 105;
                }else{
                    $addToLedger = $ledger-> createLedgerEntry([
                        'agentId'=> $_SESSION['agentID'],
                        'clientId'=>$clientId,
                        'dr'=>0,
                        'cr'=>0
                    ]);
                    $url['_main'] = $_COOKIE['_main'];
                    $url['err'] = 106;
                }
                break;
            
            case'update-member':
                $data['agentId'] = $_SESSION['agentID'];
                $data['firstName'] = $_POST['first_name'];
                $data['midName'] = $_POST['middle_name'];
                $data['lastName'] =$_POST['last_name'];
                $data['gender'] = $_POST['gender'];
                $data['dob'] = $_POST['dob'];
                $data['maritalStatus'] = $_POST['marital_status'];
                $data['nationality'] = $_POST['nationality'];
                $data['occupation'] = $_POST['occupation'];
                $data['mobile'] = $_POST['mobile'];
                $data['email'] = $_POST['email'];
                $data['postalAddress'] = $_POST['postal_address'];
                $data['digitalAddress'] = $_POST['digital_address'];
                $data['contactAddress'] = $_POST['home_address'];
                $data['nkName'] = $_POST['nok_name'];
                $data['nkRelationship'] = $_POST['nok_relationship'];
                $data['nkMobile'] = $_POST['nok_mobile'];
                $data['passport'] = '';
                $data['govtIDcard'] = '';
                $data['status'] ='active';
                $clientId = $client->updateClient($_SESSION['clientID'], $data);
                if($clientId == false){
                    $url['_main'] = $_COOKIE['_main'];
                    $url['id'] = $_SESSION['clientID'];
                    $url['err'] = 105;
                }else{
                    $url['_main'] = $_COOKIE['_main'];
                    $url['id'] = $_SESSION['clientID'];
                    $url['err'] = 107;
                }
                break;

            case'del-member':
                    $del = $client->deleteClient($_REQUEST['id']);
                    $del = $ledger->deleteLedgerByClientId($_REQUEST['id']);
                    $del = $transaction->deleteTransactionsByClientId($_REQUEST['id']);
                    
                    $url['_main'] = $_COOKIE['_main'];
                    $url['err'] = 107;
                break;
            
            case'add-contribution':
                $accountNumber = getNumeric($_POST['account_number']);
                $clientDetails = $client->getClientByAccountNumber($accountNumber);
                if($clientDetails == false){
                    $url['_main'] = $_COOKIE['_main'];
                    $url['err'] = 108;
                }else{
                    $ledgerTran = $ledger->getLedgerByClientId($clientDetails['clientId']);
                    if($_POST['transaction_type'] == 'contribution'){
                          
                        $data['agentId'] = $_SESSION['agentID'];
                        $data['clientId'] = $clientDetails['clientId'];
                        $data['tranDate'] = isset($_POST['slip_date'])? $_POST['slip_date'] : date('y-m-d');
                        $data['details'] = $_POST['description'];
                        $data['ref'] = isset($_POST['slip_ref']) ? $_POST['slip_ref'] : "CTX-".time(); 
                        $data['tranType'] = $_POST['transaction_type'];
                        $data['tranMethod'] = $_POST['transaction_method'];
                        $data['contribution'] = $_POST['amount'];
                        $data['payout'] = 0.00;
                        $data['amount'] = +$_POST['amount'];
                        $data['balance'] = $transaction->getBalanceByClient($clientDetails['clientId'],+$_POST['amount']);
                        $add = $transaction->createTransaction($data);
                        if($add == false){
                            $url['_main'] = $_COOKIE['_main'];
                            $url['err'] = 108;
                        }else{
                            $amt = $ledgerTran['dr'] + $_POST['amount'];
                            $update = $ledger->updateClientLedgerDr($clientDetails['clientId'],$amt);
                        }

                        $url['_main'] = $_COOKIE['_main'];
                        $url['err'] = 109;
                    }elseif($_POST['transaction_type'] == 'payout'){
                        
                        $data['agentId'] = $_SESSION['agentID'];
                        $data['clientId'] = $clientDetails['clientId'];
                        $data['tranDate'] = isset($_POST['slip_date'])? $_POST['slip_date'] : date('y-m-d');
                        $data['details'] = $_POST['description'];
                        $data['ref'] = isset($_POST['slip_ref']) ? $_POST['slip_ref'] : "PTX-".time(); 
                        $data['tranType'] = $_POST['transaction_type'];
                        $data['tranMethod'] = $_POST['transaction_method'];
                        $data['contribution'] = 0.00;
                        $data['payout'] = $_POST['amount'];
                        $data['amount'] = -$_POST['amount'];
                        $data['balance'] = $transaction->getBalanceByClient($clientDetails['clientId'],-$_POST['amount']);
                        $add = $transaction->createTransaction($data);
                        if($add == false){
                            $url['_main'] = $_COOKIE['_main'];
                            $url['err'] = 108;
                        }else{
                            $amt = $ledgerTran['cr'] + $_POST['amount'];
                            $update = $ledger->updateClientLedgerCr($clientDetails['clientId'],$amt);
                        }

                        $url['_main'] = $_COOKIE['_main'];
                        $url['err'] = 109;
                    }
                }
                break;
            
            default:
                require_once($template['404']);
                break;
        }

        header('location: ?'.http_build_query($url));
    } catch (Exception $e) {
        // Handle exception
        handle_fatal_error($e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
    }
?>