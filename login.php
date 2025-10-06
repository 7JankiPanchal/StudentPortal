<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
    $email    = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required. <a href='login.html'>Go Back</a>");
    }

    if (strlen($password) < 6 || strlen($password) > 12) {
        die("Password length must be between 6 and 12 characters. <a href='login.html'>Go Back</a>");
    }

    if (preg_match("/^[@]/", $username)) {
        die("Username should not start with '@'. <a href='login.html'>Go Back</a>");
    }

    if (preg_match("/^[_]/", $username)) {
        die("Username should not start with '_'. <a href='login.html'>Go Back</a>");
    }

    $users = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $found = false;

    foreach ($users as $user) {
        if (preg_match('/Username:\s*(.+)\s*\|\s*Email:\s*(.+)\s*\|\s*Password:\s*(.+)/', $user, $matches)) {
            $savedUser  = trim($matches[1]);
            $savedEmail = trim($matches[2]);
            $savedPass  = trim($matches[3]);

            if ($savedUser === $username && $savedEmail === $email && $savedPass === $password) {
                $found = true;
                $_SESSION['username'] = $savedUser;
                $_SESSION['email']    = $savedEmail;

                if ($remember) {
                    setcookie("username", $savedUser, time() + (7 * 24 * 60 * 60), "/");
                    setcookie("email", $savedEmail, time() + (7 * 24 * 60 * 60), "/");
                }

                break;
            }
        }
    }

    if ($found) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p>Invalid credentials. <a href='login.html'>Try Again</a></p>";
    }
}
?>
    