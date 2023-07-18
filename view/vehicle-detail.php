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
    <?php require $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?>
    <nav>
        <?php //require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/nav.php';
        echo $navList; ?>
    </nav>
    <main>
        <h1><?php echo "{$vehicle['invMake']} {$vehicle['invModel']}"; ?></h1>
        <?php
        if (isset($detailDisplay)) echo $detailDisplay;
        ?>
        <h3 class="review-heading">Customer Reviews </h3>
        <?php
        if (isset($_SESSION['message'])) echo $_SESSION['message'];
        if (isset($_SESSION['loggedin'])) {
            echo "<form action='/phpmotors/reviews/index.php' method='post'>
                <label for='screenName'>Screen Name</label><input id='screenName' name='screenName' type='text' value='{$_SESSION['screenName']}' readonly style='background-color: #D3D3D3;'>
                <label for='reviewText'>Review*</label><textarea id='reviewText' name='reviewText' required></textarea>
                <input type='hidden' name='action' value='newReview'>
                <input type='hidden' name='invId' value='{$vehicle['invId']}'>
                <input type='hidden' name='clientId' value='{$_SESSION['clientData']['clientId']}'>
                <input type='submit' class='btn' value='Leave a review'>
            </form>";
        }

        else {
            echo "Please <a href='../accounts/index.php?action=login'>login</a> to leave a review";
        }
            
        ?>
        <?php
        if (isset($reviewDisplay)) {
            echo $reviewDisplay;
        } else {
            echo "<p id='first-review'>Be the first to leave a review.</p>";
        }
        ?>
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?>
</body>

</html>
<?php unset($_SESSION['message']); ?>