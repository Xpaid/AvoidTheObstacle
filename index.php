<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avoid the Obstacle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        @font-face {
            font-family: 'Bloxat';
            /* Replace with your font name */
            src: url('assets/Bloxat.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-family: 'Bloxat', sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #0f0;
            text-align: center;
            padding: 50px;
        }

        canvas {
            border: 2px solid #0f0;
            background-color: #222;
            display: block;
            margin: 0 auto;
        }

        #scoreboard {
            font-size: 1.5rem;
            margin-top: 20px;
        }

        #levelboard {
            font-size: 1.5rem;
            margin-top: 20px;
            color: #f0f;
        }

        #gameOverScreen {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        #restartButton {
            padding: 10px 20px;
            background-color: #0f0;
            color: #000;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
        }

        .container-fluid {
            display: flex;
            justify-content: space-between;
        }

        .game-container {
            flex: 0 0 60%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            height: 600px;
        }

        .game-box {
            position: relative;
            text-align: center;
        }

        h1 {
            margin-top: 200px;
            margin-bottom: 20px;
        }

        #startGameButton {
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }

        .sidebar {
            flex: 0 0 20%;
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .login-register {
            margin-bottom: 20px;
        }

        .login-register input {
            margin-bottom: 10px;
        }

        .login-register button {
            width: 100%;
        }

        .leaderboard {
            margin-bottom: 20px;
        }

        .leaderboard ul {
            list-style-type: none;
            padding-left: 0;
        }

        .leaderboard li {
            color: #0f0;
        }

        #registerForm {
            display: none;
        }
    </style>
</head>

