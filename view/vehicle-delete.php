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
    } elseif(isset($invInfo['classificationId'])){
        if($classification['classificationId'] === $invInfo['classificationId']){
         $classificationList .= ' selected ';
        }
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
    <title><?php if(isset($invInfo['invMake']) && isset($invInfo['invModel'])){ 
		echo "Delete $invInfo[invMake] $invInfo[invModel]";} 
	elseif(isset($invMake) && isset($invModel)) { 
		echo "Modify $invMake $invModel"; }?> | PHP Motors</title>
</head>
<body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/header.php'; ?>
    <nav>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/nav.php'; ?>
    </nav>
    <main>
        <h1><?php if(isset($invInfo['invMake'])){ 
        echo "Delete $invInfo[invMake] $invInfo[invModel]";} ?>
        </h1>
        <p>Confirm Vehicle Deletion. The delete is permanent.</p>
        <?php
            if (isset($message)) {
            echo $message;
            }
        ?>
        <h2>*Note all fields are required</h2>
        <form id="modify-vehicle" action="/phpmotors/vehicles/index.php?action=modify-vehicle" method="POST">
            <fieldset>
                <legend>Vehicle Information</legend>
                    <?php
                        echo $classificationList;
                    ?>
                    <label for='invMake'>Make*</label><input type='text' name='invMake' readonly id='invMake' placeholder='Chevrolet' <?php if(isset($invInfo['invMake'])) {echo "value='$invInfo[invMake]'"; }?>><br>
                    <label for='invModel'>Model*</label><input type='text' name='invModel' readonly id='invModel' placeholder='Camaro' <?php if(isset($invInfo['invModel'])) {echo "value='$invInfo[invModel]'"; }?>><br>
                    <label for='invDescription'>Vehicle Description*</label><textarea readonly id='invDescription' name='invDescription' ><?php if(isset($invInfo['invDescription'])) {echo $invInfo['invDescription']; }?></textarea>
                    <input type='hidden' name='action' value='deleteVehicle'>
                    <input type="hidden" name="invId" value="<?php if(isset($invInfo['invId'])){echo $invInfo['invId'];} ?>">
                    <input class="btn" type='submit' name='submit' value='Delete'>
            </fieldset>
        </form>
        
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/footer.php'; ?>
</body>
</html>