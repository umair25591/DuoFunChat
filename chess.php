<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="./source/css/chess.css">
</head>
<body onload="setup();">
<h1>D3VIL CHESS</h1>
<p id="turnInfo">Player's turn: <b>White</b></p>
<section id="board">
    <div class="overlay" id="promotionMessage">
        <div class="overlay-inner">
            <span class="overlay-title">Promotion!</span>
            <p class="overlay-text" id="alertText"></p>
            <ul id="promotionList" class="white">
                <li><a href="#" class="promotion-button queen" onclick="promote('queen');"></a></li>
                <li><a href="#" class="promotion-button castle" onclick="promote('castle');"></a></li>
                <li><a href="#" class="promotion-button bishop" onclick="promote('bishop');"></a></li>
                <li><a href="#" class="promotion-button knight" onclick="promote('knight');"></a></li>
            </ul>
        </div>
    </div>
	<div class="overlay" id="errorMessage">
		<div class="overlay-inner">
			<span class="overlay-title">Error!</span>
			<p class="overlay-text" id="errorText"></p>
			<a href="#" class="overlay-button" onclick="closeError();">Close</a>
		</div>
	</div>
	<div class="overlay" id="endMessage">
		<div class="overlay-inner">
			<span class="overlay-title">Game Over!</span>
			<p class="overlay-text" id="endText"></p>
			<a href="#" class="overlay-button" onclick="newGame();">New Game</a>
		</div>
	</div>
</section>

<button id="start_game">connect game</button>
<button id="end_game">end game</button>
</body>
</html>
<script src="./source/js/chess.js"></script>
