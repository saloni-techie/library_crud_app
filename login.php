<?php
$login = false;
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';

    $username = $_POST["username"];
    $password = $_POST["Password"];
    if (empty($username)) {
        header("Location: login.php");
        exit(); // Always use exit() after header to stop further script execution
    }
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("location: index.php");
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "User not found. Signup if new user.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <?php
        if($login){
            echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> You are logged in
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> ';
            }
            if($error){
            echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> '. $error.'
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> ';
            }
    ?>
    <div class="container">
        <h2 class="text-center">Login to our website</h2>
        <div class="text-center mb-4">
            <a href="login.php" class="btn btn-outline-primary mr-2">Login as Student</a>
            <a href="admin_login.php" class="btn btn-outline-secondary">Login as Admin</a>
        </div>

        <form action="login.php" method="Post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
                
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" class="form-control" id="Password" name="Password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p style="font-size: 0.9em; margin-top: 10px;">
                <a href="update_password.php">Forgot Password?</a>
            </p>

    </form>
    </div>
</body>
</html>