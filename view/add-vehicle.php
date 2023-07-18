<?php
if ($_SESSION['clientData']['clientLevel'] < 2) {
 header('location: /phpmotors/');
 exit;
}
?>
<?php
$classificationList = "<label for='classificationId'>Classifications:</label><br>"."<select id='classificationId' name='classificationId' ><option>Choose Car Classification</option>";

// Build a navigation bar using the $classifications array
foreach ($classifications as $classification) {
    $classificationList .= "<option value='{$classification['classificationId']}'";
    if (isset($classificationId) && $classificationId == $classification['classificationId']) {
        $classificationList .= " selected";
    }
    $classificationList .= ">{$classification['classificationName']}</option>";
}
$classificationList .= '</select>';
$classificationList .= "<br>";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/phpmotors/css/style.css" type="text/css" rel="stylesheet" media="screen">
    <link href="/phpmotors/css/large.css" type="text/css" rel="stylesheet" media="screen">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300&display=swap" rel="stylesheet">
    <title>PHP Motors</title>
</head>
<body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/header.php'; ?>
    <nav>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/nav.php'; ?>
    </nav>
    <main>
        <h1>Add Vehicle</h1>
        <?php
            if (isset($message)) {
            echo $message;
            }
        ?>
        <h2>*Note all fields are required</h2>
        <form id="create-vehicle" action="/phpmotors/vehicles/index.php?action=create-vehicle" method="POST">
            <fieldset>
                <legend>Vehicle Information</legend>
                    <?php
                        echo $classificationList;
                    ?>
                    <label for='invMake'>Make*</label><input type='text' name='invMake' id='invMake' placeholder='Chevrolet' <?php if(isset($invMake)){echo "value='$invMake'";} ?> required><br>
                    <label for='invModel'>Model*</label><input type='text' name='invModel' id='invModel' placeholder='Camaro' <?php if(isset($invModel)){echo "value='$invModel'";} ?> required><br>
                    <label for='invDescription'>Vehicle Description*</label><textarea id='invDescription' name='invDescription' required><?php if(isset($invDescription)){echo "$invDescription";} ?></textarea>
                    <label for='invImage'>Image Path*</label><input type='text' name='invImage' id='invImage' placeholder='/phpmotors/images/img.jpeg' <?php if(isset($invImage)){echo "value='$invImage'";} ?> required>
                    <label for='invThumbnail'>Thumbnail Path*</label><input type='text' name='invThumbnail' id='invThumbnail' placeholder='/phpmotors/images/img.jpeg' <?php if(isset($invThumbnail)){echo "value='$invThumbnail'";} ?> required>
                    <label for='invPrice'>Price*</label><input type='number' name='invPrice' id='invPrice' placeholder='49,999' <?php if(isset($invPrice)){echo "value='$invPrice'";} ?> required>
                    <label for='invStock'>Stock*</label><input type='number' name='invStock' id='invStock' placeholder='1' <?php if(isset($invStock)){echo "value='$invStock'";} ?> required>
                    <label for='invColor'>Color*</label><input type='text' name='invColor' id='invColor' placeholder='Red' <?php if(isset($invColor)){echo "value='$invColor'";} ?> required>
                    <input type='hidden' name='action' value='create-vehicle'>
                    <input type='submit' name='submit' class='btn' value='Create'>
            </fieldset>
        </form>
        
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/footer.php'; ?>
</body>
</html>