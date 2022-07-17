<html>
<head>
    <title>The Letter Game</title>
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/matchpage.css">
</head>

<body>
<div class="title-scene-centered">
    <div id="turn-number">

    </div>
</div>

<div id="word-history" class="history-botright">
    <table id="history-grid">
        <thead>
        <tr>
            <th class="history-header" id="history-turn">Turn #</th>
            <th class="history-header" id="history-ally">Ally Word</th>
            <th class="history-header" id="history-enemy">Enemy Word</th>
        </tr>
        </thead>
    </table>
</div>

<div id="command-panel">
</div>

<div class="game-console">

</div>

<input type="text" id="word-input" name="word" class="main-text-input-centered" placeholder="Write your word here">

<div id="ally-data" class="player-data-bottom">
    <div id="ally-word" class="player-word-bottom-centered">
    </div>
    <div style="position: absolute; bottom: 0; width: 100%; height 50%;">
        <div id="ally-name"><?= $_GET['u']?></div>
        <div id="ally-healthbar-container"><input type="text" id="ally-healthbar" disabled="true" value="100"></div>
    </div>
</div>

<div id="enemy-data" class="enemy-data-top">
    <div id="enemy-healthbar-container"><input type="text" id="enemy-healthbar" disabled="true" value="100"></div>
    <div id="enemy-name">Waiting for opponent...</div>
    <div id="enemy-word" class="enemy-word-top-centered">
    </div>
</div>

<script>var sessionID = '<?= $_GET['sessionID']?>'</script>
<script src="scripts/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="scripts/wsconn.js"></script>
<script type="text/javascript" src="scripts/wordpplink.js"></script>
</body>
</html>