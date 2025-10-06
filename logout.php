<?php
session_start();
session_unset();
session_destroy();
setcookie("username", "", time() - 3600, "/");
setcookie("email", "", time() - 3600, "/");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Logged Out - Student Portal</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="logout-container">
    <div class="card">
      <h1>You've been logged out</h1>
      <p>Thank you for using the Student Portal.</p>
      <a href="login.html" class="btn">Log In Again</a>
    </div>
  </div>
</body>
</html>
