<?php
// this is the reviews controller

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    case 'newReview':
        $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);
        $clientId = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT);
        $reviewText = filter_input(INPUT_POST, 'reviewText', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($reviewText) || empty($clientId) || empty($invId)) {
            $message = '<h3 style="color: red;">Please provide information for the empty form field.</h3>';
            !$_SESSION['message'] = $message;
            $reviews = getReviewsByInvId($invId);
            $vehicle = getInvItemInfo($invId);
            $images = getImagesByInvId($invId);
            $detailDisplay = buildDetailDisplay($vehicle, $images);
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
            include '../view/vehicle-detail.php';
            exit;
        }

        $vehicle = getInvItemInfo($invId);
        $images = getImagesByInvId($invId);
        $vehicle['invPrice'] = number_format($vehicle['invPrice'], 2);
        $vehicle['invStock'] = number_format($vehicle['invStock']);
        if (!count($vehicle)) {
            $message = "<p class='notice'>Sorry, that vehicle could not be found.</p>";
            !$_SESSION['message'] = $message;
        } else {
            $detailDisplay = buildDetailDisplay($vehicle, $images);
        }



        // Send the data to the Model
        $createReviewOutcome = createReview($reviewText, $invId, $clientId);

        // Check and report the result
        if ($createReviewOutcome === 1) {
            $message = "<h3 style='color: green;'>Thank you for submitting your review. It has been added successfully.</h3>";
            $_SESSION['message'] = $message;
            header("Location: /phpmotors/reviews/index.php?action=reviewDisplay&invId=$invId");
            exit;
        } else {
            $message = "<h3 style='color: red;'>Sorry, but the review submission was unsuccessful. Please try again.</h3>";
            $_SESSION['message'] = $message;
            include '../view/vehicle-detail.php';
            exit;
        }






    case 'reviewDisplay':
        $invId = filter_input(INPUT_GET, 'invId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reviews = getReviewsByInvId($invId);
        $vehicle = getInvItemInfo($invId);
        $images = getImagesByInvId($invId);
        $vehicle['invPrice'] = number_format($vehicle['invPrice'], 2);
        $vehicle['invStock'] = number_format($vehicle['invStock']);
        if (!count($vehicle)) {
            $message = "<p class='notice'>Sorry, that vehicle could not be found.</p>";
            !$_SESSION['message'] = $message;
        } else {
            $detailDisplay = buildDetailDisplay($vehicle, $images);
        }
        if (count($reviews) === 0 || empty($reviews)) {
            $reviewDisplay =  '<p>Be the first to leave a review</p>';
        } else {
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

        include '../view/vehicle-detail.php';
        exit;
        break;

    case 'edit':
        $reviewId = filter_input(INPUT_GET, 'reviewId', FILTER_SANITIZE_NUMBER_INT);
        $review = getReview($reviewId);
        $reviewData = $review[0];
        $invId = $reviewData['invId'];
        $reviewText = $reviewData['reviewText'];
        $reviewDate = $reviewData['reviewDate'];

        if (empty($reviewId) || empty($review) || empty($reviewData) || empty($invId) || empty($reviewText) || empty($reviewDate)) {
            $message = '<h3 style="color: red;">Please provide information for the empty form field.</h3>';
            $_SESSION['message'] = $message;
            $review = getReview($reviewId);
            $invId = $review['invId'];
            $vehicle = getInvItemInfo($invId);
            $reviewEditDisplay = buildReviewEditDisplay($review, $vehicle);
            include '../view/review-edit.php';
            exit;
        } else {
            $vehicle = getInvItemInfo($invId);
            $reviewEditDisplay = buildReviewEditDisplay($reviewData, $vehicle);
        }



        include '../view/review-edit.php';
        exit;
        break;

    case 'updateReview':
        $reviewId = filter_input(INPUT_POST, 'reviewId', FILTER_SANITIZE_NUMBER_INT);
        $reviewText = filter_input(INPUT_POST, 'reviewText', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Check for missing data
        if (empty($reviewText) || empty($reviewId)) {
            $message = '<h3 style="color: red;">Please provide information for the empty form field.</h3>';
            $_SESSION['message'] = $message;
            $review = getReview($reviewId);
            $reviewData = $review[0];
            $invId = $reviewData['invId'];
            $vehicle = getInvItemInfo($invId);
            $reviewEditDisplay = buildReviewEditDisplay($reviewData, $vehicle);
            include '../view/review-edit.php';
            exit;
        }

        $updateResult = updateReview($reviewId, $reviewText);

        // Check and report the result
        if ($updateResult === 1) {
            $message = "<h3 style='color: green;'>Congratulations, your review was successfully updated.</h3>";
            $_SESSION['message'] = $message;
            header('location: /phpmotors/accounts/');
            exit;
        } else {
            $message = '<h3 style="color: red;">Sorry, but the update failed. Please try again.</h3>';
            $_SESSION['message'] = $message;
            $review = getReview($reviewId);
            $reviewData = $review[0];
            $invId = $reviewData['invId'];
            $vehicle = getInvItemInfo($invId);
            $reviewEditDisplay = buildReviewEditDisplay($reviewData);
            include '../view/review-edit.php';
            exit;
        }

        include '../view/admin.php';
        exit;
        break;

    case 'confirmDelete':
        $reviewId = filter_input(INPUT_GET, 'reviewId', FILTER_SANITIZE_NUMBER_INT);
        $review = getReview($reviewId);
        $reviewData = $review[0];
        $invId = $reviewData['invId'];
        $reviewText = $reviewData['reviewText'];
        $reviewDate = $reviewData['reviewDate'];
        $vehicle = getInvItemInfo($invId);


        $reviewDeleteDisplay = buildReviewDeleteDisplay($reviewData);

        include '../view/review-delete.php';
        exit;
        break;


    case 'deleteReview':
        $reviewId = filter_input(INPUT_POST, 'reviewId', FILTER_SANITIZE_NUMBER_INT);
        $deleteResult = deleteReview($reviewId);

        // Check and report the result
        if ($deleteResult === 1) {
            $message = "<h3 style='color: green;'>Your review was successfully deleted.</h3>";
            $_SESSION['message'] = $message;
            header('location: /phpmotors/accounts/');
            exit;
        } else {
            $message = '<h3 style="color: red;">Sorry, but the review was not able to be deleted. Please try again.</h3>';
            $_SESSION['message'] = $message;
            $review = getReview($reviewId);
            $reviewData = $review[0];
            $invId = $reviewData['invId'];
            $vehicle = getInvItemInfo($invId);
            $reviewDeleteDisplay = buildReviewDeleteDisplay($reviewData);
            include '../view/review-delete.php';
            exit;
        }

        break;




    default:
        $classificationList = buildClassificationList($classifications);

        include '../view/admin.php';
        break;
}
