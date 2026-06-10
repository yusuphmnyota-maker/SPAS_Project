<?php
include('../config/connection.php');

$student_id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';
$message = '';
$student = null;

// Handle delete
if ($action === 'delete' && !empty($student_id)) {
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    if ($stmt->execute()) {
        header("Location: view_student.php");
        exit();
    }
    $stmt->close();
}

// Fetch student details
if (!empty($student_id)) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle update
if (isset($_POST['update'])) {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $student_id = $_POST['student_id'] ?? '';

    if (empty($firstname) || empty($lastname) || empty($class) || empty($gender)) {
        $message = "All fields are required!";
    } else {
        $stmt = $conn->prepare("UPDATE students SET firstname = ?, lastname = ?, class = ?, gender = ? WHERE student_id = ?");
        $stmt->bind_param("ssssi", $firstname, $lastname, $class, $gender, $student_id);
        if ($stmt->execute()) {
            $message = "Student updated successfully!";
            $student = ['student_id' => $student_id, 'firstname' => $firstname, 'lastname' => $lastname, 'class' => $class, 'gender' => $gender];
        } else {
            $message = "Error updating student.";
        }
        $stmt->close();
    }
}

if (!$student && empty($student_id)) {
    header("Location: view_student.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-box">
        <h2>Edit Student</h2>

        <?php if (!empty($message)): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($student): ?>
            <form method="POST">
                <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">

                <label>First Name</label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($student['firstname']); ?>" required>

                <label>Last Name</label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($student['lastname']); ?>" required>

                <label>Class</label>
                <input type="text" name="class" value="<?php echo htmlspecialchars($student['class']); ?>" required>

                <label>Gender</label>
                <select name="gender" required>
                    <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>

                <button type="submit" name="update" class="btn-primary">Update Student</button>
                <a href="view_student.php" class="btn-link">Back</a>
            </form>
        <?php else: ?>
            <p class="error">Student not found.</p>
            <a href="view_student.php" class="btn-link">Back to List</a>
        <?php endif; ?>
    </div>
</body>
</html>

