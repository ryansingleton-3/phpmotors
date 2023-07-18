<?php
// -Reviews Model


function createReview($reviewText, $invId, $clientId){

    $db = phpmotorsConnect();
    $sql = 'INSERT INTO reviews (reviewText, invId, clientId)
        VALUES (:reviewText, :invId, :clientId)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewText', $reviewText, PDO::PARAM_STR);
    $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->errorInfo()[2]) {
        echo $stmt->errorInfo()[2];
    }
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

function getReviewsByInvId($invId) {
    $db = phpmotorsConnect();
    $sql = 'SELECT reviews.*, inventory.* 
            FROM reviews 
            JOIN inventory ON reviews.invId = inventory.invId 
            WHERE reviews.invId = :invId 
            ORDER BY reviews.reviewDate DESC';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':invId', $invId, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $reviews;
}

function getReviewsByClientId($clientId) {
    $db = phpmotorsConnect();
    $sql = 'SELECT reviews.*, clients.clientFirstname, clients.clientLastname 
            FROM reviews
            JOIN clients ON reviews.clientId = clients.clientId
            WHERE reviews.clientId = :clientId
            ORDER BY reviews.reviewDate DESC';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $reviews;
}



function getReview($reviewId){ 
    $db = phpmotorsConnect(); 
    $sql = ' SELECT * FROM reviews WHERE reviewId = :reviewId'; 
    $stmt = $db->prepare($sql); 
    $stmt->bindValue(':reviewId', $reviewId, PDO::PARAM_INT); 
    $stmt->execute(); 
    $review = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $stmt->closeCursor(); 
    return $review; 
}

function updateReview($reviewId, $reviewText){
    $db = phpmotorsConnect();
    $sql = 'UPDATE reviews SET reviewText = :reviewText
	WHERE reviewId = :reviewId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewId', $reviewId, PDO::PARAM_INT);
    $stmt->bindValue(':reviewText', $reviewText, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->errorInfo()[2]) {
        echo $stmt->errorInfo()[2];
    }
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

function deleteReview($reviewId){
    $db = phpmotorsConnect();
    $sql = 'DELETE FROM reviews WHERE reviewId = :reviewId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewId', $reviewId, PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->errorInfo()[2]) {
        echo $stmt->errorInfo()[2];
    }
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}



?>