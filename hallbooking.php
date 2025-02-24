<?php
// book_hall.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch hall availability
$stmt = $pdo->query("SELECT * FROM halls");
$halls = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hall_name = $_POST['hall_name'];
    $event_type = $_POST['event_type'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    // Validate booking date (must not be in the past)
    $current_date = date('Y-m-d'); // Get today's date in YYYY-MM-DD format
    if ($booking_date < $current_date) {
        echo "<script>alert('Booking date cannot be in the past. Please select a valid date.');</script>";
    } else {
        // Check hall availability
        $stmt = $pdo->prepare("SELECT * FROM halls WHERE hall_name = ?");
        $stmt->execute([$hall_name]);
        $hall = $stmt->fetch();

        if ($hall['total_halls'] > $hall['booked_halls']) {
            // Book the hall
            $stmt = $pdo->prepare("INSERT INTO hall_bookings (user_id, hall_name, event_type, booking_date, booking_time) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $hall_name, $event_type, $booking_date, $booking_time]);

            // Update booked_halls count
            $stmt = $pdo->prepare("UPDATE halls SET booked_halls = booked_halls + 1 WHERE hall_name = ?");
            $stmt->execute([$hall_name]);

            echo "<script>alert('Hall booked successfully!');</script>";
        } else {
            echo "<script>alert('No halls available for this type.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Hall</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0d6efd;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-select,
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            background-color: #0d6efd;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container mt-5">
        <h2>Book a Hall</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="hall_name" class="form-label">Select Hall</label>
                <select class="form-select" id="hall_name" name="hall_name" required>
                    <?php foreach ($halls as $hall): ?>
                        <option value="<?= $hall['hall_name'] ?>">
                            <?= $hall['hall_name'] ?> (Available: <?= $hall['total_halls'] - $hall['booked_halls'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="event_type" class="form-label">Event Type</label>
                <input type="text" class="form-control" id="event_type" name="event_type" placeholder="e.g., Wedding, Conference" required>
            </div>
            <div class="mb-3">
                <label for="booking_date" class="form-label">Booking Date</label>
                <input type="date" class="form-control" id="booking_date" name="booking_date" required>
            </div>
            <div class="mb-3">
                <label for="booking_time" class="form-label">Booking Time</label>
                <input type="time" class="form-control" id="booking_time" name="booking_time" required>
            </div>
            <button type="submit" class="btn btn-primary">Book Hall</button>
        </form>
    </div>
</body>

</html>