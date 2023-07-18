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
require_once '../model/vehicles-model.php';

require_once '../library/functions.php';

require_once '../model/uploads-model.php';

require_once '../model/reviews-model.php';

require_once '../model/accounts-model.php';

$navList = getNavList(getClassificationIds());
$classifications = getClassificationIds();

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    case 'vehicle-management':
        if (!$_SESSION['loggedin'] and $_SESSION['clientData']['clientLevel'] < 3) {
            header("Location: /phpmotors/index.php");
            exit;
        }
        include '../view/vehicle-management.php';
        break;

    case 'add-vehicle':
        if (!$_SESSION['loggedin'] and $_SESSION['clientData']['clientLevel'] < 3) {
            header("Location: /phpmotors/index.php");
            exit;
        }
        include '../view/add-vehicle.php';
        break;

    case 'add-classification':
        if (!$_SESSION['loggedin'] and $_SESSION['clientData']['clientLevel'] < 3) {
            header("Location: /phpmotors/index.php");
            exit;
        }
        include '../view/add-classification.php';
        break;

    case 'create-vehicle':
        // Filter and store the data

        $invMake = trim(filter_input(INPUT_POST, 'invMake', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invModel = trim(filter_input(INPUT_POST, 'invModel', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invDescription = trim(filter_input(INPUT_POST, 'invDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invImage = trim(filter_input(INPUT_POST, 'invImage', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invThumbnail = trim(filter_input(INPUT_POST, 'invThumbnail', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invPrice = filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $invStock = trim(filter_input(INPUT_POST, 'invStock', FILTER_SANITIZE_NUMBER_INT));
        $invColor = trim(filter_input(INPUT_POST, 'invColor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $classificationId = trim(filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_INT));

        // Check for missing data
        if (empty($classificationId) || empty($invMake) || empty($invModel) || empty($invDescription) || empty($invImage) || empty($invThumbnail) || empty($invPrice) || empty($invStock) || empty($invColor)) {
            $message = '<h3 style="color: red;">Please provide information for all empty form fields.</h3>';
            include '../view/add-vehicle.php';
            exit;
        }
        // Send the data to the invModel
        $createVehicleOutcome = createVehicle($invMake, $invModel, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invColor, $classificationId);

        // Check and report the result
        if ($createVehicleOutcome === 1) {
            $message = "<h3>Thanks for creating the $invMake $invModel. It has been added successfully.</h3>";
            include '../view/vehicle-management.php';
            exit;
        } else {
            $message = "<h3>Sorry, but the creation of the vehicle failed. Please try again.</h3>";
            include '../view/add-vehicle.php';
            exit;
        }
        break;

    case 'create-classification':
        // Filter and store the data

        $classificationName = filter_input(INPUT_POST, 'classificationName');


        // Check for missing data
        if (empty($classificationName)) {
            $message = '<h3 style="color: red;">Please provide information for all empty form fields.</h3>';
            include '../view/add-classification.php';
            exit;
        }
        // Send the data to the invModel
        $createClassificationOutcome = createClassification($classificationName);

        // Check and report the result
        if ($createClassificationOutcome === 1) {
            $message = "<h3>Thanks for creating the new $classificationName classification type. It has been added successfully.</h3>";
            include '../view/vehicle-management.php';
            exit;
        } else {
            $message = "<h3>Sorry, but the creation of the classification failed. Please try again.</h3>";
            include '../view/add-classification.php';
            exit;
        }
        include '../view/add-classification.php';
        break;

        /* * ********************************** 
    * Get vehicles by classificationId 
    * Used for starting Update & Delete process 
    * ********************************** */
    case 'getInventoryItems':
        // Get the classificationId 
        $classificationId = filter_input(INPUT_GET, 'classificationId', FILTER_SANITIZE_NUMBER_INT);
        // Fetch the vehicles by classificationId from the DB 
        $inventoryArray = getInventoryByClassification($classificationId);
        // Convert the array to a JSON object and send it back 
        echo json_encode($inventoryArray);
        break;


    case 'mod':
        $invId = filter_input(INPUT_GET, 'invId', FILTER_VALIDATE_INT);
        $invInfo = getInvItemInfo($invId);
        if (count($invInfo) < 1) {
            $message = '<h3 style="color: red;">Sorry, no vehicle information could be found.</h3>';
        }
        include '../view/vehicle-update.php';
        exit;
        break;

    case 'updateVehicle':
        $invMake = trim(filter_input(INPUT_POST, 'invMake', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invModel = trim(filter_input(INPUT_POST, 'invModel', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invDescription = trim(filter_input(INPUT_POST, 'invDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invImage = trim(filter_input(INPUT_POST, 'invImage', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invThumbnail = trim(filter_input(INPUT_POST, 'invThumbnail', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invPrice = filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $invStock = trim(filter_input(INPUT_POST, 'invStock', FILTER_SANITIZE_NUMBER_INT));
        $invColor = trim(filter_input(INPUT_POST, 'invColor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $classificationId = trim(filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_INT));
        $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);

        // Check for missing data
        if (empty($classificationId) || empty($invMake) || empty($invModel) || empty($invDescription) || empty($invImage) || empty($invThumbnail) || empty($invPrice) || empty($invStock) || empty($invColor) || empty($invId)) {
            $message = '<h3 style="color: red;">Please provide information for all empty form fields.</h3>';
            include '../view/vehicle-update.php';
            exit;
        }
        // Send the data to the invModel
        $updateResult = updateVehicle($invMake, $invModel, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invColor, $classificationId, $invId);

        // Check and report the result
        if ($updateResult === 1) {
            $message = "<h3>Congratulations, the $invMake $invModel was successfully updated.</h3>";
            $_SESSION['message'] = $message;
            header('location: /phpmotors/vehicles/');
            exit;
        } else {
            $message = '<h3 style="color: red;">Sorry, but the update failed. Please try again.</h3>';
            include '../view/vehicle-update.php';
            exit;
        }

        break;

    case 'del':
        $invId = filter_input(INPUT_GET, 'invId', FILTER_VALIDATE_INT);
        $invInfo = getInvItemInfo($invId);
        if (count($invInfo) < 1) {
            $message = '<h3 style="color: red;">Sorry, no vehicle information could be found.</h3>';
        }
        include '../view/vehicle-delete.php';
        exit;
        break;

    case 'deleteVehicle':
        $invMake = trim(filter_input(INPUT_POST, 'invMake', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invModel = trim(filter_input(INPUT_POST, 'invModel', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);

        // Check for missing data
        if (empty($invMake) || empty($invModel) || empty($invId)) {
            $message = '<h3 style="color: red;">Please provide information for all empty form fields.</h3>';
            include '../view/vehicle-update.php';
            exit;
        }
        // Send the data to the invModel
        $deleteResult = deleteVehicle($invId);

        // Check and report the result
        if ($deleteResult === 1) {
            $message = "<h3 style='color: green;'>The $invMake $invModel was successfully deleted.</h3>";
            $_SESSION['message'] = $message;
            header('location: /phpmotors/vehicles/');
            exit;
        } else {
            $message = "<h3>Error: $invMake $invModel was not deleted.</h3>";
            $_SESSION['message'] = $message;
            header('location: /phpmotors/vehicles/');
            exit;
        }
        break;

    case 'classification':
        $classificationName = filter_input(INPUT_GET, 'classificationName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $vehicles = getVehiclesByClassification($classificationName);
        if (!count($vehicles)) {
            $message = "<p class='notice'>Sorry, no $classificationName vehicles could be found.</p>";
        } else {
            $vehicleDisplay = buildVehiclesDisplay($vehicles);
        }
        include '../view/classification.php';
        exit;
        break;


    case 'getVehicle':
        $invId = filter_input(INPUT_GET, 'invId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $vehicle = getInvItemInfo($invId);
        $images = getImagesByInvId($invId);
        if (isset($_SESSION['clientData'])) {
            $firstName = $_SESSION['clientData']['clientFirstname'];
            $lastName = $_SESSION['clientData']['clientLastname'];
            $screenName = substr($firstName, 0, 1) . $lastName;

        }
        $vehicle['invPrice'] = number_format($vehicle['invPrice'], 2);
        $vehicle['invStock'] = number_format($vehicle['invStock']);
        if (!count($vehicle)) {
            $message = "<p class='notice'>Sorry, that vehicle could not be found.</p>";
        } else {
            $detailDisplay = buildDetailDisplay($vehicle, $images);
            $reviews = getReviewsByInvId($invId);
            if (!count($reviews)) {
                $message = "<p class='notice'>Be the first to leave a review.</p>";
            } 
            else {
                $reviewDisplay = '<div id="review-display">';
                $reviewDisplay .= '<h4>Existing Reviews</h4>';

                foreach ($reviews as $review) {
                    $clients = getClientInfo($review['clientId']);
                    $firstName = $clients['clientFirstname'];
                    $lastName = $clients['clientLastname'];
                    $screenName = substr($firstName, 0, 1) . $lastName;
                    $reviewDisplay .= buildDetailReviewDisplay($review, $screenName);
                }
                $reviewDisplay .= "</div>";
            }
        }

        
        include '../view/vehicle-detail.php';
        exit;
        break;

    default:
        $classificationList = buildClassificationList($classifications);

        include '../view/vehicle-management.php';
        break;
}
