function validate(form){
var userName = document.getElementById("email");
var password = FormData.password.value;
var FirstCharStr1 = /^@[A-Za-z]/;
var FirstCharStr2 =  /^_[A-Za-z]/;
var FirstCharStr3 = /^[0-9]/;
if(userName.length===0){
    alert("You must enter a username.");
    return false;
}
if(password.length===0){
    alert("You must enter a password.");
    return false;
}
if(password.length<6 || password.length>12){
    alert("Length of Password must be between 6 to 12 characters");
    return false;
}
if((FirstCharStr1.test(userName))){
    alert("You must enter the valid user name");
    return false;
}
if((FirstCharStr2.test(userName))){
    alert("You must enter the valid user name");
    return false;
}
if((FirstCharStr3.test(userName))){
    alert("You must enter the valid user name");
    return false;
}
return true;
}


// <!DOCTYPE html>
// <html>
// <head>
//   <title>JS Form Validation with Cookies & Session</title>
//   <script>
//     // Validate form input
//     function validateForm(event) {
//       event.preventDefault(); // stop form submission
      
//       let username = document.getElementById("username").value;
//       let password = document.getElementById("password").value;

//       if (username === "" || password === "") {
//         alert("All fields are required!");
//         return false;
//       }

//       // Example: Hardcoded validation
//       if (username === "admin" && password === "12345") {
//         alert("Login successful!");

//         // Set cookie (expires in 1 hour)
//         document.cookie = "username=" + username + "; max-age=" + 3600 + "; path=/";

//         // Set sessionStorage (clears when tab is closed)
//         sessionStorage.setItem("user", username);

//         // Redirect to welcome page (simulate)
//         window.location.href = "welcome.html";
//       } else {
//         alert("Invalid username or password!");
//         return false;
//       }
//     }

//     // Helper: Get cookie by name
//     function getCookie(name) {
//       let cookies = document.cookie.split(";");
//       for (let c of cookies) {
//         let [key, value] = c.trim().split("=");
//         if (key === name) return value;
//       }
//       return "";
//     }
//   </script>
// </head>
// <body>
//   <h2>Login Form</h2>
//   <form onsubmit="validateForm(event)">
//     Username: <input type="text" id="username"><br><br>
//     Password: <input type="password" id="password"><br><br>
//     <input type="submit" value="Login">
//   </form>
// </body>
// </html>
<?php
$servername = "localhost";  
$dbusername = "root";                 
$dbpassword = "";  
$dbname = "student_db"; 

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Guest_name = $_POST['Guest_name'];
    $Contact = $_POST['Contact'];
    $Room_Type = $_POST['Room_Type'];
    $Check_in_Date = $_POST['check-in'];

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO guests (Guest_name, Contact) VALUES (?, ?)");
    $stmt->bind_param("ss", $Guest_name, $Contact); // "ss" means two strings
    if ($stmt->execute()) {
        // After guest is inserted, get the last inserted guest_id
        $guest_id = $conn->insert_id;

        // Now, insert into the Bookings table using the guest_id
        $stmt_booking = $conn->prepare("INSERT INTO Bookings (guest_id, room_type, check_in, status) VALUES (?, ?, ?, ?)");
        $status = 'Booked';  // default status
        $stmt_booking->bind_param("isss", $guest_id, $Room_Type, $Check_in_Date, $status); // "isss" means integer, string, string, string
        if ($stmt_booking->execute()) {
            echo "Registration and Booking successful!";
        } else {
            echo "Error booking: " . $stmt_booking->error;
        }

    } else {
        echo "Error registering guest: " . $stmt->error;
    }
    
    // Close the prepared statements
    $stmt->close();
    $stmt_booking->close();
}

$conn->close();
?>
INSERT INTO Bookings (guest_id, room_type, check_in, status) 
VALUES (
    (SELECT guest_id FROM Guests WHERE name = 'Ravi Patel' AND contact = '9876543210'),
    'Deluxe', '2025-09-20', 'Booked'
);
