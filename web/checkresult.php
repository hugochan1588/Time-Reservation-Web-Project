<?php
// Start the PHP session and retrieve the student ID from the session variable
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
  <script src="scripts/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="styles/bootstrap.min.css" >
  <link rel="stylesheet" href="styles/main.css" >
</head>

<body>  
    <!-- First header section with PolyU, Faculty of Engineering, and Department of Computing logos -->
    <header class="first-header">
        <div class="logo">

            <!-- PolyU logo which directs the user back to the main page -->
            <div class="polyu">
                <img src="images/main-logo-3x.png" alt="PolyU" usemap="#Polyu">
                    <map name="Polyu">
                        <area shape="rect" coords="0,0,300,50" alt="poly" href="main.php">
                    </map>
            </div>

            <!-- Display of two other logos -->
            <div class="feng"><img src="images/feng.png" alt="Faculty of Engineering"></div>
            <div class="comp"><img src="images/compLogo.png" alt="Department of Computing"></div>
        </div>
    </header>

    <!-- Second header section with links and student name and ID -->
    <div class="second-header">
        <div class="home">
            <h1> Online Reservation System</h1>
            <div class="content">
                <!-- Create meeting link (disabled), create password link (disabled), check choice link, check result link, and check status link (disabled) -->
                <a class="link create" style="pointer-events: none; color: rgb(142, 168, 190);" href="createmeeting2.php">Create meeting</a>
                <a class="link password" style="pointer-events: none; color: rgb(142, 168, 190);" href="createpassword.php">Create password</a>
                <a class="link choice" href="checkchoice.php">Check your choice</a>
                <a class="link result" style="background-color:rgb(156, 255, 253, 0.43);" href="checkresult.php">Check result</a>
                <a class="link status" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkstatus.php">Check status</a>
            </div>
        </div>

        <!-- Display the student ID retrieved from the session variable -->
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

        <!-- Logout button -->
        <form action="logout.php" method="post">
            <button class="btn btn-info" id="logout" type="submit">Logout</button>
        </form>
        </div>
    </div>

    <div class="container">
        
        <!-- Card section with a form to make a booking -->
        <div class="card py-md-5 py-2 px-sm-2 px-md-5 my-5 w-100">

            <!-- Form input for the meeting code -->
            <form onsubmit="return false;" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <div class="col-xs-2">
                        <label for="code" class="form-label">Meeting code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter meeting code" required>
                    </div>
                </div>
                
                <!-- Hidden input for student ID, populated with session data -->
                <div class="mb-3">
                    <label for="studentid" class=""></label>
                        <?php
                        if ($_SESSION['studentid']) {
                        echo' <input type="hidden" class="" id="studentid" name="studentid" value = "'.$_SESSION["studentid"].'" ?></input>';
                        }
                        ?>
                </div>

                <!-- Submit button for the form -->
                <div class="d-grid">
                    <button type="submit" id="submit" class="btn btn-info text-white">Submit</button>
                </div>
            </form>
        </div>
    </div>

<script type="text/javascript">
  $("#submit").click(function(){
    window.location.href = "studentresult.php?uuid="+$("#code").val();
  });
</script>
</body>
</html>