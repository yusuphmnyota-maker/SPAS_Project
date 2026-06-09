<?php
include('../config/connection.php');

$student_id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';
$message = '';
$messageType = '';
$student = null;

// Handle delete
if($action === 'delete' && !empty($student_id)){
    $delete_query = "DELETE FROM students WHERE student_id = ?";
    if($stmt = $conn->prepare($delete_query)){
        $stmt->bind_param("i", $student_id);
        if($stmt->execute()){
            header("Location: view_students.php?message=Student deleted successfully");
            exit();
        } else {
            $message = "Error deleting student: " . $stmt->error;
            $messageType = "error";
        }
        $stmt->close();
    }
}

// Fetch student for editing
if(!empty($student_id)){
    $fetch_query = "SELECT * FROM students WHERE student_id = ?";
    if($stmt = $conn->prepare($fetch_query)){
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $stmt->close();
        
        if(!$student){
            $message = "Student not found.";
            $messageType = "error";
        }
    }
}

// Handle update
if(isset($_POST['update'])){
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $class = trim($_POST['class'] ?? '');
    $student_id = $_POST['student_id'] ?? '';

    if(empty($firstname) || empty($lastname) || empty($gender) || empty($class)){
        $message = "All fields are required!";
        $messageType = "error";
    } else {
        $update_query = "UPDATE students SET firstname = ?, lastname = ?, gender = ?, class = ? WHERE student_id = ?";
        if($stmt = $conn->prepare($update_query)){
            $stmt->bind_param("ssssi", $firstname, $lastname, $gender, $class, $student_id);
            if($stmt->execute()){
                $message = "Student updated successfully!";
                $messageType = "success";
                $student = array('student_id' => $student_id, 'firstname' => $firstname, 'lastname' => $lastname, 'gender' => $gender, 'class' => $class);
            } else {
                $message = "Error updating student: " . $stmt->error;
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

if(!$student && empty($student_id)){
    header("Location: view_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/addstudent.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h2 class="title">Edit Student</h2>

        <?php if(!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if($student): ?>
            <form method="POST" class="student-form" novalidate>
                <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">

                <div class="field">
                    <label for="firstname">First Name</label>
                    <input id="firstname" type="text" name="firstname" placeholder="First Name" required value="<?php echo htmlspecialchars($student['firstname']); ?>" autofocus>
                </div>

                <div class="field">
                    <label for="lastname">Last Name</label>
                    <input id="lastname" type="text" name="lastname" placeholder="Last Name" required value="<?php echo htmlspecialchars($student['lastname']); ?>">
                </div>

                <div class="field">
                    <label for="class">Class</label>
                    <input id="class" type="text" name="class" placeholder="e.g Form Two" required value="<?php echo htmlspecialchars($student['class']); ?>">
                </div>

                <div class="field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select gender</option>
                        <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>

                <div class="actions">
                    <button type="submit" name="update" class="btn-primary">Update Student</button>
                    <a href="view_students.php" class="btn-link">Back</a>
                </div>
            </form>
        <?php else: ?>
            <p style="color: #dc2626; padding: 12px; background: rgba(220, 38, 38, 0.06); border-radius: 8px;">Student not found.</p>
            <a href="view_students.php" class="btn-link">Back to Students</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
