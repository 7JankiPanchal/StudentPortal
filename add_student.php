<?php
include 'database1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $marks = $_POST['marks'];

    $sql = "INSERT INTO students (student_id, name, email, marks) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $student_id, $name, $email, $marks);

    if ($stmt->execute()) {
        echo "<h3>✅ Student added successfully!</h3>";
    } else {
        echo "<h3>❌ Error: " . $stmt->error . "</h3>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
</head>
<body>
    <h2>Add New Student</h2>
    <form method="post" action="">
        Student ID: <input type="number" name="student_id" required><br><br>
        Name: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Marks: <input type="number" name="marks" required><br><br>
        <button type="submit">Add Student</button>
    </form>
    <p><a href="view_students.php">View All Students</a></p>
</body>
</html>
