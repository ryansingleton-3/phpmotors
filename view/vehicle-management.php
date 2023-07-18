<?php
if ($_SESSION['clientData']['clientLevel'] < 2) {
 header('location: /phpmotors/');
 exit;
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
}
?>
<!DOCTYPE html>
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
        <h1>Vehicle Management</h1>
        <?php
            if (isset($message)) {
            echo $message;
            }
        ?>
        <ul>
            <li>
                <a href="../vehicles/index.php?action=add-vehicle" class="vehicle-management-li">Add Vehicle</a>
            </li>
            <li>
                <a href="../vehicles/index.php?action=add-classification" class="vehicle-management-li">Add Classification</a>
            </li>
        </ul>

        <?php
        // if (isset($message)) { 
        // echo $message; 
        // } 
        if (isset($classificationList)) { 
        echo '<h2>Vehicles By Classification</h2>'; 
        echo '<p>Choose a classification to see those vehicles</p>'; 
        echo $classificationList; 
        }
        ?>
        <noscript>
            <p><strong>JavaScript Must Be Enabled to Use this Page.</strong></p>
        </noscript>
        <table id="inventoryDisplay"></table>
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/footer.php'; ?>
    <script src="../js/inventory.js"></script>
</body>
</html>
<?php unset($_SESSION['message']); ?>