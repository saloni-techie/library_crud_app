<?php
$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';

    $username = $_POST["username"];
    $password = $_POST["Password"];
    $cpassword = $_POST["cpassword"];
    $user = "student";

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $showError = "Username already exists.";
    } else {
        if ($password === $cpassword) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, date, user) VALUES (?, ?, current_timestamp(),?)");
            $stmt->bind_param("sss", $username, $hashed_password,$user);
            if ($stmt->execute()) {
                $showAlert = true;
            } else {
                $showError = "Something went wrong.";
            }
        } else {
            $showError = "Passwords do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup page</title>
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <?php
        if($showAlert){
        echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your account is now created and you can login
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div> ';
       }
       if($showError){
        echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> '. $showError.'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div> ';
        }
    ?>
    <div class="container">
        <h2 class="text-center">Signup to our website</h2>
        <form action="signup.php" method="Post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
                
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" class="form-control" id="Password" name="Password">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"> Confirm Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="cpassword">
            </div>
            <button type="submit" class="btn btn-primary">Signup</button>
    </form>
    </div>
</body>
</html>