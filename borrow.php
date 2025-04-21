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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Borrow Book</title>
  </head>
  <body>
    <ul class="nav">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="index.php">HOME</a>
    </li>
    </ul>
    
    <form action="borrow.php" method="Post">
        <div class="row g-3 align-items-center my-5">
            <div class="col-auto">
                <label for="inputPassword6" class="col-form-label">Borrow:</label>
            </div>
            <div class="col-auto">
                <input type="text" id="inputPassword6" class="form-control" name="book_nm" aria-describedby="passwordHelpInline">
            </div>
            <div class="col-auto">
                <span id="passwordHelpInline" class="form-text">
                    Enter the book name you want to return.
                </span>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>        
        </div>
    </form>

    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            include '_dbconnect.php';
            $bookname = $_POST['book_nm'];
            if($bookname){
                $query = "Select * from books where title='$bookname'  AND num_avl > 0;";
                $result = mysqli_query($conn, $query) or die('error querring database');
                if($row = mysqli_fetch_assoc($result)){
                    echo 'You can borrow your books from the counter';
                    $num = $row['num_avl']-1;
                    $sql = "Update books set num_avl='$num' where title='$bookname';";
                    $result = mysqli_query($conn, $sql) or die('error querring database');
                    session_start();
                    $name = $_SESSION['username'];
                    $today_date=  date("Y-m-d");
                    $book = $row['title'];
                    $sql2 = "insert into borrow(`username`,`bookname`, `borrow_date`) values ('$name','$book','$today_date');";
                    $result = mysqli_query($conn, $sql2) or die('error querring database');
                }
                else{
                    echo 'This book is currently not available.<br>';
                    echo 'To search the book <a href="search.php">Click Here</a>';
                    
                }
            }
            
        
        }
    ?>







    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>