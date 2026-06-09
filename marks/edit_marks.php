<?php
include('../config/connection.php');

$mark_id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';
$message = '';
$messageType = '';
$mark = null;

// Handle delete
if($action === 'delete' && !empty($mark_id)){
    $delete_query = "DELETE FROM marks WHERE mark_id = ?";
    if($stmt = $conn->prepare($delete_query)){
        $stmt->bind_param("i", $mark_id);
        if($stmt->execute()){
            header("Location: view_marks.php?message=Mark deleted successfully");
            exit();
        } else {
            $message = "Error deleting mark: " . $stmt->error;
            $messageType = "error";
        }
        $stmt->close();
    }
}

// Fetch mark for editing
if(!empty($mark_id)){
    $fetch_query = "SELECT m.mark_id, m.student_id, m.subject_id, m.marks,
                           s.firstname, s.lastname, sub.subject_name
                    FROM marks m
                    JOIN students s ON m.student_id = s.student_id
                    JOIN subjects sub ON m.subject_id = sub.subject_id
                    WHERE m.mark_id = ?";
    if($stmt = $conn->prepare($fetch_query)){
        $stmt->bind_param("i", $mark_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $mark = $result->fetch_assoc();
        $stmt->close();
        
        if(!$mark){
            $message = "Mark record not found.";
            $messageType = "error";
        }
    }
}

// Fetch students and subjects for dropdowns
$students = $conn->query("SELECT student_id, firstname, lastname FROM students ORDER BY firstname, lastname");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");

// Handle update
if(isset($_POST['update'])){
    $mark_id = $_POST['mark_id'] ?? '';
    $student_id = $_POST['student_id'] ?? '';
    $subject_id = $_POST['subject_id'] ?? '';
    $marks = trim($_POST['marks'] ?? '');

    if(empty($student_id) || empty($subject_id) || $marks === ''){
        $message = "All fields are required!";
        $messageType = "error";
    } elseif(!is_numeric($marks) || $marks < 0 || $marks > 100){
        $message = "Marks must be a number between 0 and 100.";
        $messageType = "error";
    } else {
        $update_query = "UPDATE marks SET student_id = ?, subject_id = ?, marks = ? WHERE mark_id = ?";
        if($stmt = $conn->prepare($update_query)){
            $stmt->bind_param("iiii", $student_id, $subject_id, $marks, $mark_id);
            if($stmt->execute()){
                $message = "Mark updated successfully!";
                $messageType = "success";
                // Refresh mark data
                $fetch_query = "SELECT m.mark_id, m.student_id, m.subject_id, m.marks,
                                       s.firstname, s.lastname, sub.subject_name
                                FROM marks m
                                JOIN students s ON m.student_id = s.student_id
                                JOIN subjects sub ON m.subject_id = sub.subject_id
                                WHERE m.mark_id = ?";
                if($stmt2 = $conn->prepare($fetch_query)){
                    $stmt2->bind_param("i", $mark_id);
                    $stmt2->execute();
                    $result = $stmt2->get_result();
                    $mark = $result->fetch_assoc();
                    $stmt2->close();
                }
            } else {
                $message = "Error updating mark: " . $stmt->error;
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

if(!$mark && empty($mark_id)){
    header("Location: view_marks.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Mark</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h2 class="title">Edit Mark</h2>

        <?php if(!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if($mark): ?>
            <form method="POST" class="marks-form" novalidate>
                <input type="hidden" name="mark_id" value="<?php echo $mark['mark_id']; ?>">

                <div class="field">
                    <label for="student_id">Student</label>
                    <select id="student_id" name="student_id" required>
                        <option value="">Select student</option>
                        <?php 
                        $students = $conn->query("SELECT student_id, firstname, lastname FROM students ORDER BY firstname, lastname");
                        while($student = $students->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $student['student_id']; ?>" <?php echo ($mark['student_id'] == $student['student_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="subject_id">Subject</label>
                    <select id="subject_id" name="subject_id" required>
                        <option value="">Select subject</option>
                        <?php 
                        $subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
                        while($subject = $subjects->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $subject['subject_id']; ?>" <?php echo ($mark['subject_id'] == $subject['subject_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="marks">Marks</label>
                    <input id="marks" type="number" min="0" max="100" name="marks" placeholder="Enter marks (0-100)" required value="<?php echo htmlspecialchars($mark['marks']); ?>">
                </div>

                <div class="actions">
                    <button type="submit" name="update" class="btn-primary">Update Mark</button>
                    <a href="view_marks.php" class="btn-link">Back</a>
                </div>
            </form>
        <?php else: ?>
            <p style="color: #dc2626; padding: 12px; background: rgba(220, 38, 38, 0.06); border-radius: 8px;">Mark record not found.</p>
            <a href="view_marks.php" class="btn-link">Back to Marks</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
