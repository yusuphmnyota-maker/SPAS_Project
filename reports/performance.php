<?php
include('../config/connection.php');

try {
    // Using PDO to fetch averages and rank from highest to lowest
    $query = "SELECT students.firstname, students.lastname, AVG(marks.marks) AS average_marks
              FROM marks
              JOIN students ON marks.student_id = students.student_id
              GROUP BY students.student_id
              ORDER BY average_marks DESC";

    $stmt = $pdo->query($query);
    $reports = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Performance Report</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="list-box">
        <h2>Performance Report</h2>
        
        <nav class="action-links">
            <input id="search" placeholder="Search student...">
            <button id="printBtn" class="btn-primary">Print</button>
            <a href="../dashboard.php" class="btn-link">Dashboard</a>
        </nav>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Average</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody id="reportBody">
                <?php 
                $rank = 1;
                if (!empty($reports)):
                    foreach ($reports as $row): 
                        $avg = $row['average_marks'];

                        // Grades
                        if ($avg >= 81) $grade = "A";
                        elseif ($avg >= 61) $grade = "B";
                        elseif ($avg >= 41) $grade = "C";
                        elseif ($avg >= 21) $grade = "D";
                        else $grade = "F";
                ?>
                    <tr>
                        <td class="center font-bold"><?php echo $rank++; ?></td>
                        <td><?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?></td>
                        <td class="center"><?php echo number_format($avg, 2); ?></td>
                        <td class="center font-bold"><?php echo $grade; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-data">No performance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    // Print report
    document.getElementById('printBtn').addEventListener('click', function() { 
        window.print(); 
    });

    // Search filter
    document.getElementById('search').addEventListener('input', function() {
        var q = this.value.toLowerCase();
        var rows = document.querySelectorAll('#reportBody tr');
        rows.forEach(function(r) {
            var nameCell = r.querySelectorAll('td')[1];
            if (nameCell) {
                var name = nameCell.textContent.toLowerCase();
                r.style.display = name.indexOf(q) !== -1 ? '' : 'none';
            }
        });
    });
    </script>
</body>
</html>
