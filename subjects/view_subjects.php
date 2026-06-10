<?php
include('../config/connection.php');

$message = $_GET['message'] ?? '';

$query = "SELECT * FROM subjects ORDER BY subject_name";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subject Settings</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="list-box">
        <h2>Subject Settings</h2>
        
        <nav class="action-links">
            <a href="add_subject.php" class="btn-primary">Add Subject</a>
            <a href="../dashboard.php" class="btn-link">Dashboard</a>
        </nav>

        <?php if ($message): ?>
            <p class="msg"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <table class="subjects-table">
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows == 0): ?>
                    <tr>
                        <td colspan="2" class="no-data">No subjects found.</td>
                    </tr>
                <?php else: ?>
                    <?php while ($subject = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                            <td class="center">
                                <a href="edit_subject.php?id=<?php echo $subject['subject_id']; ?>" class="btn-edit">Edit</a>
                                <a href="edit_subject.php?id=<?php echo $subject['subject_id']; ?>&action=delete" class="btn-delete" onclick="return confirm('Delete this subject?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

