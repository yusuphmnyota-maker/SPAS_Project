<?php
include('../config/connection.php');

$subject_id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';
$message = '';
$subject_name = '';

try {
    // Handle delete
    if ($action === 'delete' && !empty($subject_id)) {
        $stmt = $pdo->prepare("DELETE FROM subjects WHERE subject_id = ?");
        if ($stmt->execute([$subject_id])) {
            header('Location: view_subject.php?message=' . urlencode('Subject deleted successfully'));
            exit();
        }
    }

    // Fetch subject details
    if (!empty($subject_id)) {
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE subject_id = ?");
        $stmt->execute([$subject_id]);
        $subject = $stmt->fetch();
        if ($subject) {
            $subject_name = $subject['subject_name'];
        }
    }

    // Handle update
    if (isset($_POST['update'])) {
        $subject_id = $_POST['subject_id'];
        $subject_name = trim($_POST['subject_name'] ?? '');

        if ($subject_name === '') {
            $message = 'Subject name cannot be empty.';
        } else {
            $stmt = $pdo->prepare("UPDATE subjects SET subject_name = ? WHERE subject_id = ?");
            if ($stmt->execute([$subject_name, $subject_id])) {
                $message = 'Subject updated successfully!';
            } else {
                $message = 'Error updating subject.';
            }
        }
    }
} catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage();
}

if (empty($subject_id) && !isset($_POST['update'])) {
    header('Location: view_subject.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Subject</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-box">
        <h2>Edit Subject</h2>

        <?php if ($message !== ''): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (!empty($subject_name)): ?>
            <form method="POST">
                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>">
                
                <label>Subject Name</label>
                <input type="text" name="subject_name" placeholder="Enter subject name" value="<?php echo htmlspecialchars($subject_name); ?>" required>

                <button type="submit" name="update" class="btn-primary">Update Subject</button>
                <a href="view_subject.php" class="btn-link">Back to Subjects</a>
            </form>
        <?php else: ?>
            <p class="error">Subject not found.</p>
            <a href="view_subject.php" class="btn-link">Back to Subjects</a>
        <?php endif; ?>
    </div>
</body>
</html>
