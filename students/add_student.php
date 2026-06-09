<?php
include('../config/connection.php');

$message = '';
$messageType = '';

if(isset($_POST['save'])){
    
    // Validate input
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $class = trim($_POST['class'] ?? '');

    if(empty($firstname) || empty($lastname) || empty($gender) || empty($class)){
        $message = "All fields are required!";
        $messageType = "error";
    } else {
        // Use prepared statements to prevent SQL injection
        $query = "INSERT INTO students(firstname, lastname, gender, class) VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if($stmt){
            $stmt->bind_param("ssss", $firstname, $lastname, $gender, $class);
            
            if($stmt->execute()){
                $message = "Student Added Successfully!";
                $messageType = "success";
                
                // Clear form
                $_POST = array();
            } else {
                $message = "Error adding student: " . $stmt->error;
                $messageType = "error";
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $conn->error;
            $messageType = "error";
        }
    }
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Student</title>
    <link rel="stylesheet" href="css/addstudent.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h2 class="title">Add Student</h2>

        <?php if(!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="student-form" novalidate>
            <div class="field">
                <label for="firstname">First Name</label>
                <input id="firstname" type="text" name="firstname" placeholder="First Name" required value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>" autofocus>
            </div>

            <div class="field">
                <label for="lastname">Last Name</label>
                <input id="lastname" type="text" name="lastname" placeholder="Last Name" required value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>">
            </div>

            <div class="field">
                <label for="class">Class</label>
                <input id="class" type="text" name="class" placeholder="e.g Form Two" required value="<?php echo htmlspecialchars($_POST['class'] ?? ''); ?>">
            </div>

            <div class="field">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select gender</option>
                    <option value="Male" <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="actions">
                <button type="submit" name="save" class="btn-primary">Save Student</button>
                <a href="../dashboard.php" class="btn-link">Back</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
