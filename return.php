<?php
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Return Book</title>
  </head>
  <body>
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="welcome.php">HOME</a>
      </li>
    </ul>

    <form action="return.php" method="Post">
      <div class="row g-3 align-items-center my-5">
        <div class="col-auto">
          <label for="inputPassword6" class="col-form-label">Return:</label>
        </div>
        <div class="col-auto">
          <input type="text" id="inputPassword6" class="form-control" name="book_nm" aria-describedby="passwordHelpInline">
        </div>
        <div class="col-auto">
          <span id="passwordHelpInline" class="form-text">
            Enter the book name you want to borrow.
          </span>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        session_start();
        $name = $_SESSION['username'];
        include '_dbconnect.php';
        $bookname = $_POST['book_nm'];
        
        if ($bookname) {
            $query = "SELECT * FROM borrow WHERE bookname='$bookname' OR bookname LIKE '%$bookname%';";
            $result = mysqli_query($conn, $query) or die('Error querying database');

            if ($row = mysqli_fetch_assoc($result)) {
                // ✅ Now it's safe to use $row
               // echo $row['bookname'] . "<br>";
                //echo $row['username'] . "<br>";

                if ($row['return_date'] == NULL) {
                    if ($row['bookname'] == $bookname && $row['username'] == $name) {
                        $return_date = date("Y-m-d");
                        $borrow_date = new DateTime($row['borrow_date']);
                        $return_date_obj = new DateTime($return_date);
                        $difference = $borrow_date->diff($return_date_obj);
                        $days = (int)$difference->days;
                        $fine = ($days > 7) ? ($days - 7) * 2 : 0;

                        $sql = "UPDATE borrow SET return_date='$return_date', fine='$fine' WHERE (bookname='$bookname' OR bookname LIKE '%$bookname%') AND username='$name';";
                        mysqli_query($conn, $sql) or die('Error updating borrow table');

                        if ($fine > 0) {
                            echo "You have a fine amount of Rs. $fine<br>";
                        }

                        echo "You can collect the book.<br>";

                        $query = "SELECT * FROM books WHERE Title='$bookname' AND num_avl >= 0;";
                        $result = mysqli_query($conn, $query) or die('Error querying books');

                        if ($row = mysqli_fetch_assoc($result)) {
                            $num = $row['num_avl'] + 1;
                            $sql = "UPDATE books SET num_avl='$num' WHERE Title='$bookname' OR Title LIKE '%$bookname%';";
                            mysqli_query($conn, $sql) or die('Error updating books');
                        }
                    } else {
                        echo "This book has not been borrowed by you.";
                    }
                } else {
                    echo "This book has already been returned.";
                }
            } else {
                echo "This book does not exist in the borrow list.";
            }
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
