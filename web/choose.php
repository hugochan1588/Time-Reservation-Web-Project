<?php
// Start the PHP session and retrieve the student ID from the session variable
session_start();
date_default_timezone_set("Asia/Hong_Kong");

// Database connection
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
//include "conn.php";

// Step 1: Get the meeting details from the database
$stmt = $conn->prepare("SELECT * FROM `meeting` WHERE `uuid` = ? ");
$stmt->bind_param("s" , $_POST['code'] );
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows==1){

    while ($row = $result->fetch_assoc()) {

        $mt_studentid = json_decode($row['studentid'], true);
        $mt_uuid = json_decode($row['uuid'], true);

        // Check if the provided student ID is in the list of student IDs associated with the meeting information/table
        if(in_array($_POST['studentid'],$mt_studentid)){

            // Retrieve meeting details
            $mt_title = $row['title'];
            $mt_subject = $row['subject'];
            $mt_teacher = $row['teacher'];
            $mt_duration = $row['duration'];
            $mt_deadline = $row['deadline'];
            $mt_timeslots = json_decode($row['timeslots'], true);

            $number = $row['numbers'];
            $day = $row['days'];

            $numbers = explode(',', $number); //turns the data from database into array
            $days = explode(',', $day); //turns the data from database into array
            $days = array_map('trim', $days); //Trim out the space in the array

            $daynum = count($numbers); //Counting how many days is inputted
            $total = array_sum($numbers); //Counting the total timeslots 
        }else{
            ?>
            <script>
                alert("Invalid studentID.");
                window.location.href = 'main.php';
            </script>
            <?php    
        }

    }

}

else{
    ?>
    <script>
        alert("Invalid studentID or meeting code!");
        window.location.href = 'main.php';
    </script>
    <?php
}

$stmt->free_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="images/favicon.ico" />
    <title>PolyU reservation system</title>
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
        <main class="w-100 m-auto" id="main">
            <div class="card py-md-5 py-2 px-sm-2 px-md-5 my-5 w-100">
                <div class="card-body">
                    <h1 class="mb-4 text-dark">Please select time slots by your preferences</h1>
                
                    <h4>Meeting title: <small class="text-secondary"> <?php echo $mt_title ?></small></h4>
                    <h4>Subject title: <small class="text-secondary"><?php echo $mt_subject ?></small></h4>
                    <h4>Teacher name: <small class="text-secondary"><?php echo $mt_teacher ?></small></h4>
                    <h4>Duration of each meeting (minutes): <small class="text-secondary"><?php echo $mt_duration ?></small></h4>
                    <h4>Deadline time: <small class="text-secondary"> <?php echo $mt_deadline ?> </small></h4>
                    <h4>Meeting code: <small class="text-secondary"> <?php echo $_POST['code'] ?> </small></h4>
                    <h4>Student ID: <small class="text-secondary"> <?php echo $_POST['studentid'] ?> </small></h4>
                    
                    <!-- <h4>Total number of days: <small class="text-secondary"> <?php echo $daynum ?> </small></h4>
                    <h4>Total timeslot for students: <small class="text-secondary"> <?php echo $total ?> </small></h4> -->


                    <!-- The form for selecting time slots -->
                    <form action="choose2.php" id="formchoose" method="post" enctype="multipart/form-data">
                    
                    <!-- Hidden input fields to store the student ID and code -->
                    <label for="studentid" class=""></label>
                    <input type="hidden" id="studentid" name="studentid" value="<?php echo $_POST['studentid'] ?>">

                    <label for="code" class=""></label>
                    <input type="hidden" id="code" name="code" value="<?php echo $_POST['code'] ?>">

                    <?php
                    // Initialize the array to store selected times
                    $selected_times = array();

                    // Loop through each day and display the available time slots
                    for ($x = 1; $x <= $daynum; $x++) {
                        $y = $x - 1;

                        // echo "Days array: " . print_r($days, true) . "<br>"; //debugging code
                        // echo "Timeslots array: " . print_r($mt_timeslots, true) . "<br>";

                        // Display the day and number of choices for the current day
                        print "<label for='code' class='form-label'> $x - $days[$y] , Choice - $numbers[$y] </label>";
                        
                        // Initialize an array to store the selected time slots for the current day
                        $day_selected_times = array();

                        // var_dump($days); //debugging code
                        // echo "y: $y\n";

                        // Display a dropdown options for each choice of time slot for the current day
                        for ($z = 1; $z <= $numbers[$y]; $z++) {
                            ?>
                            <div class="mb-3">  
                                <select class="form-select time-select" name="day<?php echo $x ?>choose<?php echo $z ?>" id="day<?php echo $x ?>choose<?php echo $z ?>"  required>
                                    <option disabled selected hidden>Click to select time slots</option>
                                    <?php
                                    // Loop through each time slot and display the ones available on the current day
                                        foreach ($mt_timeslots as $value) {
                                            if (str_starts_with($value,$days[$y])) {
                                                echo "<option value=\"$value\">$value</option>";
                                                // Store the selected time slot in the array
                                                $day_selected_times[] = $value;                                        
                                            }
                                        }                                        
                                        
                                    ?>
                                </select>
                            </div>
                            <?php
                        }
                        // Store the selected time slots for the current day in the array
                        $selected_times[$days[$y]] = $day_selected_times;
                    }
                    // Store the selected options in a hidden field
                    echo '<input type="hidden" name="selected_options" value="'.htmlspecialchars(json_encode($selected_times)).'">';

                    ?>

                    <div class="row">
                        <div class="col-6">
                            <div class="d-grid">
                                <button type="button" id="reselect" class="btn btn-primary fw-bold text-white" >Reselect</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button type="button" id="submitbtn" class="btn btn-poly fw-bold text-white" >Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<body>
<html>


<script src="/scripts/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function(){
    $('select.time-select').on('change', function() {
        var selected = $(this).val();
        $('select.time-select').not(this).find('option[value="' + selected + '"]').prop('disabled', true);
    });

    // Reselect button click event
    $('#reselect').click(function() {
        $('select.time-select').prop('selectedIndex', 0);
        $('select.time-select').find('option').prop('disabled', false);
    });
    
    // Submit button click event
    $('#submitbtn').click(function() {
        $('form').submit();
    });

});
</script>