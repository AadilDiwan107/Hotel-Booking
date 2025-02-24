<?php
// book_room.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch room availability
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_type = $_POST['room_type'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $user_id = $_SESSION['user_id'];

    // Debugging: Check if user_id is valid
    if (!isset($user_id) || empty($user_id)) {
        die("Error: User ID is not set or invalid.");
    }

    // Get today's date
    $current_date = date('Y-m-d');

    // Validate check-in and check-out dates
    if ($check_in_date < $current_date) {
        echo "<script>alert('Check-in date cannot be in the past. Please select a valid date.');</script>";
    } elseif ($check_out_date < $current_date) {
        echo "<script>alert('Check-out date cannot be in the past. Please select a valid date.');</script>";
    } elseif ($check_out_date <= $check_in_date) {
        echo "<script>alert('Check-out date must be after the check-in date.');</script>";
    } else {
        // Check room availability
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_type = ?");
        $stmt->execute([$room_type]);
        $room = $stmt->fetch();

        if ($room['total_rooms'] > $room['booked_rooms']) {
            // Book the room
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_type, check_in_date, check_out_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $room_type, $check_in_date, $check_out_date]);

            // Update booked_rooms count
            $stmt = $pdo->prepare("UPDATE rooms SET booked_rooms = booked_rooms + 1 WHERE room_type = ?");
            $stmt->execute([$room_type]);

            echo "<script>alert('Room booked successfully!');</script>";
        } else {
            echo "<script>alert('No rooms available for this type.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room</title>
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
        <h2>Book a Room</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="room_type" class="form-label">Select Room Type</label>
                <select class="form-select" id="room_type" name="room_type" required>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['room_type'] ?>">
                            <?= ucfirst($room['room_type']) ?> (Available: <?= $room['total_rooms'] - $room['booked_rooms'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="check_in_date" class="form-label">Check-in Date</label>
                <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
            </div>
            <div class="mb-3">
                <label for="check_out_date" class="form-label">Check-out Date</label>
                <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Book Room</button>
        </form>
    </div>
</body>

</html>