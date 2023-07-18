<?php

    function checkEmail($clientEmail){
        $valEmail = filter_var($clientEmail, FILTER_VALIDATE_EMAIL);
        return $valEmail;
    }

    function checkPassword($clientPassword){
        $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]\s])(?=.*[A-Z])(?=.*[a-z])(?:.{8,})$/';
        return preg_match($pattern, $clientPassword);
    }
    
    // Build a navigation bar using the $classifications array
    function getNavList($classifications) {
        $navList = '<ul>';
        $navList .= "<li><a href='/phpmotors/index.php' title='View the PHP Motors home page'>Home</a></li>";
        foreach ($classifications as $classification) {
            $navList .= "<li><a href='/phpmotors/vehicles/?action=classification&classificationName="
            .urlencode($classification['classificationName']).
            "' title='View our $classification[classificationName] lineup of vehicles'>$classification[classificationName]</a></li>";

        }
        // $navList .= "<li><a href= '/phpmotors/vehicles/index.php?action=vehicle-management' id='vehicle-management'>Management</a></li>";
        $navList .= '</ul>';
        return $navList;
    }

    function consoleLog($data) {
        if (is_array($data)) {
            $data = implode(',', $data);
        }
        echo "<script>console.log('". $data ."');</script>";
    }

    // Build the classifications select list 
    function buildClassificationList($classifications){ 
        $classificationList = '<select name="classificationId" id="classificationList">'; 
        $classificationList .= "<option>Choose a Classification</option>"; 
        foreach ($classifications as $classification) { 
        $classificationList .= "<option value='$classification[classificationId]'>$classification[classificationName]</option>"; 
        } 
        $classificationList .= '</select>'; 
        return $classificationList; 
    }


    // Build a display of vehicles within an unordered list
    function buildVehiclesDisplay($vehicles){
        $dv = '<ul id="inv-display">';
        foreach ($vehicles as $vehicle) {
            $vehicle['invPrice'] = number_format($vehicle['invPrice'], 2);
            $vehicle['invStock'] = number_format($vehicle['invStock']);
            $dv .= '<li>';
            $dv .= "<a class='detail-page-link' href='/phpmotors/vehicles/index.php?action=getVehicle&invId={$vehicle['invId']}'><img src='{$vehicle['invThumbnail']}' alt='Image of {$vehicle['invMake']} {$vehicle['invModel']} on phpmotors.com'></a>";
            $dv .= "<a class='detail-page-link' href='/phpmotors/vehicles/index.php?action=getVehicle&invId={$vehicle['invId']}'><h2>{$vehicle['invMake']} {$vehicle['invModel']}</h2></a>";
            $dv .= "<span>\${$vehicle['invPrice']}</span>";
            $dv .= '</li>';
        }
        $dv .= '</ul>';
        return $dv;
    }

    function buildDetailDisplay($vehicle, $images){
        $dv = '<div id="inv-detail">';
        $dv .= "<div class='tn-images'>";
        foreach ($images as $image) {
            $dv .= "<img src='{$image}' alt='Image of {$vehicle['invMake']} {$vehicle['invModel']} on phpmotors.com' class='inv-image-tn'>";
        }
        $dv .= "</div>";
        $dv .= "<img src='{$vehicle['invImage']}' alt='Image of {$vehicle['invMake']} {$vehicle['invModel']} on phpmotors.com' class='inv-image'>";
        $dv .= "<div class='inv-info'>";
        $dv .= "<div class='inv-price'>\${$vehicle['invPrice']}</div>";
        $dv .= "<p class='inv-desc'>{$vehicle['invDescription']}</p>";
        $dv .= "<p class='inv-color'>Color: {$vehicle['invColor']}</p>";
        $dv .= "<p class='inv-stock'>Stock: {$vehicle['invStock']}</p>";
        $dv .= "</div>";
        $dv .= "<div class='thumbnail-header-div'><h3>Vehicle Thumbnails</h3>";
        $dv .= '</div>';
        $dv .= '</div>';
        return $dv;
    }
    

    /* * ********************************
    *  Functions for working with images
    * ********************************* */

    // Adds "-tn" designation to file name
    function makeThumbnailName($image) {
        $i = strrpos($image, '.');
        $image_name = substr($image, 0, $i);
        $ext = substr($image, $i);
        $image = $image_name . '-tn' . $ext;
        return $image;
    }
    

    // Build images display for image management view
    function buildImageDisplay($imageArray) {
        $id = '<ul id="image-display">';
        foreach ($imageArray as $image) {
        $id .= '<li class="image-li">';
        $id .= "<img src='$image[imgPath]' title='$image[invMake] $image[invModel] image on PHP Motors.com' alt='$image[invMake] $image[invModel] image on PHP Motors.com'>";
        $id .= "<p class='delete-p'><a class='image-delete-link btn' href='/phpmotors/uploads?action=delete&imgId=$image[imgId]&filename=$image[imgName]' title='Delete the image'>Delete $image[imgName]</a></p>";
        $id .= '</li>';
    }
        $id .= '</ul>';
        return $id;
    }
    
    

    // Build the vehicles select list
    function buildVehiclesSelect($vehicles) {
        $prodList = '<select name="invId" id="invId">';
        $prodList .= "<option>Choose a Vehicle</option>";
        foreach ($vehicles as $vehicle) {
        $prodList .= "<option value='$vehicle[invId]'>$vehicle[invMake] $vehicle[invModel]</option>";
        }
        $prodList .= '</select>';
        return $prodList;
    }


    // Handles the file upload process and returns the path
    // The file path is stored into the database
    function uploadFile($name) {
        // Gets the paths, full and local directory
        global $image_dir, $image_dir_path;
        if (isset($_FILES[$name])) {
        // Gets the actual file name
        $filename = $_FILES[$name]['name'];
        if (empty($filename)) {
        return;
        }
        // Get the file from the temp folder on the server
        $source = $_FILES[$name]['tmp_name'];
        // Sets the new path - images folder in this directory
        $target = $image_dir_path . '/' . $filename;
        // Moves the file to the target folder
        move_uploaded_file($source, $target);
        // Send file for further processing
        processImage($image_dir_path, $filename);
        // Sets the path for the image for Database storage
        $filepath = $image_dir . '/' . $filename;
        // Returns the path where the file is stored
        return $filepath;
        }
    }


    // Processes images by getting paths and 
    // creating smaller versions of the image
    function processImage($dir, $filename) {
        // Set up the variables
        $dir = $dir . '/';
    
        // Set up the image path
        $image_path = $dir . $filename;
    
        // Set up the thumbnail image path
        $image_path_tn = $dir.makeThumbnailName($filename);
    
        // Create a thumbnail image that's a maximum of 200 pixels square
        resizeImage($image_path, $image_path_tn, 200, 200);
    
        // Resize original to a maximum of 500 pixels square
        resizeImage($image_path, $image_path, 500, 500);
    }


    // Checks and Resizes image
    function resizeImage($old_image_path, $new_image_path, $max_width, $max_height) {
        
        // Get image type
        $image_info = getimagesize($old_image_path);
        $image_type = $image_info[2];
    
        // Set up the function names
        switch ($image_type) {
        case IMAGETYPE_JPEG:
        $image_from_file = 'imagecreatefromjpeg';
        $image_to_file = 'imagejpeg';
        break;
        case IMAGETYPE_GIF:
        $image_from_file = 'imagecreatefromgif';
        $image_to_file = 'imagegif';
        break;
        case IMAGETYPE_PNG:
        $image_from_file = 'imagecreatefrompng';
        $image_to_file = 'imagepng';
        break;
        default:
        return;
    } // ends the switch
    
        // Get the old image and its height and width
        $old_image = $image_from_file($old_image_path);
        $old_width = imagesx($old_image);
        $old_height = imagesy($old_image);
    
        // Calculate height and width ratios
        $width_ratio = $old_width / $max_width;
        $height_ratio = $old_height / $max_height;
    
        // If image is larger than specified ratio, create the new image
        if ($width_ratio > 1 || $height_ratio > 1) {
    
        // Calculate height and width for the new image
        $ratio = max($width_ratio, $height_ratio);
        $new_height = round($old_height / $ratio);
        $new_width = round($old_width / $ratio);
    
        // Create the new image
        $new_image = imagecreatetruecolor($new_width, $new_height);
    
        // Set transparency according to image type
        if ($image_type == IMAGETYPE_GIF) {
        $alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
        imagecolortransparent($new_image, $alpha);
        }
    
        if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        }
    
        // Copy old image to new image - this resizes the image
        $new_x = 0;
        $new_y = 0;
        $old_x = 0;
        $old_y = 0;
        imagecopyresampled($new_image, $old_image, $new_x, $new_y, $old_x, $old_y, $new_width, $new_height, $old_width, $old_height);
    
        // Write the new image to a new file
        $image_to_file($new_image, $new_image_path);
        // Free any memory associated with the new image
        imagedestroy($new_image);
        } else {
        // Write the old image to a new file
        $image_to_file($old_image, $new_image_path);
        }
        // Free any memory associated with the old image
        imagedestroy($old_image);
    } // ends resizeImage function


    function buildDetailReviewDisplay($review, $screenName){
        $dv = "<div class='single-review-div'>";
        $timestamp = $review['reviewDate'];
        $formattedDate = date('d F, Y', strtotime($timestamp));
        $dv .= "<div class='top-review-div'><strong><span class='screenName'>$screenName</span></strong> wrote on <em>$formattedDate</em>:</div>";
        $dv .= "<div class='review-text-div'><p class='review-text'>{$review['reviewText']}</p>";
        $dv .= "</div>";
        $dv .= "</div>";
        return $dv;
    }
    

    function buildAdminReviewDisplay($review, $invInfo) {
        
        $reviewId = $review['reviewId'];
        $timestamp = $review['reviewDate'];
        $formattedDate = date('d F, Y', strtotime($timestamp));
        $dv = "<li class='admin-review-li'>";
        $dv .= "{$invInfo['invMake']} {$invInfo['invModel']} ";
        $dv .= "(<em>Reviewed on {$formattedDate}</em>): ";
        $dv .= "<a class='edit-link' href='/phpmotors/reviews/index.php?action=edit&reviewId={$reviewId}'>Edit</a>";
        $dv .= " | ";
        $dv .= "<a class='edit-link' href='/phpmotors/reviews/index.php?action=confirmDelete&reviewId={$reviewId}'>Delete</a>";
        $dv .= "</li>";
        return $dv;
    }

    function buildReviewEditDisplay($review) {
        $timestamp = $review['reviewDate'];
        $formattedDate = date('d F, Y', strtotime($timestamp));
        $dv = "<div id='admin-review-edit-div'>";
        $dv .= "Reviewed on {$formattedDate}";
        $dv .= "<div id='inner-admin-edit-div'>";
        $dv .= "<form method='post' action='/phpmotors/reviews/'>";
        $reviewText = isset($review['reviewText']) ? $review['reviewText'] : '';
        $dv .= "<label for='reviewText'>Review Text</label><textarea required id='reviewText' name='reviewText' rows='5' cols='100'>{$reviewText}</textarea>";
        $dv .= "<input type='hidden' name='action' value='updateReview'>";
        $dv .= "<input type='hidden' name='reviewId' value='{$review['reviewId']}'>";
        $dv .= "<input type='submit' name='submit' class='btn' value='Update'>";
        $dv .= "</form>";
        $dv .= "</div>";
        $dv .= "</div>";
        return $dv;
    }

    function buildReviewDeleteDisplay($review) {
        $timestamp = $review['reviewDate'];
        $formattedDate = date('d F, Y', strtotime($timestamp));
        $dv = "<div id='admin-review-edit-div'>";
        $dv .= "Reviewed on {$formattedDate}";
        $dv .= "<h3 style='color: red;'>Deletes cannot be undone. Are you sure your want to delete this review?</h3>";
        $dv .= "<div id='inner-admin-edit-div'>";
        $dv .= "<form method='post' action='/phpmotors/reviews/'>";
        $reviewText = isset($review['reviewText']) ? $review['reviewText'] : '';
        $dv .= "<label for='reviewText'>Review Text</label><textarea id='reviewText' name='reviewText' rows='5' cols='100' disabled>{$reviewText}</textarea>";
        $dv .= "<input type='hidden' name='action' value='deleteReview'>";
        $dv .= "<input type='hidden' name='reviewId' value='{$review['reviewId']}'>";
        $dv .= "<input type='submit' name='submit' class='btn' value='Delete'>";
        $dv .= "</form>";
        $dv .= "</div>";
        $dv .= "</div>";
        return $dv;
    }
