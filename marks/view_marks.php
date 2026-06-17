<?php
include('../config/connection.php');

try {
    // Fetch subjects for column headings using PDO
    $subjects = $pdo->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name")->fetchAll();

    // Fetch student marks and combine them using PDO
    $query = "SELECT s.student_id, s.firstname, s.lastname, sub.subject_id, m.marks, m.mark_id
              FROM students s
              LEFT JOIN marks m ON s.student_id = m.student_id
              LEFT JOIN subjects sub ON m.subject_id = sub.subject_id
              ORDER BY s.firstname, s.lastname";
    $results = $pdo->query($query)->fetchAll();

    $students = [];
    foreach ($results as $row) {
        $sid = $row['student_id'];
        if (!isset($students[$sid])) {
            $students[$sid] = [
                'student_id' => $sid,
                'name' => $row['firstname'] . ' ' . $row['lastname'],
                'marks' => [],
                'mark_ids' => [],
                'sum' => 0,
                'count' => 0,
                'average' => 0
            ];
        }
        if ($row['subject_id'] !== null) {
            $students[$sid]['marks'][$row['subject_id']] = $row['marks'];
            $students[$sid]['mark_ids'][$row['subject_id']] = $row['mark_id'];
            $students[$sid]['sum'] += $row['marks'];
            $students[$sid]['count']++;
        }
    }

    // Calculate averages
    foreach ($students as &$student) {
        if ($student['count'] > 0) {
            $student['average'] = $student['sum'] / $student['count'];
        }
    }
    unset($student);

    // Sort students by average (Highest to Lowest)
    usort($students, function($a, $b) {
        return $b['average'] <=> $a['average'];
    });

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Marks List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="list-box">
        <h2>Marks List</h2>
        
        <nav class="action-links">
            <a href="add_marks.php" class="btn-primary">+ Add Marks</a>
            <a href="../dashboard.php" class="btn-link">Back</a>
        </nav>

        <table class="marks-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Student Name</th>
                    <?php foreach ($subjects as $subject): ?>
                        <th><?php echo htmlspecialchars($subject['subject_name']); ?></th>
                    <?php endforeach; ?>
                    <th>Average</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="<?php echo 4 + count($subjects); ?>" class="no-data">No marks recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $rank = 1;
                    foreach ($students as $student): 
                    ?>
                        <tr>
                            <td class="center font-bold"><?php echo $student['count'] > 0 ? $rank++ : '-'; ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            
                            <?php foreach ($subjects as $subject): ?>
                                <td class="center">
                                    <?php echo $student['marks'][$subject['subject_id']] ?? '-'; ?>
                                </td>
                            <?php endforeach; ?>
                            
                            <td class="center font-bold">
                                <?php echo $student['count'] > 0 ? number_format($student['average'], 2) : '-'; ?>
                            </td>
                            <td class="center">
                                <?php if (!empty($student['marks'])): ?>
                                    <?php $firstMarkId = reset($student['mark_ids']); ?>
                                    <a href="edit_marks.php?id=<?php echo $firstMarkId; ?>" class="btn-edit">Edit</a>
                                    <a href="edit_marks.php?id=<?php echo $firstMarkId; ?>&action=delete" class="btn-delete" onclick="return confirm('Delete this mark?')">Delete</a>
                                <?php else: ?>
                                    <span class="muted">No marks</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
