<?php
    try {
        $main = $_REQUEST['_page'];
        setcookie('_page', $main, time() + 3600);
        switch($_REQUEST['_page']){

            case "reset-password":
                require_once($template['reset-password']);
                break;
                
            case "2f-auth":
                require_once($template['2f-auth']);
                break;
                
            default:
                require_once($template['login']);

        }
    } catch (Exception $e) {
        // Handle exception
        handle_fatal_error("".$e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
    }
?>