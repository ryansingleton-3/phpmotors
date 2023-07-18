<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//Create or access a Session
session_start();

// Get the database connection file
require_once 'library/connections.php';
// Get the PHP Motors model for use as needed
require_once 'model/main-model.php';

require_once 'library/functions.php';

// require_once './model/accounts-model.php';

$navList = getNavList(getClassifications());

if(isset($_COOKIE['firstname'])) {
    $cookieFirstname = filter_input(INPUT_COOKIE, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// this is the main controller
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL){
 $action = filter_input(INPUT_GET, 'action');
}

switch ($action){
    case 'template':
     include 'view/template.php';
     break;
    
    default:
    // $clientData = getClientInfo(18);
    // $_SESSION['clientData'] = $clientData;
     include 'view/home.php';
}




// echo $navList;
// exit;
// echo $action;
// exit;
?>