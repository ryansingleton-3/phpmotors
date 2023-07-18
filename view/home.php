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
    <main id="main-home">
        <h1>Welcome to PHP Motors!</h1>
        <section id="hero-section">
            <div id="hero-text-div">
                <h2>DMC Delorean</h2>
                <p>3 Cup Holders</p>
                <p>Superman Doors</p>
                <p>Fuzzy Dice!</p>
            </div>
            <img src="/phpmotors/images/vehicles/delorean.jpg" alt="delorean car" id="delorean-main-img">
            <div id="own-today">
                <img src="/phpmotors/images/site/own_today.png" alt="own today button" >
            </div>
            
        </section>
        <section id="reviews-section">
            <h2>DMC Delorean Reviews</h2>
            <ul>
                <li>"So fast its almost like traveling in time." [4/5]</li>
                <li>"Coolest ride on the road." [4/5]</li>
                <li>"I'm feeling Mary McFly!" [5/5]</li>
                <li>"The most futuristic ride of our day." [4.5/5]</li>
                <li>"80's living and I love it!." [5/5]</li>
            </ul>

        </section>
        <section id="upgrades-section">
            <h2>Delorean Upgrades</h2>
            <div id="upgrades">
                <div class="upgrade">
                    <div class="upgrade-bg">
                        <img src="/phpmotors/images/upgrades/flux-cap.png" alt="flux capacitor" >
                    </div>
                    <p>Flux Capacitor</p>
                </div>
                <div class="upgrade">
                    <div class="upgrade-bg">
                        <img src="/phpmotors/images/upgrades/flame.jpg"  alt="flame">
                    </div>
                    <p>Flame Decals</p>
                </div>
                <div class="upgrade">
                    <div class="upgrade-bg">
                        <img src="/phpmotors/images/upgrades/bumper_sticker.jpg" alt="bumper sticker that says hello world" >
                    </div>
                    <p>Bumper Stickers</p>
                </div>
                <div class="upgrade">
                    <div class="upgrade-bg">
                        <img src="/phpmotors/images/upgrades/hub-cap.jpg" alt="hub cap" >
                    </div>
                    <p>Hub Caps</p>
                </div>
            </div>
        </section>
    </main>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/phpmotors/snippets/footer.php'; ?>
</body>
</html>