<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logout page</title>
</head>
<body>
<?php require 'partials/_nav.php' ?>

</body>
</html>

