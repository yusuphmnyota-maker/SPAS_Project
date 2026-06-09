<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="title">Welcome to SPAS Dashboard</h1>
            </div>

            <ul class="dashboard-menu">
                <li><a href="students/add_student.php">Add Student</a></li>
                <li><a href="students/view_students.php">View Students</a></li>
                <li><a href="subjects/add_subject.php">Add Subject</a></li>
                <li><a href="marks/add_marks.php">Add Marks</a></li>
                <li><a href="marks/view_marks.php">View Marks</a></li>
                <li><a href="reports/performance.php">Performance Report</a></li>
                <li><a href="logout.php" class="logout-link">Logout</a></li>
            </ul>
        </div>
    </div>
</body>
</html>

