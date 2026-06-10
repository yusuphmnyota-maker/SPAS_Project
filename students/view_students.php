<?php
include('../config/connection.php');

$query = "SELECT * FROM students ORDER BY firstname, lastname";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Students List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="list-box">
        <h2>Students List</h2>
        
        <nav class="action-links">
            <a href="add_student.php" class="btn-primary">+ Add Student</a>
            <a href="../dashboard.php" class="btn-link">Dashboard</a>
        </nav>

        <table class="students-table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Class</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="5" class="no-data">No students found.</td>
                    </tr>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['class']); ?></td>
                            <td class="center">
                                <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn-edit">Edit</a>
                                <a href="edit_student.php?id=<?php echo $row['student_id']; ?>&action=delete" class="btn-delete" onclick="return confirm('Delete this student?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
