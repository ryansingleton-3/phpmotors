<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" type="text/css" rel="stylesheet" media="screen">
    <link href="../css/large.css" type="text/css" rel="stylesheet" media="screen">
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
        <h1>Registration</h1>
        <?php
            if (isset($message)) {
            echo $message;
            }
        ?>
        <form id="registration-form" method="post" action="/phpmotors/accounts/index.php">
            <fieldset>
                <legend>Your Information</legend>
                    <label for="clientFirstName">First Name*<input type="text" name="clientFirstname" id="clientFirstName" <?php if(isset($clientFirstname)){echo "value='$clientFirstname'";} ?> required></label>
                    <label for="clientLastName">Last Name*<input type="text" name="clientLastname" id="clientLastname" <?php if(isset($clientLastname)){echo "value='$clientLastname'";} ?> required></label>
                    <label for="clientEmail">Email*<input type="email" name="clientEmail" id="clientEmail" placeholder="someone@gmail.com" <?php if(isset($clientEmail)){echo "value='$clientEmail'";} ?> required></label>
                    <label for="clientPassword">Password*<input type="password" name="clientPassword" id="clientEmail" placeholder="1235551234" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required></label>
                    <span>*Password must have at least 8 characters, at least 1 uppercase character, 1 number, and 1 special character</span><br>
                    <br><input type="submit" name="submit" class="btn" value="Register">
                    <input type="hidden" name="action" value="register">
            </fieldset>
        </form>
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/footer.php'; ?>
</body>
</html>