<?php
session_start();
include('config/connection.php');

$message = '';

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){
        $message = 'Please fill all fields.';
    } else {
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0){
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $message = 'Invalid Username or Password';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SPAS Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="title">Student Performance Analysis System</h1>
            </div>

            <?php if(!empty($message)): ?>
                <div class="message error"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form class="login-form" method="POST">
                <div class="field">
                    <label><b>Username</b</label>
                    <input type="text" name="username" placeholder="Username">
                </div>

                <div class="field">
                    <label><b>Password</b></label>
                    <input type="password" name="password" placeholder="Password">
                </div>

                <button type="submit" name="login" class="btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
