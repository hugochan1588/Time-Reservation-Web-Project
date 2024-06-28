<?php
session_start();
date_default_timezone_set("Asia/Hong_Kong");
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
// include "conn.php";

$studentid = $_POST["studentid"];
$code = $_POST['code'];

$stmt = $conn->prepare("SELECT * FROM `choices` WHERE `studentid` = ? and `code` = ?");
$stmt->bind_param("ss", $studentid, $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $mt_selected = $row['selected_time'];
        $mt_submission = $row['submission_time'];

        $selected_times = explode(',', $mt_selected);
        $daynum = count($selected_times);
    }
    $stmt->free_result();
    $stmt->close();

    $sql = "SELECT * FROM `meeting` WHERE `uuid` = '$code'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1){
        while ($row = $result->fetch_assoc()){
            $mt_title = $row['title'];
            $mt_subject = $row['subject'];
            $mt_teacher = $row['teacher'];
        }
    }
}

else{
    ?>
    <script>
        alert("You have not made any choice.");
        window.location.href = 'checkchoice.php';
    </script>
    <?php
    header('Location: checkchoice.php');
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
            <a class="link create" style="pointer-events: none; color: rgb(142, 168, 190);" href="createmeeting2.php">Create meeting</a>
                <a class="link password" style="pointer-events: none; color: rgb(142, 168, 190);" href="createpassword.php">Create password</a>
                <a class="link choice" style="background-color:rgb(156, 255, 253, 0.43);" href="checkchoice.php">Check your choice</a>
                <a class="link result" href="checkresult.php">Check result</a>
                <a class="link status" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkstatus.php">Check status</a>
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
                    <h1 class="mb-4 text-dark">The following table is the choice you made</h1>

                    <h4>Meeting title: <small class="text-secondary"> <?php echo $mt_title ?></small></h4>
                    <h4>Subject title: <small class="text-secondary"><?php echo $mt_subject ?></small></h4>
                    <h4>Teacher name: <small class="text-secondary"><?php echo $mt_teacher ?></small></h4>
                    <h4>Meeting code: <small class="text-secondary"> <?php echo $code ?> </small></h4>
                    <h4>StudentID: <small class="text-secondary"> <?php echo $studentid ?> </small></h4>
                    <!-- <h4>Selected Time: <small class="text-secondary"> <?php echo $mt_selected ?> </small></h4> -->
                    <h4>Submission Time: <small class="text-secondary"> <?php echo $mt_submission ?> </small></h4>

                    <?php
                            echo "<table class='table mt-5'>";
                            echo '<thead><tr><th>Preference</th><th>Time Slot</th></tr></thead>';
                            echo '<tbody>';
                            $i = 1;
                                foreach ($selected_times as $timeslot) {
                                    echo "<tr>";
                                    echo "<td>Choice " . $i . "</td>";
                                    echo "<td>" . $timeslot . "</td>";  
                                    echo "</tr>";
                                    $i++;
                                }
                                
                            echo '</tbody>';
                            echo '</table>';
                    ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>