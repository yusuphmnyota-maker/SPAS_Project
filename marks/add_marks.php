<?php
include('../config/connection.php');

$message = '';
$selectedStudent = '';
$selectedSubject = '';
$marksValue = '';

try {
    // Fetch active lists using PDO
    $students = $pdo->query("SELECT student_id, firstname, lastname FROM students ORDER BY firstname, lastname")->fetchAll();
    $subjects = $pdo->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name")->fetchAll();

    if (isset($_POST['save'])) {
        $selectedStudent = $_POST['student_id'] ?? '';
        $selectedSubject = $_POST['subject_id'] ?? '';
        $marksValue = trim($_POST['marks'] ?? '');

        if (empty($selectedStudent) || empty($selectedSubject) || $marksValue === '') {
            $message = "All fields are required.";
        } elseif (!is_numeric($marksValue) || $marksValue < 0 || $marksValue > 100) {
            $message = "Marks must be a number between 0 and 100.";
        } else {
            // Using PDO prepared statements
            $stmt = $pdo->prepare("INSERT INTO marks (student_id, subject_id, marks) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$selectedStudent, $selectedSubject, $marksValue])) {
                $message = "Marks added successfully.";
                $selectedStudent = '';
                $selectedSubject = '';
                $marksValue = '';
            } else {
                $message = "Unable to save marks.";
            }
        }
    }
} catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Marks</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-box">
        <h2>Add Marks</h2>

        <?php if (!empty($message)): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Student</label>
            <select name="student_id" required>
                <option value="">Select student</option>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>" <?php echo ($selectedStudent == $student['student_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label>Subject</label>
            <select name="subject_id" required>
                <option value="">Select subject</option>
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['subject_id']; ?>" <?php echo ($selectedSubject == $subject['subject_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label>Marks</label>
            <input type="number" min="0" max="100" name="marks" placeholder="Enter marks (0-100)" value="<?php echo htmlspecialchars($marksValue); ?>" required>

            <button type="submit" name="save" class="btn-primary">Save Marks</button>
            <a href="../dashboard.php" class="btn-link">Dashboard</a>
        </form>
    </div>
</body>
</html>
