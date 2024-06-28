<?php
date_default_timezone_set("Asia/Hong_Kong");
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
//include "conn.php";

session_start();
$student_id = $_SESSION["studentid"];

// Step 1: Get the meeting details from the database
$stmt = $conn->prepare("SELECT * FROM `meeting` WHERE `uuid` = ? ");
$stmt->bind_param("s", $_GET['uuid']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {

        // Add var_dump statement to check the row data
        //var_dump($row); 

        $mt_deadline = $row['deadline'];
        $mt_timeslots = json_decode($row['timeslots'], true);

        $mt_title = $row['title'];
        $mt_subject = $row['subject'];
        $mt_teacher = $row['teacher'];
        $mt_duration = $row['duration'];
        $mt_deadline = $row['deadline'];

        $stmt->free_result();
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM `result` WHERE `uuid` = ? ");
        $stmt->bind_param("s", $_GET['uuid']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            while ($row = $result->fetch_assoc()) {

                $timeslots = $row['result'];
                $timeslots_array = json_decode($timeslots, true);

                $stmt->free_result();
                $stmt->close();

                //Add var_dump statement to check the result object
                //var_dump($timeslots);
            }
        }

        else{
        ?>
            <script>
                // Show warning message
                alert("The result has not been released yet.");
                // Redirect to checkresult.php
                window.location.href = "checkresult.php";
            </script>
        <?php
        }
    }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
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
                <a class="link result" style="background-color:rgb(156, 255, 253, 0.43);" href="checkresult.php">Check result</a>
                <a class="link status" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkstatus.php">Check status</a>
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
        <main class="w-100 m-auto" id="main"  >
            <div class="card py-md-5 py-2 px-sm-2 px-md-5   my-5 w-100"  >
                <div class="card-body">

                <h1 class="mb-4 text-dark">Timeslot Allocation Results</h1>
                <h4>Meeting title: <small class="text-secondary"> <?php echo $mt_title ?></small></h4>
                <h4>Subject title: <small class="text-secondary"><?php echo $mt_subject ?></small></h4>
                <h4>Teacher name: <small class="text-secondary"><?php echo $mt_teacher ?></small></h4>
                <h4>Meeting code: <small class="text-secondary"> <?php echo $_GET['uuid'] ?> </small></h4>

                    <?php
                    $assigned = false;
                    foreach ($timeslots_array as $timeslot => $students) {
                        if (in_array($student_id, $students)) {
                            echo "<table class='table mt-5'>";
                            echo "<tr><th>Timeslot</th><th>Student</th></tr>";
                            echo "<tr><td>" . str_replace("_", " ", $timeslot) . "</td><td>" . implode(", ", $students) . "</td></tr>";
                            echo "</table>";
                            $assigned = true;
                            break;
                        }
                    }

                    if (!$assigned) {
                        echo "You are not assigned to any timeslot.";
                    }
                    ?>
                    </tbody>

            </div>
        </div>
    </main>

</div>

</body>
</html>