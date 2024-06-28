<?php
session_start();
$studentid = $_SESSION["studentid"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="images/favicon.ico" />
    <title>Online Reservation System</title>
    <link rel="stylesheet" href="styles/bootstrap.min.css" >
    <link rel="stylesheet" href="styles/main.css" >
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
</head>
<body>
    <header class="first-header">
        <div class="logo">
            <div class="polyu">
                <img src="images/main-logo-3x.png" alt="PolyU" usemap="#Polyu">
                    <map name="Polyu">
                        <area shape="rect" coords="0,0,300,50" alt="poly" href="main.php">
                    </map>
            </div>
            <div class="feng"><img src="images/feng.png" alt="Faculty of Engineering"></div>
            <div class="comp"><img src="images/compLogo.png" alt="Department of Computing"></div>
        </div>
    </header>

    <div class="second-header">
        <div class="home">
            <h1>Online Reservation System</h1>
            <div class="content">
                <a class="link create" style="pointer-events: none; color: rgb(142, 168, 190);" href="createmeeting2.php">Create meeting</a>
                <a class="link password" style="pointer-events: none; color: rgb(142, 168, 190);" href="createpassword.php">Create password</a>
                <a class="link choice" href="checkchoice.php">Check your choice</a>
                <a class="link result" href="checkresult.php">Check result</a>
                <a class="link status" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkstatus.php">Check status</a>
                <!-- <a class="link edit" style="pointer-events: none; color: rgb(142, 168, 190);" href="editresult.html">Edit result</a> -->
            </div>
        </div>

        <div class="name">
          <a>
            <?php 
            echo 'Welcome ';
            echo $_SESSION["studentname"];
            echo ' (';
            echo $_SESSION["studentid"];
            echo ').';
            ?>
          </a>

        <form action="logout.php" method="post">
            <button class="btn btn-info" id="logout" type="submit">Logout</button>
        </form>
        </div>
    </div>
<div class="container">

  <main class="w-100 m-auto" id="main"  >
    <div class="card py-md-5 py-2 px-sm-2 px-md-5   my-5 w-100"  >
      <div class="card-body" >
        <h1 class="mb-4 text-dark">Your choices have been successfully submitted!</h1>

      </div>
    </div>
  </main>

</div>




</body>
</html>