<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New tab</title>
  <script>
        function replaceHistoryState() {
    window.history.replaceState('', '', '/');
  }

  // Call the function when the page loads
  window.onload = function() {
    replaceHistoryState();
  };
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <p>Uh oh, either you found a wack error, or you're banned! Please leave the website.</p>
    <p>Please check the URL you entered for any mistakes, or go to the <a href="index.php">homepage</a>.</p>
</body>
</html>
