<?php
include('../config/connection.php');

$message = '';
$messageType = '';

$students = $conn->query("SELECT student_id, firstname, lastname FROM students ORDER BY firstname, lastname");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");

$selectedStudent = $_POST['student_id'] ?? '';
$selectedSubject = $_POST['subject_id'] ?? '';
$marksValue = $_POST['marks'] ?? '';

if(isset($_POST['save'])){
    $selectedStudent = $_POST['student_id'] ?? '';
    $selectedSubject = $_POST['subject_id'] ?? '';
    $marksValue = trim($_POST['marks'] ?? '');

    if(empty($selectedStudent) || empty($selectedSubject) || $marksValue === ''){
        $message = "All fields are required.";
        $messageType = "error";
    } elseif(!is_numeric($marksValue) || $marksValue < 0 || $marksValue > 100) {
        $message = "Marks must be a number between 0 and 100.";
        $messageType = "error";
    } else {
        $query = "INSERT INTO marks (student_id, subject_id, marks) VALUES (?, ?, ?)";
        if($stmt = $conn->prepare($query)){
            $stmt->bind_param("iii", $selectedStudent, $selectedSubject, $marksValue);
            if($stmt->execute()){
                $message = "Marks added successfully.";
                $messageType = "success";
                $selectedStudent = '';
                $selectedSubject = '';
                $marksValue = '';
            } else {
                $message = "Unable to save marks: " . $stmt->error;
                $messageType = "error";
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $conn->error;
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Marks</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="title">Add Marks</h2>
            <a href="../dashboard.php" class="btn-link">Return to Dashboard</a>
        </div>

        <?php if(!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="marks-form" novalidate>
            <div class="field">
                <label for="student_id">Student</label>
                <select id="student_id" name="student_id" required>
                    <option value="">Select student</option>
                    <?php if($students && $students->num_rows): ?>
                        <?php while($student = $students->fetch_assoc()): ?>
                            <option value="<?php echo $student['student_id']; ?>" <?php echo ($selectedStudent == $student['student_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No students available</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="field">
                <label for="subject_id">Subject</label>
                <select id="subject_id" name="subject_id" required>
                    <option value="">Select subject</option>
                    <?php if($subjects && $subjects->num_rows): ?>
                        <?php while($subject = $subjects->fetch_assoc()): ?>
                            <option value="<?php echo $subject['subject_id']; ?>" <?php echo ($selectedSubject == $subject['subject_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No subjects available</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="field">
                <label for="marks">Marks</label>
                <input id="marks" type="number" min="0" max="100" name="marks" placeholder="Enter marks (0-100)" required value="<?php echo htmlspecialchars($marksValue); ?>">
            </div>

            <div class="actions">
                <button type="submit" name="save" class="btn-primary">Save Marks</button>
                <a href="../reports/performance.php" class="btn-link">View Performance</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
