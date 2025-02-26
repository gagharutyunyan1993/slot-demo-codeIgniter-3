<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8" />
	<title>Burning Love Slot Demo</title>
	<style>
		body {
			font-family: Arial, sans-serif;
		}
		.game-container {
			width: 600px;
			margin: 20px auto;
			text-align: center;
		}
		.reels {
			display: flex;
			justify-content: center;
			margin-bottom: 20px;
		}
		.reel-column {
			display: flex;
			flex-direction: column;
			margin: 0 5px;
		}
		.symbol {
			width: 80px;
			height: 80px;
			background-color: #eee;
			border: 1px solid #ccc;
			margin-bottom: 5px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: bold;
			font-size: 24px;
		}
		.win-line {
			color: red;
			font-weight: bold;
		}
		button {
			padding: 10px 20px;
			font-size: 16px;
		}
		.balance, .result-info {
			margin-bottom: 10px;
		}
		#startBtn {
			margin-bottom: 20px;
		}
	</style>
</head>
<body>
<div class="game-container">
	<h1>Burning Love (Demo Slot)</h1>

	<button id="startBtn">Начать игру</button>

	<div class="balance">
		Баланс: <span id="balance">-</span>
	</div>

	<div class="reels" id="reels"></div>

	<button id="spinBtn" disabled>SPIN (Bet 10)</button>

	<div class="result-info" id="resultInfo"></div>
</div>

<audio id="loadingAudio" src="<?= base_url('resources/sounds/loading.mp3') ?>" loop></audio>
<audio id="spinAudio" src="<?= base_url('resources/sounds/spin.mp3') ?>"></audio>
<audio id="winAudio" src="<?= base_url('resources/sounds/win.mp3') ?>"></audio>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		let sessionId = null;
		let balanceEl = document.getElementById('balance');
		let reelsEl = document.getElementById('reels');
		let resultInfoEl = document.getElementById('resultInfo');
		let spinBtn = document.getElementById('spinBtn');
		let startBtn = document.getElementById('startBtn');
		let loadingAudio = document.getElementById('loadingAudio');
		let spinAudio = document.getElementById('spinAudio');
		let winAudio = document.getElementById('winAudio');

		function initSession() {
			fetch('<?= site_url('init-session') ?>', {
				method: 'POST'
			})
				.then(res => res.json())
				.then(data => {
					if (data.status === 'success') {
						sessionId = data.session_id;
						balanceEl.textContent = data.balance;
						renderEmptyReels();
					} else {
						alert('Ошибка инициализации сессии');
					}
				})
				.catch(err => console.error(err));
		}

		startBtn.addEventListener('click', function() {
			loadingAudio.play().catch(() => {});
			spinBtn.disabled = false;
			startBtn.style.display = 'none';
			initSession();
		});

		spinBtn.addEventListener('click', () => {
			if (!sessionId) {
				alert('Сессия не инициализирована');
				return;
			}

			if (!loadingAudio.paused) {
				loadingAudio.pause();
				loadingAudio.currentTime = 0;
			}

			spinAudio.pause();
			spinAudio.currentTime = 0;
			spinAudio.play().catch(() => {});

			fetch('<?= site_url('do-spin') ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: 'session_id=' + encodeURIComponent(sessionId)
			})
				.then(res => {
					if (!res.ok) {
						return res.json().then(errData => { throw errData; });
					}
					return res.json();
				})
				.then(data => {
					balanceEl.textContent = data.new_balance;
					renderReels(data.grid);
					if (data.win_lines.length > 0) {
						let linesStr = data.win_lines.map(line => {
							return `Линия ${line.line_id + 1}, символ ${line.symbol}, x${line.count}, выигрыш ${line.payout}`;
						}).join('<br>');
						resultInfoEl.innerHTML = `
					<div>Выигрыш: <strong>${data.total_win}</strong></div>
					<div class="win-line">${linesStr}</div>
				`;

						if (data.total_win > 0) {
							winAudio.pause();
							winAudio.currentTime = 0;
							winAudio.play().catch(() => {});
						}
					} else {
						resultInfoEl.innerHTML = `
					<div>Выигрыш: 0</div>
					<div>Нет совпадений</div>
				`;
					}
				})
				.catch(err => {
					console.error(err);
					if (err.message) {
						alert(err.message);
					} else if (err.error) {
						alert(err.error);
					} else {
						alert('Ошибка спина');
					}
				});
		});

		function renderEmptyReels() {
			reelsEl.innerHTML = '';
			for (let col = 0; col < 5; col++) {
				let colDiv = document.createElement('div');
				colDiv.className = 'reel-column';
				for (let row = 0; row < 3; row++) {
					let symbolDiv = document.createElement('div');
					symbolDiv.className = 'symbol';
					symbolDiv.innerText = '?';
					colDiv.appendChild(symbolDiv);
				}
				reelsEl.appendChild(colDiv);
			}
			resultInfoEl.innerHTML = '';
		}

		function renderReels(grid) {
			reelsEl.innerHTML = '';
			for (let col = 0; col < 5; col++) {
				let colDiv = document.createElement('div');
				colDiv.className = 'reel-column';
				for (let row = 0; row < 3; row++) {
					let symbol = grid[col][row];
					let symbolDiv = document.createElement('div');
					symbolDiv.className = 'symbol';

					let img = document.createElement('img');
					img.src = '<?= base_url('resources/images/') ?>' + symbol + '.png';
					img.alt = symbol;
					symbolDiv.appendChild(img);

					colDiv.appendChild(symbolDiv);
				}
				reelsEl.appendChild(colDiv);
			}
		}
	});
</script>
</body>
</html>
