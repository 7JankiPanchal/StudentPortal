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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Portal - Event CRUD</title>
<style>
    body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
    header { background-color: #004080; color: white; padding: 15px 30px; }
    header h1 { margin: 0; }
    nav a { color: white; text-decoration: none; margin-right: 15px; padding: 5px 10px; border-radius: 5px; }
    nav a:hover, nav a.active { background-color: #002f66; }
    .container { max-width: 900px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    h2, h3 { color: #004080; }
    form input, form select { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
    form button { background-color: #004080; color: white; border: none; padding: 10px 15px; font-size: 16px; border-radius: 5px; cursor: pointer; }
    form button:hover { background-color: #002f66; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    th { background-color: #004080; color: white; }
    a.delete-btn { color: white; background-color: #e60000; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
    a.delete-btn:hover { background-color: #990000; }
    .message { color: green; margin-bottom: 15px; }
</style>
</head>
<body>
<header>
    <h1>Student Portal</h1>
    <nav>
        <a href="crud.php?action=insert" class="<?= $action==='insert'?'active':'' ?>">Add Event</a>
        <a href="crud.php?action=view" class="<?= $action==='view'?'active':'' ?>">View Events</a>
    </nav>
</header>

<div class="container"> 
    <h2>Event Management</h2>
    <p class="message"><?= $message ?></p>

    <?php if ($action === 'insert'): ?>
        <h3>Add Event</h3>
        <form method="post" action="crud.php?action=insert">
            <input type="text" name="event_name" placeholder="Event Name" required>
            <input type="date" name="event_date" required>
            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Open">Open</option>
                <option value="Closed">Closed</option>
            </select>
            <button type="submit">Add Event</button>
        </form>

    <?php elseif ($action === 'update' && $event): ?>
        <h3>Update Event</h3>
        <form method="post" action="crud.php?action=update&id=<?= $event['id'] ?>">
            <input type="text" name="event_name" value="<?= $event['event_name'] ?>" required>
            <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required>
            <select name="status" required>
                <option value="Open" <?= $event['status']=='Open'?'selected':'' ?>>Open</option>
                <option value="Closed" <?= $event['status']=='Closed'?'selected':'' ?>>Closed</option>
            </select>
            <button type="submit">Update Event</button>
        </form>
    <?php endif; ?>

    <?php if ($action === 'view' || $action === 'menu'): ?>
        <h3>Event List</h3>
        <table>
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
                        <a class="delete-btn" href="crud.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete this event?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>

<?php $conn->close(); ?>
</body>
</html>
