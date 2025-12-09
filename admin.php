<?php
include 'db_connect.php';

if (isset($_POST['update'])) {
    $app_id = $_POST['app_id'];
    $new_date = $_POST['new_date'];
    $new_mechanic = $_POST['new_mechanic'];

    $update_stmt = $conn->prepare("UPDATE appointments SET appointment_date = ?, mechanic_id = ? WHERE id = ?");
    $update_stmt->bind_param("sii", $new_date, $new_mechanic, $app_id);
    $update_stmt->execute();
    $update_stmt->close();
}

$sql = "SELECT a.id, a.client_name, a.client_phone, a.car_license, a.appointment_date, m.name as mechanic_name, m.id as mechanic_id 
        FROM appointments a 
        JOIN mechanics m ON a.mechanic_id = m.id 
        ORDER BY a.appointment_date DESC";
$result = $conn->query($sql);

$mechanics_list = $conn->query("SELECT * FROM mechanics");
$mechanics_arr = [];
while ($m = $mechanics_list->fetch_assoc()) {
    $mechanics_arr[] = $m;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container wide">
        <h2>Admin Panel - Appointments List</h2>
        <a href="index.php" class="btn-small">Back to Booking</a>

        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Phone</th>
                    <th>Car Reg No</th>
                    <th>Date</th>
                    <th>Assigned Mechanic</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="">
                            <td><?php echo $row['client_name']; ?></td>
                            <td><?php echo $row['client_phone']; ?></td>
                            <td><?php echo $row['car_license']; ?></td>

                            <td>
                                <input type="date" name="new_date" value="<?php echo $row['appointment_date']; ?>" required>
                            </td>

                            <td>
                                <select name="new_mechanic">
                                    <?php foreach ($mechanics_arr as $mech): ?>
                                        <option value="<?php echo $mech['id']; ?>" <?php if ($mech['id'] == $row['mechanic_id'])
                                               echo 'selected'; ?>>
                                            <?php echo $mech['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <input type="hidden" name="app_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="update" class="btn-small">Update</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>