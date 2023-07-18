<?php
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
    <title>PHP Motors Account Login</title>
</head>
<body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/header.php'; ?>
    <nav>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/nav.php'; ?>
    </nav>
    <main>
        <h1>PHP Motors Login</h1>
        <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
            }
        ?>
        <form id="login-form" action="/phpmotors/accounts/" method="post">
            <fieldset>
                <legend>Your Information</legend>
                    <label for="clientEmail">Email*<input id="clientEmail" type="email" name="clientEmail" placeholder="someone@gmail.com" <?php if(isset($clientEmail)){echo "value='$clientEmail'";} ?>required></label>
                    <label for="clientPassword">Password*<input id="clientPassword" type="password" name="clientPassword" placeholder="1235551234" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required></label>
                    <span>*Password must have at least 8 characters, at least 1 uppercase character, 1 number, and 1 special character</span><br>
                    <input type="hidden" name="action" value="Login">
                    <br><input type="submit" name="submit" class="btn" value="Login">
                    
                    <br><a href="../accounts/index.php?action=registration">Not a member yet?</a>
            </fieldset>
        </form>
        
        
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/footer.php'; ?>
</body>
</html>
<?php unset($_SESSION['message']); ?>