<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: admin_login.php");
    exit;
}

?>
<?php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add or Update Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<ul class="nav">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="welcome.php">HOME</a>
  </li>
</ul>

<div class="container">
  <form action="update.php" method="POST">
    <div class="my-4">
      <label class="form-label">Action</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="action" value="add" required id="addAction">
        <label class="form-check-label">Add</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="action" value="update" required id="updateAction">
        <label class="form-check-label">Update</label>
      </div>
    </div>

    <!-- Display the original_title field only when "Update" is selected -->
    <div class="my-3" id="originalTitleField" style="display: none;">
      <label class="form-label">Book Title (required for update)</label>
      <input type="text" class="form-control" name="original_title">
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">New Title</label>
        <input type="text" class="form-control" name="title">
      </div>
      <div class="col-md-6">
        <label class="form-label">Author</label>
        <input type="text" class="form-control" name="author">
      </div>
      <div class="col-md-6">
        <label class="form-label">Publisher</label>
        <input type="text" class="form-control" name="publisher">
      </div>
      <div class="col-md-6">
        <label class="form-label">Library</label>
        <input type="text" class="form-control" name="library">
      </div>
      <div class="col-md-6">
        <label class="form-label">Available Copies (num_avl)</label>
        <input type="number" class="form-control" name="num_avl" min="0">
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary w-100">Submit</button>
    </div>
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';

    $action = $_POST['action'];
    $original = trim($_POST['original_title']);

    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $library = trim($_POST['library']);
    $num_avl = $_POST['num_avl'];

    if ($action === "add") {
      $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, library, num_avl) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssi", $title, $author, $publisher, $library, $num_avl);
      if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Book added successfully.</div>";
      } else {
        echo "<div class='alert alert-danger mt-3'>Failed to add book. Title might already exist.</div>";
      }
    } elseif ($action === "update") {
      $fields = [];
      $values = [];

      if (!empty($title)) {
        $fields[] = "title = ?";
        $values[] = $title;
      }
      if (!empty($author)) {
        $fields[] = "author = ?";
        $values[] = $author;
      }
      if (!empty($publisher)) {
        $fields[] = "publisher = ?";
        $values[] = $publisher;
      }
      if (!empty($library)) {
        $fields[] = "library = ?";
        $values[] = $library;
      }
      if ($num_avl !== '') {
        $fields[] = "num_avl = ?";
        $values[] = $num_avl;
      }

      if (count($fields) > 0) {
        $query = "UPDATE books SET " . implode(", ", $fields) . " WHERE title = ?";
        $values[] = $original;

        $types = str_repeat("s", count($values) - 1) . "s";
        if (in_array("num_avl = ?", $fields)) {
          $pos = array_search("num_avl = ?", $fields);
          $types = substr_replace($types, "i", $pos, 1);
        }

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
          echo "<div class='alert alert-success mt-3'>Book updated successfully.</div>";
        } else {
          echo "<div class='alert alert-warning mt-3'>No changes made or book not found.</div>";
        }
      } else {
        echo "<div class='alert alert-info mt-3'>No fields selected for update.</div>";
      }
    }
  }
  ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Display original title field only when "Update" action is selected
  document.getElementById('updateAction').addEventListener('change', function() {
    document.getElementById('originalTitleField').style.display = 'block';
  });

  document.getElementById('addAction').addEventListener('change', function() {
    document.getElementById('originalTitleField').style.display = 'none';
  });
</script>

</body>
</html>
