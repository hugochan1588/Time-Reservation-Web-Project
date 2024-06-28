<?php
//starts session
session_start();

//saving user's information
$studentname = $_SESSION["studentname"];
$studentid = $_SESSION["studentid"];
$type = $_SESSION["type"];

//student logs in to the system
if($type == 'student'){
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
</head>

<body class="">
    <!-- first header (white)   -->
    <header class="first-header">
        <div class="logo">
            <div class="polyu">
                <img src="images/main-logo-3x.png" alt="PolyU" usemap="#Polyu">
                    <map name="Polyu">
                        <area shape="rect" coords="0,0,300,50" alt="poly" href="main.php?id={<?php $SESSION['studentid']?>">
                    </map>
            </div>
            <div class="feng"><img src="images/feng.png" alt="Faculty of Engineering"></div>
            <div class="comp"><img src="images/compLogo.png" alt="Department of Computing"></div>
        </div>
    </header>
    
    <!-- second header (blue)   -->
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

        <!-- showing user's id in the second header-->
        <div class="name">
            <a>
            <?php 
            echo 'Welcome (';
            echo $_SESSION["studentid"];
            echo ').';
            ?>
          </a>

        <!-- logout button -->
        <form action="logout.php" method="post">
            <button class="btn btn-info" type="submit">Logout</button>
        </form>
        </div>
    </div>

    <div class="body">
    <div class="description">
            <p>Welcome to this <b>Online Reservation System.</b></p>
            <p>Through this <b>reservation system</b>, you can:</p>
            <ul>
                <li>Choose timeslots created by your professor.</li>
                <li>Meet your professor with the time your chose.</li>
                <li>Consultate, and learn from your professor!</li>
            </ul>
        </div>

        <div class="booking">
            <a class="btn btn-info" href="booking.php" role="button">Make a booking</a>
        </div>
    </div>
</body>
</html>

<?php
}

//teacher enters the system
if($type == 'teacher'){
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
</head>

<body class="">  
    <header class="first-header">
        <div class="logo">
            <div class="polyu">
                <img src="images/main-logo-3x.png" alt="PolyU" usemap="#Polyu">
                    <map name="Polyu">
                        <area shape="rect" coords="0,0,300,50" alt="poly" href="main.php?id={<?php $SESSION['studentid']?>">
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
                <a class="link create" href="createmeeting2.php">Create meeting</a>
                <a class="link password" href="createpassword.php">Create password</a>
                <a class="link choice" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkchoose.php">Check your choice</a>
                <a class="link result" href="teachercheckresult.php">Check result</a>
                <a class="link status" href="teachercheckstatus.php">Check status</a>
                <!-- <a class="link edit" href="teachereditresult.php">Edit result</a> -->
            </div>
        </div>

        <div class="name">
          <a>
            <?php 
            echo 'Welcome (';
            echo $_SESSION["studentid"];
            echo ').';
            ?>
          </a>

        <form action="logout.php" method="post">
            <button class="btn btn-info" type="submit">Logout</button>
        </form>
        </div>
    </div>

    <div class="body">
    <div class="description">
            <p>Welcome to this <b>Online Reservation System.</b></p>
            <p>Through this <b>reservation system</b>, you can:</p>
            <ul>
                <li>Choose timeslots created by your professor.</li>
                <li>Meet your professor with the time your chose.</li>
                <li>Consultate, and learn from your professor!</li>
            </ul>
        </div>
    </div>

    <!-- <footer>
        <div class="text-center p-2" style="background-color: rgb(146, 202, 255);">
            Copyright © 2023 The Hong Kong Polytechnic University. All Rights Reserved.
        </div>
    </footer> -->
</body>
</html>

<?php
}
?>