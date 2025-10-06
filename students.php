<?php
include "database.php";

$action = isset($_GET['action']) ? $_GET['action'] : 'menu';
$message = "";

// Insert
if ($action === 'insert' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = $_POST['event_name'];
    $date   = $_POST['event_date'];
    $status = $_POST['status'];

    if ($conn->query("INSERT INTO events (event_name, event_date, status) VALUES ('$name','$date','$status')")) {
        $message = "Event added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Update
if ($action === 'update' && isset($_GET['id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id     = intval($_GET['id']);
    $name   = $_POST['event_name'];
    $date   = $_POST['event_date'];
    $status = $_POST['status'];

    if ($conn->query("UPDATE events SET event_name='$name', event_date='$date', status='$status' WHERE id=$id")) {
        $message = "Event updated successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($conn->query("DELETE FROM events WHERE id=$id")) {
        $message = "Event deleted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch single event for update form
$event = null;
if ($action === 'update' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM events WHERE id=$id");
    $event = $res->fetch_assoc();
}

// Fetch all events
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<h2>Simple Event CRUD</h2>
<p style="color:green;"><?= $message ?></p>

<h3>Operations Menu</h3>
<a href="crud.php?action=insert">Add Event</a> | 
<a href="crud.php?action=view">View Events</a>

<?php if ($action === 'insert'): ?>
    <h3>Add Event</h3>
    <form method="post" action="crud.php?action=insert">
        <input type="text" name="event_name" placeholder="Event Name" required><br>
        <input type="date" name="event_date" required><br>
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Open">Open</option>
            <option value="Closed">Closed</option>
        </select><br>
        <button type="submit">Add Event</button>
    </form>

<?php elseif ($action === 'update' && $event): ?>
    <h3>Update Event</h3>
    <form method="post" action="crud.php?action=update&id=<?= $event['id'] ?>">
        <input type="text" name="event_name" value="<?= $event['event_name'] ?>" required><br>
        <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required><br>
        <select name="status" required>
            <option value="Open" <?= $event['status']=='Open'?'selected':'' ?>>Open</option>
            <option value="Closed" <?= $event['status']=='Closed'?'selected':'' ?>>Closed</option>
        </select><br>
        <button type="submit">Update Event</button>
    </form>
<?php endif; ?>

<?php if ($action === 'view' || $action === 'menu'): ?>
    <h3>Event List</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th><th>Name</th><th>Date</th><th>Status</th><th>Actions</th>
        </tr>
        <?php while($row = $events->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['event_name'] ?></td>
            <td><?= $row['event_date'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <a href="crud.php?action=update&id=<?= $row['id'] ?>">Update</a> | 
                <a href="crud.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete this event?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<?php $conn->close(); ?>
-- Create the Guests table
CREATE TABLE Guests (
    guest_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact VARCHAR(15) NOT NULL
);

-- Create the Bookings table
CREATE TABLE Bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    guest_id INT NOT NULL,
    room_type ENUM('Standard', 'Deluxe', 'Suite') NOT NULL,
    check_in DATE NOT NULL,
    status ENUM('Booked', 'Cancelled') DEFAULT 'Booked',
    FOREIGN KEY (guest_id) REFERENCES Guests(guest_id)
);
-- Insert a new guest
INSERT INTO Guests (name, contact) 
VALUES ('Ravi Patel', '9876543210');

-- Insert a booking for Ravi Patel
INSERT INTO Bookings (guest_id, room_type, check_in, status) 
VALUES (
    (SELECT guest_id FROM Guests WHERE name = 'Ravi Patel' AND contact = '9876543210'),
    'Deluxe', '2025-09-20', 'Booked'
);

