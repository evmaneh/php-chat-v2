<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: main.php");
    exit();
}

function clearChatMessages() {
    file_put_contents("data/chat_messages.txt", "");
    echo "<script>alert('Chat messages cleared successfully.');</script>";
}

function updateChatMessage($index, $message) {
    $messages = getChatMessages();
    $messages[$index] = $message;
    file_put_contents("data/chat_messages.txt", implode("\n", $messages));
}

function banIPAddress($ip) {
    file_put_contents("data/banned.txt", $ip . PHP_EOL, FILE_APPEND);
}

function unbanIPAddress($ip) {
    $bannedIPs = file("data/banned.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $bannedIPs = array_diff($bannedIPs, array($ip));
    file_put_contents("data/banned.txt", implode("\n", $bannedIPs));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_chat'])) {
    clearChatMessages();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_message'])) {
    $index = $_POST['index'];
    $updatedMessage = $_POST['updated_message'];
    updateChatMessage($index, $updatedMessage);
    echo "<script>alert('Message updated successfully.');</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ban_ip'])) {
    $ip = $_POST['ip'];
    banIPAddress($ip);
    echo "<script>alert('IP address banned successfully.');</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unban_ip'])) {
    $ip = $_POST['ip'];
    unbanIPAddress($ip);
    echo "<script>alert('IP address unbanned successfully.');</script>";
}

function getChatMessages() {
    if (file_exists("data/chat_messages.txt")) {
        $messages = file("data/chat_messages.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $messages;
    } else {
        return array();
    }
}

function getBannedIPs() {
    if (file_exists("data/banned.txt")) {
        $bannedIPs = file("data/banned.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $bannedIPs;
    } else {
        return array();
    }
}

$chatMessages = getChatMessages();
$bannedIPs = getBannedIPs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script>
        function replaceHistoryState() {
    window.history.replaceState('', '', '/');
  }

  // Call the function when the page loads
  window.onload = function() {
    replaceHistoryState();
  };
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script>
        function disableClearChatButton() {
            var clearChatButton = document.getElementById('clear-chat-button');
            clearChatButton.disabled = true;
            setTimeout(function() {
                clearChatButton.disabled = false;
            }, 500); // 0.5 seconds delay
        }
        
        window.onload = function() {
            disableClearChatButton();
        };
        
        function refreshChatMessages() {
            var chatMessages = document.getElementById('chat-messages');
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    chatMessages.innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'get_chat_messages.php', true);
            xhr.send();
        }

        setInterval(refreshChatMessages, 500);
    </script>
</head>
<body>
    <h1>Welcome, Admin!</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input id="clear-chat-button" type="submit" name="clear_chat" value="Clear Chat Messages">
    </form>

    <h2>Edit Chat Messages:</h2>
    <ul id="chat-messages">
        <?php foreach ($chatMessages as $index => $msg): ?>
            <li>
                <?php echo htmlspecialchars($msg); ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <input type="text" name="updated_message" value="<?php echo htmlspecialchars($msg); ?>">
                    <input type="submit" name="update_message" value="Update">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Ban IP Address:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="ip" placeholder="Enter IPv4 Address">
        <input type="submit" name="ban_ip" value="Ban IP">
    </form>

    <h2>Currently Banned IPs:</h2>
    <ul>
        <?php foreach ($bannedIPs as $ip): ?>
            <li>
                <?php echo htmlspecialchars($ip); ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="ip" value="<?php echo $ip; ?>">
                    <input type="submit" name="unban_ip" value="Remove">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
