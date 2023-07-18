<header>
    <div>
        <img src="/phpmotors/images/site/logo.png" alt="php motors logo">
    </div>
    <div>
        <?php
        if (isset($_SESSION['loggedin'])) {
            if (isset($_SESSION['clientData']['clientFirstname'])) {
                echo "<span id='cookie'><a href='/phpmotors/accounts/index.php?action=admin'>Welcome, ".$_SESSION['clientData']['clientFirstname']."</a></span>";
                echo "<div id='logout'><a href='/phpmotors/accounts/index.php?action=logout'>Logout</a></div>";
            }
        } else echo "<div id='my-account'><a href='/phpmotors/accounts/index.php?action=login'>My Account</a></div>";
        ?>
    </div>
    
</header>

