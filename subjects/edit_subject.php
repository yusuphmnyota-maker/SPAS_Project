<?php
include('../config/connection.php');

$subject_id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';
$message = '';
$messageType = '';
$subject_name = '';

if($action === 'delete' && !empty($subject_id)){
    $delete_query = "DELETE FROM subjects WHERE subject_id = ?";
    if($stmt = $conn->prepare($delete_query)){
        $stmt->bind_param('i', $subject_id);
        if($stmt->execute()){
            header('Location: view_subjects.php?message=' . urlencode('Subject deleted successfully') . '&type=success');
            exit();
        } else {
            $message = 'Error deleting subject: ' . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    }
}

if(!empty($subject_id)){
    $fetch_query = "SELECT * FROM subjects WHERE subject_id = ?";
    if($stmt = $conn->prepare($fetch_query)){
        $stmt->bind_param('i', $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            $subject = $result->fetch_assoc();
            if($subject){
                $subject_name = $subject['subject_name'];
            } else {
                $message = 'Subject not found.';
                $messageType = 'error';
            }
        }
        $stmt->close();
    }
}

if(isset($_POST['update'])){
    $subject_id = $_POST['subject_id'];
    $subject_name = trim($_POST['subject_name'] ?? '');

    if($subject_name === ''){
        $message = 'Subject name cannot be empty.';
        $messageType = 'error';
    } else {
        $update_query = "UPDATE subjects SET subject_name = ? WHERE subject_id = ?";
        if($stmt = $conn->prepare($update_query)){
            $stmt->bind_param('si', $subject_name, $subject_id);
            if($stmt->execute()){
                $message = 'Subject updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating subject: ' . $stmt->error;
                $messageType = 'error';
            }
            $stmt->close();
        }
    }
}

if(empty($subject_id) && !isset($_POST['update'])){
    header('Location: view_subjects.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Subject</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="title">Edit Subject</h2>
            <div class="actions" style="margin:0;">
                <a href="view_subjects.php" class="btn-link">Back to Subjects</a>
            </div>
        </div>

        <?php if($message !== ''): ?>
            <div class="message <?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($subject_name)): ?>
            <form method="POST" class="subject-form" novalidate>
                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>">
                <div class="field">
                    <label for="subject_name">Subject Name</label>
                    <input id="subject_name" type="text" name="subject_name" placeholder="Enter subject name" value="<?php echo htmlspecialchars($subject_name); ?>" required>
                </div>

                <div class="actions">
                    <button type="submit" name="update" class="btn-primary">Update Subject</button>
                </div>
            </form>
        <?php else: ?>
            <div class="message error">Subject not found.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
