<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: main.php");
    exit();
}

function verifyUser($username, $password) {
    $lines = file("data/users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $user = explode(",", $line);
        if (count($user) >= 2 && trim($user[0]) === $username) {
            if (password_verify($password, trim($user[1]))) {
                return true;
            }
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    if (verifyUser($username, $password)) {
        $_SESSION['username'] = $username;

        header("Location: main.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
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
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    </div>
</body>
</html>
