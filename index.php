<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: main.php");
    exit();
}

function getIPAddress() {
    return $_SERVER['REMOTE_ADDR'];
}

function validateIPv4($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}

function isDuplicateIP($ip) {
    $lines = file("data/users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $user = explode(",", $line);
        if (count($user) >= 3 && trim($user[2]) === $ip) {
            return true;
        }
    }
    return false;
}

function isDuplicateUsername($username) {
    $lines = file("data/users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $user = explode(",", $line);
        if (count($user) >= 1 && trim($user[0]) === $username) {
            return true;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    
    $ipAddress = getIPAddress();

    if (!validateIPv4($ipAddress)) {
        echo "Invalid IP address.";
        exit();
    }

    if (isDuplicateIP($ipAddress)) {
        echo "<script>alert('Duplicate IP address detected.');</script>";
        exit();
    }

    if (isDuplicateUsername($username)) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";
        exit();
    }

    // Secure passwords from attacks
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $data = "$username,$hashedPassword,$ipAddress\n";
    file_put_contents("data/users.txt", $data, FILE_APPEND);

    $_SESSION['username'] = $username;

    header("Location: main.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script>
        function replaceHistoryState() {
    window.history.replaceState('', '', '/');
  }

  window.onload = function() {
    replaceHistoryState();
  };
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>New tab</title>
</head>
<body>
    <div class="ca-window">
    <h2>Create Account</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Submit">
    </form>
    </div>
</body>
</html>
