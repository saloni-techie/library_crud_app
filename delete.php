<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: admin_login.php");
    exit;
}

?>
<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_title'])) {
    $delete_title = $_POST['delete_title'];
    $stmt = $conn->prepare("DELETE FROM books WHERE title = ?");
    $stmt->bind_param("s", $delete_title);
    $stmt->execute();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Delete Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<ul class="nav">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="welcome.php">HOME</a>
  </li>
</ul>

<h3 class="text-center my-4">Delete Books</h3>

<form action="delete.php" method="POST">
  <div class="row my-5">
    <div class="col my-3 text-center">
      <select class="form-select w-50 mx-auto" name="library" required>
        <option>Select Library</option>
        <option>Old Library</option>
        <option>New Library</option>
        <option>Engineering Library</option>
      </select>
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-danger">View Books</button>
    </div>
  </div>
</form>

<div class="container my-4">
  <table class="table" id="myTable">
    <thead>
      <tr>
        <th scope="col">Title</th>
        <th scope="col">Author</th>
        <th scope="col">Publisher</th>
        <th scope="col">Available</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['library'])) {
    $library = $_POST['library'];
    if ($library != "Select Library") {
        $sql = "SELECT * FROM books WHERE library='$library'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $title = htmlspecialchars($row['title'], ENT_QUOTES);
            echo "<tr>
                <td>$title</td>
                <td>{$row['author']}</td>
                <td>{$row['publisher']}</td>
                <td>{$row['num_avl']}</td>
                <td>
                  <form method='POST' action='delete.php' onsubmit='return confirmDelete(\"$title\")'>
                    <input type='hidden' name='delete_title' value=\"$title\">
                    <input type='hidden' name='library' value=\"$library\">
                    <button type='submit' class='btn btn-sm btn-danger'>Delete</button>
                  </form>
                </td>
              </tr>";
        }
    } else {
        echo '<script>alert("Select library to view books!");</script>';
    }
}
?>
    </tbody>
  </table>
</div>

<script>
function confirmDelete(title) {
  return confirm(`Are you sure you want to delete the book titled "${title}"?`);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
