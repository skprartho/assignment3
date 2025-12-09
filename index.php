<?php
include 'db_connect.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $license = $_POST['license'];
    $engine = $_POST['engine'];
    $date = $_POST['date'];
    $mechanic_id = $_POST['mechanic'];

    $check_client = $conn->prepare("SELECT id FROM appointments WHERE car_license = ? AND appointment_date = ?");
    $check_client->bind_param("ss", $license, $date);
    $check_client->execute();
    $check_client->store_result();

    if ($check_client->num_rows > 0) {
        $message = "Error: You already have an appointment on this date.";
        $message_type = "error";
    } else {

        $check_mech = $conn->prepare("SELECT count(*) as total FROM appointments WHERE mechanic_id = ? AND appointment_date = ?");
        $check_mech->bind_param("is", $mechanic_id, $date);
        $check_mech->execute();
        $result = $check_mech->get_result();
        $row = $result->fetch_assoc();

        if ($row['total'] >= 4) {
            $message = "Error: This mechanic is fully occupied (4/4) on this date. Please choose another.";
            $message_type = "error";
        } else {

            $stmt = $conn->prepare("INSERT INTO appointments (client_name, client_address, client_phone, car_license, car_engine, appointment_date, mechanic_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $name, $address, $phone, $license, $engine, $date, $mechanic_id);

            if ($stmt->execute()) {
                $message = "Success! Appointment booked.";
                $message_type = "success";
            } else {
                $message = "Database error: " . $conn->error;
                $message_type = "error";
            }
            $stmt->close();
        }
        $check_mech->close();
    }
    $check_client->close();
}

$mechanics = $conn->query("SELECT * FROM mechanics");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Car Workshop Appointment</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>

<body>

    <div class="container">
        <h2>Book an Appointment</h2>

        <?php if ($message): ?>
            <div class="alert <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Client Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" required>
            </div>

            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" id="phone" required>
                <small>Numbers only</small>
            </div>

            <div class="form-group">
                <label>Car License No:</label>
                <input type="text" name="license" required>
            </div>

            <div class="form-group">
                <label>Car Engine No:</label>
                <input type="text" name="engine" id="engine" required>
                <small>Numbers only</small>
            </div>

            <div class="form-group">
                <label>Appointment Date:</label>
                <input type="date" name="date" id="date" required>
            </div>

            <div class="form-group">
                <label>Select Mechanic:</label>
                <select name="mechanic" id="mechanic" required>
                    <option value="">-- Select Mechanic --</option>
                    <?php while ($row = $mechanics->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn">Book Appointment</button>
            <br><br>
            <a href="admin.php" style="text-align:center; display:block;">Go to Admin Panel</a>
        </form>
    </div>

</body>

</html>