<?php
include('../config/connection.php');

$message = '';
$subject_name = '';

if (isset($_POST['save'])) {
    $subject_name = trim($_POST['subject_name'] ?? '');

    if ($subject_name === '') {
        $message = 'Please enter a subject name.';
    } else {
        try {
            // Using PDO prepared statements
            $stmt = $pdo->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
            
            if ($stmt->execute([$subject_name])) {
                $message = 'Subject added successfully!';
                $subject_name = '';
            } else {
                $message = 'Error adding subject.';
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Subject</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-box">
        <h2>Add Subject</h2>

        <?php if ($message !== ''): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Subject Name</label>
            <input type="text" name="subject_name" placeholder="Enter subject name" value="<?php echo htmlspecialchars($subject_name); ?>" required>

            <button type="submit" name="save" class="btn-primary">Save Subject</button>
            <a href="../dashboard.php" class="btn-link">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
