<?php
include 'database.php'; // connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];

    if ($password === $confirm_password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<h2> Registration Successful!</h2>";
            echo "<p><a href='login.html'>Go to Login Page</a></p>";
        } else {
            echo "<h3> Error: " . $stmt->error . "</h3>";
            echo "<p><a href='register.html'>Back to Register</a></p>";
        }

        $stmt->close();
    } else {
        echo "<h3>⚠️ Passwords do not match. Please try again.</h3>";
        echo "<p><a href='register.html'>Back to Register</a></p>";
    }

    $conn->close();
}
?>
