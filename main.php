<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

function getChatMessages() {
    if (file_exists("data/chat_messages.txt")) {
        $messages = file("data/chat_messages.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $messages;
    } else {
        return array();
    }
}

function saveChatMessage($message) {
    $username = $_SESSION['username'];
    $formattedMessage = "[" . date("H:i") . "] $username: $message\n";
    file_put_contents("data/chat_messages.txt", $formattedMessage, FILE_APPEND);
}

function isIPBanned($ip) {
    $bannedIPs = file("data/banned.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($ip, $bannedIPs);
}

if (isIPBanned($_SERVER['REMOTE_ADDR'])) {
    header("Location: 404.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["message"])) {
    $message = $_POST["message"];
    saveChatMessage($message);
}

$chatMessages = getChatMessages();

if ($_SESSION['username'] === 'admin') {
    echo "<script>console.log('You are logged in as admin.');</script>";
}

if (isset($_GET['logout'])) {
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600, '/');
    }
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/image (46).png" type="image/x-icon"/>
    <title>New tab</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background: linear-gradient(140deg, rgba(116,0,120,1) 12%, rgba(9,9,121,1) 39%, rgba(0,16,255,0.758035782672444) 100%);
    margin: 0;
    padding: 0;
}

#chat-container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background: rgb(0,0,0, 0.6);
    border: 1px solid #ccc;
    border-radius: 5px;
    color: whitesmoke;
}

#chat-messages {
    height: 300px;
    overflow-y: scroll;
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
    color: #ababab;
}

#chat-messages p,h1 {
    margin: 5px 0;
    color: white;
}


form {
    margin-top: 10px;
}

textarea {
    width: calc(100% - 10px);
    padding: 5px;
    border: 1px solid #000;
    border-radius: 3px;
    color: #fff;
    background-color: #303145;
}

input[type="submit"] {
    padding: 5px 10px;
    background-color: #303145;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #5f5f87;
}

p,
a,
h1 {
    background-color: white;
}

#AP {
    padding: 5px 10px;
    background-color: #303145;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}
#AP:hover {
    background-color: #5f5f87;
}

    </style>
    <script>
        function refreshChat() {
            var chatMessages = document.getElementById('chat-messages');
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    chatMessages.innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'refresh_chat.php', true);
            xhr.send();
        }

        setInterval(refreshChat, 500);

        // Add event listener to detect Enter key press in textarea
        document.addEventListener('DOMContentLoaded', function() {
            var textarea = document.querySelector('textarea[name="message"]');
            textarea.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent default behavior of Enter key
                    document.querySelector('input[type="submit"]').click(); // Simulate click on Send button
                }
            });
        });
    </script>
</head>
<body>
<div id="chat-container">
    <h2>Chat Room</h2>
    <?php if ($_SESSION['username'] === 'admin'): ?>
    <?php endif; ?>
    <div id="chat-messages">
        <?php foreach ($chatMessages as $msg): ?>
            <?php echo htmlspecialchars($msg); ?><br>
        <?php endforeach; ?>
    </div>
    <form action="" method="post">
    <textarea name="message" rows="3" cols="50" required autofocus></textarea><br>
        <input type="submit" value="Send">
        <button id="AP" onclick="window.open('/admin.php', '_blank')">Admin Panel</button>
    </form>
</div>
</body>
</html>
