<?php
if (!$_SESSION['loggedin']){
    header("Location: ../index.php");
    exit;
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
    <?php //require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/nav.php';
     echo $navList; ?>
    </nav>
    <main>
        <h1><?php echo $_SESSION['clientData']['clientFirstname']." ".$_SESSION['clientData']['clientLastname'] ?></h1>
        <?php
            if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            }

            if ($_SESSION['loggedin']) {
                echo "<p>You are logged in.</p>";
            }
        ?>
        <ul>
            <li>First Name: <?php echo $_SESSION['clientData']['clientFirstname'] ?></li>
            <li>Last Name: <?php echo $_SESSION['clientData']['clientLastname'] ?></li>
            <li>Email: <?php echo $_SESSION['clientData']['clientEmail'] ?></li>
        </ul>
        <h2>Account Management</h2>
        <p><a href="/phpmotors/accounts/index.php?action=update&clientId=<?php echo $_SESSION['clientData']['clientId']?>">Update Account Information</a></p>
        <?php 

        if ($_SESSION['clientData']['clientLevel'] > 1) {
            echo "<h2>Inventory Management</h2><p><a href='../vehicles/'>Vehicle Management</a></p>";
        }
        ?>
        <?php 
        if (isset($adminReviewsDisplay)) {
            echo $adminReviewsDisplay;
        }
        ?>
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/footer.php'; ?>
</body>
</html>
<?php unset($_SESSION['message']); ?>