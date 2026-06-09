<?php
include('../config/connection.php');

// Fetch subjects for column headings
$subjects = [];
$subjectQuery = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name";
$subjectResult = $conn->query($subjectQuery);
if($subjectResult){
    while($subject = $subjectResult->fetch_assoc()){
        $subjects[] = $subject;
    }
}

// Fetch student marks and pivot by subject
$query = "SELECT s.student_id, s.firstname, s.lastname, sub.subject_id, m.marks, m.mark_id
          FROM students s
          LEFT JOIN marks m ON s.student_id = m.student_id
          LEFT JOIN subjects sub ON m.subject_id = sub.subject_id
          ORDER BY s.firstname, s.lastname, sub.subject_name";
$result = $conn->query($query);
$students = [];
if($result){
    while($row = $result->fetch_assoc()){
        $studentId = $row['student_id'];
        if(!isset($students[$studentId])){
            $students[$studentId] = [
                'student_id' => $studentId,
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'marks' => [],
                'mark_ids' => [],
                'sum' => 0,
                'count' => 0,
                'average' => null,
            ];
        }

        if($row['subject_id'] !== null){
            $students[$studentId]['marks'][$row['subject_id']] = $row['marks'];
            $students[$studentId]['mark_ids'][$row['subject_id']] = $row['mark_id'];
            $students[$studentId]['sum'] += $row['marks'];
            $students[$studentId]['count'] += 1;
        }
    }
}

foreach($students as &$student){
    if($student['count'] > 0){
        $student['average'] = $student['sum'] / $student['count'];
    }
}
unset($student);

// Rank students by average
$studentList = array_values($students);
usort($studentList, function($a, $b){
    if($a['average'] === null && $b['average'] === null){
        return strcmp($a['lastname'], $b['lastname']) ?: strcmp($a['firstname'], $b['firstname']);
    }
    if($a['average'] === null){
        return 1;
    }
    if($b['average'] === null){
        return -1;
    }
    if($b['average'] == $a['average']){
        $cmp = strcmp($a['lastname'], $b['lastname']);
        return $cmp !== 0 ? $cmp : strcmp($a['firstname'], $b['firstname']);
    }
    return ($b['average'] < $a['average']) ? -1 : 1;
});

$rank = 1;
foreach($studentList as &$student){
    if($student['average'] !== null){
        $student['rank'] = $rank++;
    } else {
        $student['rank'] = '-';
    }
}
unset($student);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Marks List</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .actions-col { width: 160px; text-align: center; }
        .btn-edit, .btn-delete { padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; display: inline-block; margin: 2px 2px; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-edit:hover { background: #2563eb; }
        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }
        .subject-cell { text-align: center; }
        .subject-cell span { display: block; }
        .small-action { font-size: 11px; padding: 4px 6px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h2 class="title">Marks List</h2>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
                <a href="add_marks.php" class="btn-primary">+ Add Marks</a>
                <a href="../dashboard.php" class="btn-link" style="background: black; color: white">Back</a>
            </div>
        </div>

        <div class="table-wrap">
            <table class="students" style="width:100%;border-collapse:collapse">
                <thead style="background:#fbfdff">
                    <tr>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:center">Rank</th>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:left">Student Name</th>
                        <?php foreach($subjects as $subject): ?>
                            <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:center"><?php echo htmlspecialchars($subject['subject_name']); ?></th>
                        <?php endforeach; ?>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:center">Average</th>
                        <th style="padding:10px 12px;border-bottom:1px solid #eef2ff;text-align:center" class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($studentList)): ?>
                        <tr>
                            <td colspan="<?php echo 4 + count($subjects); ?>" style="padding:14px;text-align:center;color:#6b7280">No marks recorded.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($studentList as $student): ?>
                            <tr style="border-bottom:1px solid #eef2ff">
                                <td style="padding:10px 12px;text-align:center;font-weight:600;color:#3b82f6"><?php echo $student['rank']; ?></td>
                                <td style="padding:10px 12px"><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></td>
                                <?php foreach($subjects as $subject): ?>
                                    <td class="subject-cell" style="padding:10px 12px;border-right:1px solid #eef2ff;">
                                        <?php if(isset($student['marks'][$subject['subject_id']])): ?>
                                            <span><?php echo htmlspecialchars($student['marks'][$subject['subject_id']]); ?></span>
                                        <?php else: ?>
                                            <span style="color:#9ca3af">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <td style="padding:10px 12px;text-align:center;font-weight:600">
                                    <?php echo $student['average'] !== null ? number_format($student['average'], 2) : '-'; ?>
                                </td>
                                <td style="padding:10px 12px;text-align:center">
                                    <?php if(!empty($student['marks'])): ?>
                                        <?php $firstMarkId = reset($student['mark_ids']); ?>
                                        <div style="display:inline-flex;gap:6px;flex-wrap:wrap;justify-content:center;">
                                            <a href="edit_marks.php?id=<?php echo htmlspecialchars($firstMarkId); ?>" class="btn-edit">Edit</a>
                                            <a href="edit_marks.php?id=<?php echo htmlspecialchars($firstMarkId); ?>&action=delete" class="btn-delete" onclick="return confirm('Delete this mark?')">Delete</a>
                                        </div>
                                    <?php else: ?>
                                        <span style="color:#9ca3af">No marks</span>
                                    <?php endif; ?>
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
