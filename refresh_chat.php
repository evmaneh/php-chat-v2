<?php
function getChatMessages() {
    if (file_exists("data/chat_messages.txt")) {
        $messages = file("data/chat_messages.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $messages;
    } else {
        return array();
    }
}

$chatMessages = getChatMessages();

// Output chat messages
foreach ($chatMessages as $msg) {
    echo htmlspecialchars($msg) . '<br>';
}
?>
