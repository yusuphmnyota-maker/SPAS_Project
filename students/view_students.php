<?php
include('../config/connection.php');

// Fetch subjects using PDO
$query = "SELECT * FROM subjects ORDER BY subject_name";
$stmt = $pdo->query($query);
$subjects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Subjects List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="list-box">
        <h2>Subjects List</h2>
        
        <nav class="action-links">
            <a href="add_subject.php" class="btn-primary">+ Add Subject</a>
            <a href="../dashboard.php" class="btn-link">Dashboard</a>
        </nav>

        <table class="students-table">
            <thead>
                <tr>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="3" class="no-data">No subjects found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subjects as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subject_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td class="center">
                                <a href="edit_subject.php?id=<?php echo $row['subject_id']; ?>" class="btn-edit">Edit</a>
                                <a href="edit_subject.php?id=<?php echo $row['subject_id']; ?>&action=delete" class="btn-delete" onclick="return confirm('Delete this subject?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
