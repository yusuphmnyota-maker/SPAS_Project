<?php
include('../config/connection.php');

$query = "SELECT students.firstname, students.lastname,
AVG(marks.marks) AS average_marks
FROM marks
JOIN students ON marks.student_id = students.student_id
GROUP BY students.student_id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Performance Report</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/report.css">
</head>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title mb-0">Performance Report</h2>
                <div class="d-flex align-items-center gap-2">
                    <input id="search" class="form-control form-control-sm" placeholder="Search student" style="max-width:180px;">
                    <button id="printBtn" class="btn btn-primary btn-sm">Print</button>
                    <a href="../dashboard.php" class="btn btn-secondary btn-sm">Dashboard</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Average</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody id="reportBody">

                    <?php while($row = mysqli_fetch_assoc($result)){

                    $avg = $row['average_marks'];

                    if($avg >= 81){
                            $grade = "A";
                    }elseif($avg >= 61){
                            $grade = "B";
                    }elseif($avg >= 41){
                            $grade = "C";
                    }elseif($avg >= 21){
                            $grade = "D";
                    }else{
                            $grade = "F";
                    }
                    ?>

                    <tr>
                            <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                            <td><?php echo number_format($avg,2); ?></td>
                            <td><?php echo $grade; ?></td>
                    </tr>

                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('printBtn').addEventListener('click', function(){ window.print(); });
document.getElementById('search').addEventListener('input', function(){
    var q = this.value.toLowerCase();
    var rows = document.querySelectorAll('#reportBody tr');
    rows.forEach(function(r){
        var name = r.querySelector('td').textContent.toLowerCase();
        r.style.display = name.indexOf(q) !== -1 ? '' : 'none';
    });
});
</script>

</body>
</html>
