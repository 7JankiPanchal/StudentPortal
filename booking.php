<?php
// Hardened booking handler extracted from script.js
// Uses prepared statements and safer error handling

// Enable mysqli exceptions for cleaner error management
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

header('Content-Type: application/json');

$response = [
  'ok' => false,
  'message' => 'Unknown error',
];

try {
    $servername = 'localhost';
    $dbusername = 'root';
    $dbpassword = '';
    $dbname     = 'student_db';

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    $conn->set_charset('utf8mb4');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new RuntimeException('Method Not Allowed');
    }

    // Basic input retrieval and trimming
    $guestName    = isset($_POST['Guest_name']) ? trim((string)$_POST['Guest_name']) : '';
    $contact      = isset($_POST['Contact']) ? trim((string)$_POST['Contact']) : '';
    $roomType     = isset($_POST['Room_Type']) ? trim((string)$_POST['Room_Type']) : '';
    $checkInDate  = isset($_POST['check-in']) ? trim((string)$_POST['check-in']) : '';

    if ($guestName === '' || $contact === '' || $roomType === '' || $checkInDate === '') {
        http_response_code(400);
        throw new InvalidArgumentException('All fields are required');
    }

    // (Optional) basic format validations
    if (!preg_match('/^[\p{L} .\'-]{2,}$/u', $guestName)) {
        http_response_code(400);
        throw new InvalidArgumentException('Invalid guest name');
    }
    if (!preg_match('/^[0-9+()\- ]{7,}$/', $contact)) {
        http_response_code(400);
        throw new InvalidArgumentException('Invalid contact');
    }
    // Simple date format check: YYYY-MM-DD or YYYY/MM/DD
    if (!preg_match('/^\d{4}[-\/]\d{2}[-\/]\d{2}$/', $checkInDate)) {
        http_response_code(400);
        throw new InvalidArgumentException('Invalid check-in date');
    }

    // 1) Insert guest
    $stmt = $conn->prepare('INSERT INTO guests (Guest_name, Contact) VALUES (?, ?)');
    $stmt->bind_param('ss', $guestName, $contact);
    $stmt->execute();

    $guestId = $conn->insert_id;

    // 2) Insert booking
    $status = 'Booked';
    $stmtBooking = $conn->prepare('INSERT INTO Bookings (guest_id, room_type, check_in, status) VALUES (?, ?, ?, ?)');
    $stmtBooking->bind_param('isss', $guestId, $roomType, $checkInDate, $status);
    $stmtBooking->execute();

    $response['ok'] = true;
    $response['message'] = 'Registration and booking successful';
    $response['guest_id'] = $guestId;

    // Close resources
    $stmtBooking->close();
    $stmt->close();
    $conn->close();

    echo json_encode($response);
    exit;
} catch (Throwable $e) {
    // Log server-side, but return generic message to client
    error_log('booking.php error: ' . $e->getMessage());
    if (!headers_sent()) {
        http_response_code(http_response_code() >= 400 ? http_response_code() : 500);
    }
    $response['ok'] = false;
    $response['message'] = 'Request failed';
    echo json_encode($response);
    exit;
}
