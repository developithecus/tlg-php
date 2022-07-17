<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SessionID in Use - The Letter Game</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<script type="text/javascript">
  var secsRemaining = 4;
  function redirect() {
    document.location.href = 'http://cstoica.zitec.zone/';
  }

  function countDown() {
    secsRemaining--;
    document.getElementById("redIn").innerHTML = "Redirecting in... " + secsRemaining;
  }
</script>

<body onload="setInterval(countDown, 1000); setTimeout(redirect, 4000);">
<div class="title-scene-centered">
    <h1 class="main-page-title">The Letter Game</h1>
</div>

<div class="subtitle-scene">
    <?php
        if ($_GET['failed'] == 2) {
            echo '<br>Your session ID was already in use by two players. <br>
                  We\'ll redirect you to the main page so you can start another game.';
        }
    ?>
    <h3 id="redIn">Redirecting in... 4</h3>
</div>
</body>
</html>