<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: admin_login.php");
    exit;
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Search Book</title>
  </head>
  <body>
  <ul class="nav">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="index.php">HOME</a>
  </li>
</ul>
    <h3 class="text-center my-4">Search for books</h3>
    <form action="search.php" method="POST">
        <div class="row my-5">
            <div class="col my-3">
            <select class="form-select" aria-label="Default select example " name="library">
                <option>Select Library</option>
                <option >Old Library</option>
                <option >New Library</option>
                <option >Engineering Library</option>
            </select>
            </div>
            <div class="col my-3">
                <input type="text" class="form-control" placeholder="Publisher" aria-label="Publisher" name="Publisher">
            </div>
        </div>
        <div class="row my-5">
            <div class="col my-3">
                    <input type="text" class="form-control" placeholder="Title" aria-label="Title" name="Title">
            </div>
            <div class="col my-3">
                <input type="text" class="form-control" placeholder="Author" aria-label="Author" name="Author">
            </div>
            </div>
            <div class ="text-center">
                <button type="submit" class="btn btn-info">Search</button>
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
          <th scope="col">No. of books available</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            include '_dbconnect.php';
            $library = isset($_POST["library"]) ? $_POST["library"] : null;
            $publisher = isset($_POST["Publisher"]) ? $_POST["Publisher"] : null;
            $author = isset($_POST["Author"]) ? $_POST["Author"] : null;
            $title = isset($_POST["Title"]) ? $_POST["Title"] : null;
            if($library!='Select Library'){
                if($publisher){
                    $sql = "SELECT * FROM `books` where library='$library' AND publisher like'%$publisher%'";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>
                        <td>". $row['title'] . "</td>
                        <td>". $row['author'] . "</td>
                        <td>". $row['publisher'] . "</td>
                        <td>". $row['num_avl'] . "</td>
                    </tr>"; 
                    }
                }
                elseif($author){
                  $sql = "SELECT * FROM `books` where library='$library' AND author like'%$author%'";
                  $result = mysqli_query($conn, $sql);
                  while($row = mysqli_fetch_assoc($result)){
                      echo "<tr>
                      <td>". $row['title'] . "</td>
                      <td>". $row['author'] . "</td>
                      <td>". $row['publisher'] . "</td>
                      <td>". $row['num_avl'] . "</td>
                  </tr>"; 
                  }
                }
                elseif($title){
                  $sql = "SELECT * FROM `books` where library='$library' AND title like'%$title%'";
                  $result = mysqli_query($conn, $sql);
                  while($row = mysqli_fetch_assoc($result)){
                      echo "<tr>
                      <td>". $row['title'] . "</td>
                      <td>". $row['author'] . "</td>
                      <td>". $row['publisher'] . "</td>
                      <td>". $row['num_avl'] . "</td>
                  </tr>"; 
                  }
                }
                else{
                    $sql = "SELECT * FROM `books` where library='$library'";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr>
                        <td>". $row['title'] . "</td>
                        <td>". $row['author'] . "</td>
                        <td>". $row['publisher'] . "</td>
                        <td>". $row['num_avl'] . "</td>
                    </tr>";
                    }
                }
            }
            else{
                echo '<script>alert("Select library to search!");</script>';
            }
        }
          ?>


      </tbody>
    </table>
  </div>

















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