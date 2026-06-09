<?php
include('../config/connection.php');

$message = '';
$messageType = '';
$subject_name = '';

if(isset($_POST['save'])){
    $subject_name = trim($_POST['subject_name'] ?? '');

    if($subject_name === ''){
        $message = 'Please enter a subject name.';
        $messageType = 'error';
    } else {
        $query = "INSERT INTO subjects(subject_name) VALUES (?)";
        if($stmt = $conn->prepare($query)){
            $stmt->bind_param('s', $subject_name);
            if($stmt->execute()){
                $message = 'Subject added successfully!';
                $messageType = 'success';
                $subject_name = '';
            } else {
                $message = 'Database error: ' . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        } else {
            $message = 'Database error: ' . $conn->error;
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Subject</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="title">Add Subject</h2>
        </div>

        <?php if($message !== ''): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="subject-form" novalidate>
            <div class="field">
                <label for="subject_name">Subject Name</label>
                <input id="subject_name" type="text" name="subject_name" placeholder="Enter subject name" value="<?php echo htmlspecialchars($subject_name); ?>" required>
            </div>

            <div class="actions">
                <button type="submit" name="save" class="btn-primary">Save Subject</button>
                <a href="../dashboard.php" class="btn-link">Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
