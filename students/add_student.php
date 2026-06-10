<?php
include('../config/connection.php');

$message = '';

if (isset($_POST['save'])) {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $gender = $_POST['gender'] ?? '';

    if (empty($firstname) || empty($lastname) || empty($class) || empty($gender)) {
        $message = "All fields are required!";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (firstname, lastname, class, gender) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $class, $gender);
        
        if ($stmt->execute()) {
            $message = "Student Added Successfully!";
        } else {
            $message = "Error adding student.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-box">
        <h2>Add New Student</h2>

        <?php if (!empty($message)): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>First Name</label>
            <input type="text" name="firstname" placeholder="First Name" required>

            <label>Last Name</label>
            <input type="text" name="lastname" placeholder="Last Name" required>

            <label>Class</label>
            <input type="text" name="class" placeholder="e.g Form Two" required>

            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <button type="submit" name="save" class="btn-primary">Save Student</button>
            <a href="../dashboard.php" class="btn-link">Back</a>
        </form>
    </div>
</body>
</html>