<body>
    <?php
    include 'src/db.php';
    ?>
    <div class="container-fluid">
        <div class="sidebar">
            <?php session_start(); ?>
            <?php if (isset($_SESSION['username'])): ?>
                <p>Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>
                <form action="src/logout.php" method="post">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                </form>
            <?php else: ?>
                <p class="text-muted"><em>You are not logged in.<br>Your scores won't be recorded.</em></p>
                <div class="login-register mt-3">
                    <button id="toggleButton" class="btn btn-secondary">Toggle between Login and Register</button>
                    <hr>

                    <div class="card p-3 mt-3 bg-dark text-light border-secondary">
                        <!-- Login Form -->
                        <form id="loginForm">
                            <h4>Login</h4>
                            <input type="text" id="loginUsername" class="form-control" placeholder="Username" required><br>
                            <input type="password" id="loginPassword" class="form-control" placeholder="Password" required><br>
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>

                        <!-- Register Form -->
                        <form id="registerForm" style="display: none;">
                            <h4>Register</h4>
                            <input type="text" id="registerUsername" class="form-control" placeholder="Username" required><br>
                            <input type="password" id="registerPassword" class="form-control" placeholder="Password" required><br>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <!-- Game Container (Center) -->
        <div class="game-container">
            <div>
                <h1>Avoid The Obstacle</h1>
                <!-- Start Game Button -->
                <button id="startGameButton" class="btn btn-primary">Start Game</button>
                <canvas id="gameCanvas" width="800" height="600"></canvas>
                <div id="scoreboard" class="text-info">Score: <span id="score">0</span></div>
                <div id="levelboard">Level: <span id="level">1</span></div>

                <!-- Game Over Screen -->
                <div id="gameOverScreen">
                    <h2>Game Over!</h2>
                    <p>Your final score: <span id="finalScore">0</span></p>
                    <button id="restartButton" onclick="restartGame()">Restart</button>
                </div>
            </div>
        </div>

        <!-- Leaderboard Section (Right Side) -->
        <div class="sidebar">
            <div class="leaderboard text-warning">
                <h4>
                    <h4><i class="fas fa-crown text-warning"></i> Leaderboard <i class="fas fa-crown text-warning"></i></h4>

                    <hr>
                </h4>
                <ul>
                    <?php displayLeaderboard($conn); ?>
                </ul>
            </div>
        </div>

    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CONFIG = {
            playerSpeed: 0.05,
            smoothingFactor: 0.05,
            obstacleSpeedRange: [1, 2],
            obstacleSpawnRate: 0.01,
            deadZone: 150,
            maxObstacleSpeed: 10,
            minObstacleSpeed: 1,
            pointsPerLevel: 20
        };

        <?php
        include 'src/db.php';

        // Function to fetch and display the leaderboard
        function displayLeaderboard($conn)
        {
            // Top 20 scores
            $sql = "SELECT username, score FROM leaderboard ORDER BY score DESC LIMIT 20";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $rank = 1;

                while ($entry = $result->fetch_assoc()) {
                    $icon = '';
                    $colorClass = 'text-secondary';

                    if ($rank == 1) {
                        $icon = '<i class="bi bi-trophy-fill text-warning me-2"></i>';
                        $colorClass = 'text-danger';
                    } elseif ($rank == 2) {
                        $icon = '<i class="bi bi-trophy-fill me-2" style="color: silver;"></i>';
                        $colorClass = 'text-primary';
                    } elseif ($rank == 3) {
                        $icon = '<i class="bi bi-trophy-fill me-2" style="color: #cd7f32;"></i>';
                        $colorClass = 'text-success';
                    }

                    echo "<li class='$colorClass' style='font-size: 1.1rem; margin-bottom: 6px; display: flex; justify-content: center; align-items: center;'>
                    $icon" . htmlspecialchars($entry['username']) . " - " . $entry['score'] . "
                  </li>";

                    $rank++;
                }
            } else {
                echo "<li class='text-muted'>No leaderboard data available.</li>";
            }
        }
        ?>



        const toggleButton = document.getElementById('toggleButton');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const sidebar = document.querySelector('.sidebar'); // "Welcome" message

        // Toggle Login and Register forms
        toggleButton.addEventListener('click', () => {
            const isLoginVisible = loginForm.style.display !== 'none';

            loginForm.style.display = isLoginVisible ? 'none' : 'block';
            registerForm.style.display = isLoginVisible ? 'block' : 'none';
            toggleButton.textContent = isLoginVisible ? 'Switch to Login' : 'Switch to Register';
        });

        // Set initial form visibility when the page is loaded
        window.addEventListener('DOMContentLoaded', () => {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            toggleButton.textContent = 'Switch to Register';
        });

        // Handle Login
        document.getElementById("loginForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const username = document.getElementById("loginUsername").value;
            const password = document.getElementById("loginPassword").value;

            fetch('src/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `username=${username}&password=${password}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        // Hide the login form and display the welcome message
                        loginForm.style.display = 'none';
                        registerForm.style.display = 'none'; // Make sure register form is also hidden
                        toggleButton.style.display = 'none'; // Hide the toggle button as we no longer need it
                        sidebar.innerHTML = `<p>Welcome, <strong>${username}</strong>!</p><form action="src/logout.php" method="post"><button type="submit" class="btn btn-sm btn-outline-danger">Logout</button></form>`;
                    } else {
                        alert(data); //error
                    }
                });
        });

        // Handle Register
        document.getElementById("registerForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const username = document.getElementById("registerUsername").value;
            const password = document.getElementById("registerPassword").value;

            fetch('src/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `username=${username}&password=${password}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        // Hide the register form and show the login form
                        registerForm.style.display = 'none';
                        loginForm.style.display = 'block'; // Show the login form after registration
                        toggleButton.textContent = 'Switch to Register'; // Reset the toggle button text
                        alert('Registration successful! Please log in.');
                        window.location.reload();
                    } else {
                        alert(data); //error 
                    }
                });
        });

        function updateScore(finalScore) {
            fetch('src/update_score.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'score=' + encodeURIComponent(finalScore)
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Score update response:', data);
                })
                .catch(error => {
                    console.error('Error updating score:', error);
                });
        }


        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const scoreElement = document.getElementById('score');
        const levelElement = document.getElementById('level');
        const gameOverScreen = document.getElementById('gameOverScreen');
        const finalScoreElement = document.getElementById('finalScore');
        const restartButton = document.getElementById('restartButton');
        const startGameButton = document.getElementById('startGameButton');

        let score = 0;
        let level = 1;
        let player = {
            x: 400,
            y: 300,
            width: 20,
            height: 20
        };
        let obstacles = [];
        let gameInterval;
        let joystickData = {
            x: 0,
            y: 0
        };
        let targetX = player.x;
        let targetY = player.y;

        startGameButton.addEventListener('click', () => {
            startGameButton.style.display = 'none';
            canvas.style.display = 'block';
            startGame();
        });

        function updateJoystickData() {
            fetch('src/read_data.php?nocache=' + Date.now())
                .then(res => res.text())
                .then(data => {
                    const parts = data.split(',');
                    joystickData.x = parseInt(parts[0].split(':')[1]);
                    joystickData.y = parseInt(parts[1].split(':')[1]);

                    targetX = joystickData.x / 1023 * canvas.width;
                    targetY = joystickData.y / 1023 * canvas.height;

                    if (Math.abs(joystickData.x - 512) < CONFIG.deadZone) {
                        targetX = player.x;
                    }

                    if (Math.abs(joystickData.y - 512) < CONFIG.deadZone) {
                        targetY = player.y;
                    }

                    targetX = Math.min(Math.max(targetX, player.width / 2), canvas.width - player.width / 2);
                    targetY = Math.min(Math.max(targetY, player.height / 2), canvas.height - player.height / 2);
                })
                .catch(err => console.error("Error fetching joystick data:", err));
        }

        function generateObstacle() {
            const size = Math.random() * 40 + 20;
            const y = -size;
            const speed = Math.random() * (CONFIG.obstacleSpeedRange[1] - CONFIG.obstacleSpeedRange[0]) + CONFIG.obstacleSpeedRange[0];
            let x;

            if (Math.random() < 0.3) {
                x = player.x + (Math.random() * 100 - 50);
            } else {
                x = Math.random() * canvas.width;
            }

            x = Math.min(Math.max(x, 0), canvas.width - size);

            obstacles.push({
                x,
                y,
                size,
                speed
            });
        }

        function smoothMovement() {
            const speedX = (targetX - player.x) * CONFIG.smoothingFactor;
            const speedY = (targetY - player.y) * CONFIG.smoothingFactor;
            player.x += speedX;
            player.y += speedY;
        }

        function increaseDifficulty() {
            const difficultyFactor = Math.floor(level / 2);
            CONFIG.obstacleSpeedRange = [CONFIG.minObstacleSpeed + difficultyFactor, CONFIG.minObstacleSpeed + difficultyFactor + 2];
            if (CONFIG.obstacleSpeedRange[1] > CONFIG.maxObstacleSpeed) {
                CONFIG.obstacleSpeedRange[1] = CONFIG.maxObstacleSpeed;
            }

            const maxObstacleCount = Math.min(level, 5);
            CONFIG.obstacleSpawnRate = Math.max(0.005, 0.01 - (level * 0.001));
        }

        function gameLoop() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            smoothMovement();

            ctx.fillStyle = '#0f0';
            ctx.fillRect(player.x - player.width / 2, player.y - player.height / 2, player.width, player.height);

            obstacles.forEach((obstacle, index) => {
                obstacle.y += obstacle.speed;
                ctx.fillStyle = '#f00';
                ctx.fillRect(obstacle.x, obstacle.y, obstacle.size, obstacle.size);

                if (obstacle.y + obstacle.size > player.y - player.height / 2 &&
                    obstacle.y < player.y + player.height / 2 &&
                    obstacle.x < player.x + player.width / 2 &&
                    obstacle.x + obstacle.size > player.x - player.width / 2) {
                    endGame();
                }

                if (obstacle.y > canvas.height) {
                    obstacles.splice(index, 1);
                    score++;
                    scoreElement.textContent = score;
                }
            });

            if (Math.random() < CONFIG.obstacleSpawnRate) {
                generateObstacle();
            }

            if (score >= level * CONFIG.pointsPerLevel) {
                level++;
                levelElement.textContent = level;
                increaseDifficulty();
            }

            updateJoystickData();
        }

        function startGame() {
            gameInterval = setInterval(gameLoop, 1000 / 60);

        }

        function endGame() {
            clearInterval(gameInterval);
            finalScoreElement.textContent = score;
            gameOverScreen.style.display = 'block'; // Show Game Over screen inside the game container
            updateScore(score)
        }

        function restartGame() {
            // Reset all variables to restart the game
            score = 0;
            level = 1;
            obstacles = [];
            player = {
                x: 400,
                y: 300,
                width: 20,
                height: 20
            };
            levelElement.textContent = level;
            scoreElement.textContent = score;
            gameOverScreen.style.display = 'none'; // Hide Game Over screen
            startGame(); // Start a new game

        }
    </script>
</body>

</html>