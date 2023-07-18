<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// this is the accounts controller

//Create or access a Session
session_start();

// Get the database connection file
require_once '../library/connections.php';
// Get the PHP Motors model for use as needed
require_once '../model/main-model.php';
// Get the accounts model
require_once '../model/accounts-model.php';

require_once '../library/functions.php';

require_once '../model/reviews-model.php';

require_once '../model/vehicles-model.php';

$navList = getNavList(getClassifications());

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    case 'login-default':
        include '../view/login.php';
        break;

    case 'registration':
        include '../view/registration.php';
        break;

    case 'register':
        // Filter and store the data
        $clientFirstname = trim(filter_input(INPUT_POST, 'clientFirstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $clientLastname = trim(filter_input(INPUT_POST, 'clientLastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
        $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $clientEmail = checkEmail($clientEmail);
        $checkPassword = checkPassword($clientPassword);

        // check for existing email
        $existingEmail = checkExistingEmail($clientEmail);

        if ($existingEmail) {
            $message = '<p> The email address already exists. Do you want to login instead?</p>';
            include '../view/login.php';
            exit;
        }

        // Check for missing data
        if (empty($clientFirstname) || empty($clientLastname) || empty($clientEmail) || empty($checkPassword)) {
            $message = '<h3 style="color: red;">Please provide information for all empty form fields.</h3>';
            include '../view/registration.php';
            exit;
        }

        $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);
        // Send the data to the model
        $regOutcome = regClient($clientFirstname, $clientLastname, $clientEmail, $hashedPassword);
        // Check and report the result
        if ($regOutcome === 1) {
            setcookie('firstname', $clientFirstname, strtotime('+1 year'), '/');
            $_SESSION['message'] = "Thanks for registering, $clientFirstname. Please use your email and password to login.";
            header('Location: /phpmotors/accounts/?action=login');
            exit;
        } else {
            $message = "<p>Sorry $clientFirstname, but the registration failed. Please try again.</p>";
            include '../view/registration.php';
            exit;
        }
        break;

    case 'Login':
        $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
        $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $clientEmail = checkEmail($clientEmail);
        $checkPassword = checkPassword($clientPassword);

        // Check for missing data
        if (empty($clientEmail) || empty($checkPassword)) {
            $message = '<p>Please provide information for all empty form fields.</p>';
            include '../view/login.php';
            exit;
        }
        // A valid password exists, proceed with the login process
        // Query the client data based on the email address
        $clientData = getClient($clientEmail);
        // Compare the password just submitted against
        // the hashed password for the matching client
        $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);
        // If the hashes don't match create an error
        // and return to the login view
        if (!$hashCheck) {
            $message = '<p class="notice">Please check your password and try again.</p>';
            include '../view/login.php';
            exit;
        }
        // A valid user exists, log them in
        $_SESSION['loggedin'] = TRUE;
        // Remove the password from the array
        // the array_pop function removes the last
        // element from an array
        array_pop($clientData);
        // Store the array into the session
        $_SESSION['clientData'] = $clientData;
        $firstName = $_SESSION['clientData']['clientFirstname'];
        $lastName = $_SESSION['clientData']['clientLastname'];
        $screenName = substr($firstName, 0, 1) . $lastName;
        $_SESSION['screenName'] = $screenName;

        $reviews = getReviewsByClientId($_SESSION['clientData']['clientId']);
        if (isset($reviews)) {
            $adminReviewsDisplay = '<div id="admin-review-display">';
            $adminReviewsDisplay .= "<h2>Manage Your Product Reviews</h2>";
            $adminReviewsDisplay .= "<ul>";
            foreach ($reviews as $review) {
                $invInfo = getInvItemInfo($review['invId']);
                $adminReviewsDisplay .= buildAdminReviewDisplay($review, $invInfo);
            }
            $adminReviewsDisplay .= "</ul>";
            $adminReviewsDisplay .= "</div>";
            
            
            
        }

        // Send them to the admin view
        include '../view/admin.php';
        exit;
        break;

    case 'admin':
        $reviews = getReviewsByClientId($_SESSION['clientData']['clientId']);
            if (isset($reviews)) {
                $adminReviewsDisplay = '<div id="admin-review-display">';
                $adminReviewsDisplay .= "<h2>Manage Your Product Reviews</h2>";
                $adminReviewsDisplay .= "<ul>";
                foreach ($reviews as $review) {
                    $invInfo = getInvItemInfo($review['invId']);
                    $adminReviewsDisplay .= buildAdminReviewDisplay($review, $invInfo);
                }
                $adminReviewsDisplay .= "</ul>";
                $adminReviewsDisplay .= "</div>";
                
                
                
            }
        include '../view/admin.php';
        break;

    case 'logout':
        session_unset();
        session_destroy();
        include '../index.php';
        break;

    case 'update':
        $clientId = filter_input(INPUT_GET, 'clientId', FILTER_VALIDATE_INT);
        $clientInfo = getClientInfo($clientId);
        if (count($clientInfo) < 1) {
            $message = '<h3 style="color: red;">Sorry, no account information could be found.</h3>';
        }
        include '../view/client-update.php';
        exit;
        break;

    case 'updateAccount':

        // Filter and store the data
        $clientId = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT);
        $clientData = getClientInfo($clientId);

        $clientFirstname = trim(filter_input(INPUT_POST, 'clientFirstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $clientLastname = trim(filter_input(INPUT_POST, 'clientLastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));

        $clientEmail = checkEmail($clientEmail);

        // check for existing email


        if ($clientEmail != $clientData['clientEmail']) {
            $existingEmail = checkExistingEmail($clientEmail);
            if ($existingEmail) {
                $message = '<p style="color: red;"> That email address already exists. Do you want to login instead? (Note: You must logout first.)</p>';
                $_SESSION['message'] = $message;
                include '../view/login.php';
                exit;
            }
        }

        // Check for missing data
        if (empty($clientFirstname) || empty($clientLastname) || empty($clientEmail) || empty($clientId)) {
            $message = '<h3 style="color: red;">Please provide information for all empty form fields.</h3>';
            include '../view/client-update.php';
            exit;
        }

        $updateOutcome = updateClient($clientFirstname, $clientLastname, $clientEmail, $clientId);

        // Check and report the result
        if ($updateOutcome === 1) {
            setcookie('firstname', $clientFirstname, strtotime('+1 year'), '/');
            $clientInfo = getClientInfo($clientId);
            // Update the session with the new data
            $_SESSION['clientData'] = $clientInfo;
            $message = "<h3>Thank you {$_SESSION['clientData']['clientFirstname']}. Your account has been updated successfully.</h3>";
            $_SESSION['message'] = $message;
            header('Location: /phpmotors/accounts/');
            exit;
        } else {
            $message = "<p>Sorry $clientFirstname, but the update failed. Please try again.</p>";
            include '../view/client-update.php';
            exit;
        }
        break;

    case 'changePassword':
        // Filter and store the data
        $clientId = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT);
        $clientData = getClientInfo($clientId);
        $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        // $clientEmail = checkEmail($clientEmail);
        $checkPassword = checkPassword($clientPassword);

        if (!$checkPassword) {
            $message = '<h3 style="color: red;">Please match the requested format.</h3>';
            include '../view/client-update.php';
            exit;
        }

        // Check for missing data
        if (empty($clientPassword || empty($checkPassword))) {
            $message = '<h3 style="color: red;">Please provide information for all empty form fields.</h3>';
            include '../view/client-update.php';
            exit;
        }

        $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);
        if (isset($hashedPassword)) {
            $passwordUpdate = updatePassword($hashedPassword, $clientId);
        }

        // Check and report the result
        if ($passwordUpdate === 1) {
            $clientInfo = getClientInfo($clientId);
            // Update the session with the new data
            $_SESSION['clientData'] = $clientInfo;
            $message = "<h3>Thank you {$_SESSION['clientData']['clientFirstname']}. Your password has been updated successfully.</h3>";
            $_SESSION['message'] = $message;
            header('Location: /phpmotors/accounts/');
            exit;
        } else {
            $message = "<p>Sorry $clientFirstname, but the update failed. Please try again.</p>";
            include '../view/client-update.php';
            exit;
        }
        break;

    default:

        if (isset($_SESSION['loggedin'])) {
            $firstName = $_SESSION['clientData']['clientFirstname'];
            $lastName = $_SESSION['clientData']['clientLastname'];
            $screenName = substr($firstName, 0, 1) . $lastName;
            $_SESSION['screenName'] = $screenName;
            
            $reviews = getReviewsByClientId($_SESSION['clientData']['clientId']);
            if (isset($reviews)) {
                $adminReviewsDisplay = '<div id="admin-review-display">';
                $adminReviewsDisplay .= "<h2>Manage Your Product Reviews</h2>";
                $adminReviewsDisplay .= "<ul>";
                foreach ($reviews as $review) {
                    $invInfo = getInvItemInfo($review['invId']);
                    $adminReviewsDisplay .= buildAdminReviewDisplay($review, $invInfo);
                }
                $adminReviewsDisplay .= "</ul>";
                $adminReviewsDisplay .= "</div>";
                
                
                
            }
            
            include '../view/admin.php';
            exit;
        }
        include '../view/login.php';
        break;
}
