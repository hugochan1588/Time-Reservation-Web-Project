<?php
date_default_timezone_set("Asia/Hong_Kong");
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
// include "conn.php";

session_start();
$studentid = $_SESSION["studentid"];

$stmt = $conn->prepare("SELECT * FROM `meeting` WHERE `uuid` = ? ");
$stmt->bind_param("s" , $_GET['uuid']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows==1){

    while ($row = $result->fetch_assoc()) {

        $mt_title = $row['title'];
        $mt_subject = $row['subject'];
        $mt_teacher = $row['teacher'];
        $mt_duration = $row['duration'];
        $mt_deadline = $row['deadline'];
        $mt_studentid = $row['studentid'];

        $number = $row['numbers'];
        $day = $row['days'];

        $numbers = explode(',', $number);
        $days = explode(',', $day);

        $studentnum = count(json_decode($mt_studentid));

        $daynum = count($numbers);
        $total = array_sum($numbers);
    }

    $stmt->free_result();
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM `choices` WHERE `code` = ? ");
    $stmt->bind_param("s" , $_GET['uuid']);
    $stmt->execute();
    $result = $stmt->get_result();

    $choosestudent=$result->num_rows;

    $stmt->free_result();
    $stmt->close();


}else{
    header('Location: main.php');
}
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
                <a class="link result" href="teachercheckresult.php">Check result</a>
                <a class="link status" style="background-color:rgb(156, 255, 253, 0.43);" href="teachercheckstatus.php">Check status</a>
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
            <button class="btn btn-info" type="submit">Logout</button>
        </form>
        </div>
    </div>

    <div class="container">

        <main class="w-100 m-auto" id="main">
            <div class="card py-md-5 py-2 px-sm-2 px-md-5 my-5 w-100">
                <div class="card-body">

                    <?php
                        if (isset($_GET['success'])){
                    ?>

                    <h2><small class="text-poly">Meeting created successfully!</small></h2>

                    <?php
                    }
                    ?>

                    <h1 class="mb-4 text-dark">Meeting Status</h1>
                    <h4>Meeting title: <small class="text-secondary"> <?php echo $mt_title ?></small></h4>
                    <h4>Subject title: <small class="text-secondary"><?php echo $mt_subject ?></small></h4>
                    <h4>Teacher name: <small class="text-secondary"><?php echo $mt_teacher ?></small></h4>
                    <h4>Duration of each meeting (minutes): <small class="text-secondary"><?php echo $mt_duration ?></small></h4>
                    <h4>Deadline time: <small class="text-secondary"> <?php echo $mt_deadline ?> </small></h4>
                    <h4>Meeting code: <small class="text-secondary"> <?php echo $_GET['uuid'] ?> </small></h4>
                    

                    <div class="card bg-light mx-1 mt-5">
                        <div class="card-body">
                            <h5>Student who have made a choice</h5>
                            <h1 class="display-1 fw-bold"><?php echo $choosestudent?>/<?php echo $studentnum?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>

</body>
</html>