<?php
include('../config/connection.php');

$message = $_GET['message'] ?? '';
$messageType = $_GET['type'] ?? '';

$query = "SELECT * FROM subjects ORDER BY subject_name";
$result = $conn->query($query);
$subjects = [];
if($result){
    while($row = $result->fetch_assoc()){
        $subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subject Settings</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .table-wrap { overflow-x: auto; }
        table.subjects th, table.subjects td { text-align: left; }
        .actions-col { width: 170px; text-align: center; }
        .btn-edit, .btn-delete { padding: 10px 14px; border-radius: 10px; text-decoration: none; font-size: 12px; display: inline-block; margin: 0 4px; font-weight: 600; transition: all 0.2s ease; }
        .btn-edit { background: #0ea5e9; color: #fff; }
        .btn-edit:hover { background: #0284c7; transform: translateY(-1px); }
        .btn-delete { background: #ef4444; color: #fff; }
        .btn-delete:hover { background: #dc2626; transform: translateY(-1px); }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="title">Subject Settings</h2>
            <div class="actions" style="margin:0;">
                <a href="add_subject.php" class="btn-primary">Add Subject</a>
                <a href="../dashboard.php" class="btn-link">Dashboard</a>
            </div>
        </div>

        <?php if($message): ?>
            <div class="message <?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="table-wrap">
            <table class="subjects" style="width:100%;border-collapse:collapse;">
                <thead style="background:#fbfdff;">
                    <tr>
                        <th style="padding:12px 14px;border-bottom:1px solid #e2e8f0;">Subject Name</th>
                        <th class="actions-col" style="padding:12px 14px;border-bottom:1px solid #e2e8f0;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($subjects)): ?>
                        <tr>
                            <td colspan="2" style="padding:16px;text-align:center;color:#64748b;">No subjects found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($subjects as $subject): ?>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e2e8f0;vertical-align:middle;">
                                    <?php echo htmlspecialchars($subject['subject_name']); ?>
                                </td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e2e8f0;vertical-align:middle;text-align:center;">
                                    <a href="edit_subject.php?id=<?php echo $subject['subject_id']; ?>" class="btn-edit">Edit</a>
                                    <a href="edit_subject.php?id=<?php echo $subject['subject_id']; ?>&action=delete" class="btn-delete" onclick="return confirm('Delete this subject?');">Delete</a>
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
