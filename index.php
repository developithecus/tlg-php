<?php
    $gameSession = rand(-10000, 10000) . $_SERVER['REMOTE_ADDR'] . time();
    $hash = hash('sha256', $gameSession);
    $user = isset($_GET['u']) ? $_GET['u']:"anon" . rand(0, 10000);
?>
<html>
    <head>
        <title>The Letter Game</title>
        <link rel="stylesheet" type="text/css" href="css/home.css">
    </head>

    <body>
        <div class="title-scene-centered">
            <h1 class="main-page-title">The Letter Game</h1>
        </div>

        <div class="subtitle-scene">
            <h1 class="subtitle-text">Hello, <?= $user ?>!</h1>
            <p>Choose another name:</p>
        </div>

        <form action="game.php" method="get">
            <input type="text" id="name-input" name="u" value="<?= $user ?>">
            <input id="hashBox" class="hash-textbox-centered" type="text" name="sessionID" value="<?= $hash?>" onfocus="this.select();">
            <input type="submit" class="button-play-centered-big" value="Play">
        </form>

        <label for="hashBox">
            How to play?<br>
            1.a. Host: Give the <b>hash</b> above to your friend. <br>
            1.b. Guest: Copy your friend's <b>hash</b> in the box above <br>
            2. Hit play :)
        </label>
    </body>
</html>