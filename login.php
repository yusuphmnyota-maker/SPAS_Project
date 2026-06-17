<?php
session_start();
include('config/connection.php'); 

$message = '';

if (isset($_POST['login'])) {
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = 'Please fill all fields.';
    } else {
        try {
            
            $query = "SELECT * FROM users WHERE username = :username AND password = :password";
            $stmt = $pdo->prepare($query);
            
            
            $stmt->execute([
                ':username' => $username,
                ':password' => $password
            ]);

            
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['username'] = $user['username']; 
       
                echo "<script>window.location.href='dashboard.php';</script>"; 
                exit;
            } else {
                $message = 'Invalid Username or Password';
            }
        } catch (PDOException $e) {
            
        $message = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SPAS Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-box">
        <h1>Student Performance Analysis System</h1>

        <?php if (!empty($message)): ?>
            <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label><b>Username</b></label>
            <input type="text" name="username" placeholder="Username" required>

            <label><b>Password</b></label>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="login" class="btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
