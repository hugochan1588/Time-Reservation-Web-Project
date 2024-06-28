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
                <a class="link create" href="createmeeting2.php">Create meeting</a>
                <a class="link password" href="createpassword.php">Create password</a>
                <a class="link choice" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkchoose.php">Check your choice</a>
                <a class="link result" style="background-color:rgb(156, 255, 253, 0.43);" href="teachercheckresult.php">Check result</a>
                <a class="link status" href="teachercheckstatus.php">Check status</a>
                <!-- <a class="link edit" href="teachereditresult.php">Edit result</a> -->
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
        <div class="card py-md-5 py-2 px-sm-2 px-md-5   my-5 w-100">
            <form onsubmit="return false;" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <div class="col-xs-2">
                        <label for="code" class="form-label">Meeting code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter meeting code" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="studentid" class=""></label>
                        <?php
                        if ($_SESSION['studentid']) {
                        echo' <input type="hidden" class="" id="studentid" name="studentid" value = "'.$_SESSION["studentid"].'" ?></input>';
                        }
                        ?>
                </div>

                <div class="d-grid">
                    <button type="submit" id="submit" class="btn btn-info text-white">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- <footer>
        <div class="text-center p-2" style="background-color: rgb(146, 202, 255);">
            Copyright © 2023 The Hong Kong Polytechnic University. All Rights Reserved.
        </div>
    </footer> -->

<script src="scripts/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
  $("#submit").click(function(){
    window.location.href = "result2.php?uuid="+$("#code").val();
  });
</script>
</body>
</html>