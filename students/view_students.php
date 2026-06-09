<?php
include('../config/connection.php');

$query = "SELECT * FROM students ORDER BY firstname, lastname";
$result = mysqli_query($conn, $query);
$rows = [];
while($row = mysqli_fetch_assoc($result)){
    $rows[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Students List</title>
    <link rel="stylesheet" href="css/addstudent.css">
    <style>
        .actions-col { width: 150px; text-align: center; }
        .btn-edit, .btn-delete { padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; display: inline-block; margin: 0 4px; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-edit:hover { background: #2563eb; }
        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h2 class="title">Students List</h2>
            <a href="add_student.php" class="btn-primary" style="text-decoration:none">+ Add Student</a>
            <a href="../dashboard.php" class="btn-link" style="background: black; color: white;">Dashboard</a>
        </div>

        <div class="table-wrap">
            <table class="students" style="width:100%;border-collapse:collapse">
                <thead style="background:#fbfdff">
                    <tr>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:left">First Name</th>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:left">Last Name</th>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:left">Gender</th>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:left">Class</th>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:center" class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($rows)): ?>
                        <tr>
                            <td colspan="5" style="padding:14px;text-align:center;color:#6b7280">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($rows as $row): ?>
                            <tr style="border-bottom:1px solid #eef2ff">
                                <td style="padding:10px 12px"><?php echo htmlspecialchars($row['firstname']); ?></td>
                                <td style="padding:10px 12px"><?php echo htmlspecialchars($row['lastname']); ?></td>
                                <td style="padding:10px 12px"><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td style="padding:10px 12px"><?php echo htmlspecialchars($row['class']); ?></td>
                                <td style="padding:10px 12px;text-align:center">
                                    <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn-edit">Edit</a>
                                    <a href="edit_student.php?id=<?php echo $row['student_id']; ?>&action=delete" class="btn-delete" onclick="return confirm('Delete this student?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
