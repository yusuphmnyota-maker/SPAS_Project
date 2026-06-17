<?php
include('../config/connection.php');

$mark_id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';
$message = '';
$mark = null;

try {
    // Handle delete
    if ($action === 'delete' && !empty($mark_id)) {
        $stmt = $pdo->prepare("DELETE FROM marks WHERE mark_id = ?");
        if ($stmt->execute([$mark_id])) {
            header("Location: view_marks.php");
            exit();
        }
    }

    // Fetch mark record for editing
    if (!empty($mark_id)) {
        $stmt = $pdo->prepare("SELECT * FROM marks WHERE mark_id = ?");
        $stmt->execute([$mark_id]);
        $mark = $stmt->fetch();
    }

    // Handle update
    if (isset($_POST['update'])) {
        $mark_id = $_POST['mark_id'] ?? '';
        $student_id = $_POST['student_id'] ?? '';
        $subject_id = $_POST['subject_id'] ?? '';
        $marks = trim($_POST['marks'] ?? '');

        if (empty($student_id) || empty($subject_id) || $marks === '') {
            $message = "All fields are required!";
        } elseif (!is_numeric($marks) || $marks < 0 || $marks > 100) {
            $message = "Marks must be a number between 0 and 100.";
        } else {
            $stmt = $pdo->prepare("UPDATE marks SET student_id = ?, subject_id = ?, marks = ? WHERE mark_id = ?");
            if ($stmt->execute([$student_id, $subject_id, $marks, $mark_id])) {
                $message = "Mark updated successfully!";
                $mark = ['mark_id' => $mark_id, 'student_id' => $student_id, 'subject_id' => $subject_id, 'marks' => $marks];
            } else {
                $message = "Error updating mark.";
            }
        }
    }

    if (!$mark && empty($mark_id)) {
        header("Location: view_marks.php");
        exit();
    }

    // Fetch lists for dropdown options using PDO
    $students = $pdo->query("SELECT student_id, firstname, lastname FROM students ORDER BY firstname, lastname")->fetchAll();
    $subjects = $pdo->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name")->fetchAll();

} catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Mark</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-box">
        <h2>Edit Mark</h2>

        <?php if (!empty($message)): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($mark): ?>
            <form method="POST">
                <input type="hidden" name="mark_id" value="<?php echo $mark['mark_id']; ?>">

                <label>Student</label>
                <select name="student_id" required>
                    <option value="">Select student</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>" <?php echo ($mark['student_id'] == $student['student_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Subject</label>
                <select name="subject_id" required>
                    <option value="">Select subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['subject_id']; ?>" <?php echo ($mark['subject_id'] == $subject['subject_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Marks</label>
                <input type="number" min="0" max="100" name="marks" value="<?php echo htmlspecialchars($mark['marks']); ?>" required>

                <button type="submit" name="update" class="btn-primary">Update Mark</button>
                <a href="view_marks.php" class="btn-link">Back</a>
            </form>
        <?php else: ?>
            <p class="error">Mark record not found.</p>
            <a href="view_marks.php" class="btn-link">Back to Marks</a>
        <?php endif; ?>
    </div>
</body>
</html>
