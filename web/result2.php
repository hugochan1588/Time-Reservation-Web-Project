<?php
date_default_timezone_set("Asia/Hong_Kong");
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
//include "conn.php";

session_start();
$studentid = $_SESSION["studentid"];

// Step 1: Get the meeting details from the database
$stmt = $conn->prepare("SELECT * FROM `meeting` WHERE `uuid` = ? ");
$stmt->bind_param("s", $_GET['uuid']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {

        $mt_deadline = $row['deadline'];
        $mt_timeslots = json_decode($row['timeslots'], true);

        $mt_title = $row['title'];
        $mt_subject = $row['subject'];
        $mt_teacher = $row['teacher'];
        $mt_duration = $row['duration'];
        $mt_deadline = $row['deadline'];

        $stmt->free_result();
        $stmt->close();

        // Step 2: Get the student choices and sort them by submission time
        $stmt = $conn->prepare("SELECT * FROM `choices` WHERE `code` = ? ORDER BY `submission_time` ASC");
        $stmt->bind_param("s", $_GET['uuid']);
        $stmt->execute();
        $result = $stmt->get_result(); //$result here already is sorted by the submission time, the students submitted the preference first will be in the first row.

        // Step 3: Assign timeslots to each student
        $assignments = array(); //Creating an empty array to store the result

        while ($row = $result->fetch_assoc()) {
            $student_id = $row['studentid'];
            $selected_times_string = $row['selected_time'];
            $selected_times = explode(", ", $selected_times_string); //Make every selected times into one array

            foreach ($selected_times as $selected_time) {
                if (in_array($selected_time, $mt_timeslots)) { //Checks if the selected time is in the timeslots array created by the teacher
                    // && time() <= strtotime($mt_deadline)
                    if (!array_key_exists($selected_time, $assignments)) {
                        $assignments[$selected_time] = array();
                    }
                    if (count($assignments[$selected_time]) < 1) { //Checks if a student is assigned to the timeslot
                        $assignments[$selected_time][] = $student_id; //If no student is assigned, the current student will be assigned to that timeslot
                        break;
                    }
                }
            }
        }
    }
}

$stmt->free_result();
$stmt->close();

if (isset($assignments)) {
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
                <a class="link create" href="createmeeting2.php">Create meeting</a>
                <a class="link password" href="createpassword.php">Create password</a>
                <a class="link choice" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkchoose.php">Check your choice</a>
                <a class="link result" style="background-color:rgb(156, 255, 253, 0.43);" href="teachercheckresult.php">Check result</a>
                <a class="link status" href="teachercheckstatus.php">Check status</a>
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

                <h1 class="mb-4 text-dark">Time slot allocation results</h1>

                <h4>Meeting title: <small class="text-secondary"> <?php echo $mt_title ?></small></h4>
                <h4>Subject title: <small class="text-secondary"><?php echo $mt_subject ?></small></h4>
                <h4>Teacher name: <small class="text-secondary"><?php echo $mt_teacher ?></small></h4>
                <h4>Duration of each meeting (minutes): <small class="text-secondary"><?php echo $mt_duration ?></small></h4>
                <h4>Deadline time: <small class="text-secondary"> <?php echo $mt_deadline ?> </small></h4>
                <h4>Meeting code: <small class="text-secondary"> <?php echo $_GET['uuid'] ?> </small></h4>

                    <?php
                    // Step 4: Display the final assignments
                    echo "<h2>Final Assignments:</h2>";

                    // Add all meeting timeslots to assignments array
                    foreach ($mt_timeslots as $timeslot) {
                        if (!array_key_exists($timeslot, $assignments)) {
                            $assignments[$timeslot] = array();
                        }
                    }

                    // sort the assignments by timeslot in ascending order
                    ksort($assignments);
                    $assignmentsjson = json_encode($assignments);

                    // Prepare SQL statement
                    $sql = "REPLACE INTO `result` (`uuid`, `result`) VALUES ('{$_GET['uuid']}', '{$assignmentsjson}')";
                    $conn->query($sql);

                    // Create a table to display the final assignments
                    echo "<table class='table mt-5'>";
                    echo "<thead><tr><th>Time Slot</th><th>Student ID(s)</th></tr></thead>";
                    echo "<tbody>";

                    foreach ($assignments as $timeslot => $student_ids) {
                        if (!empty($student_ids)) {
                                echo "<tr>";
                                echo "<td>{$timeslot}</td>";
                                echo "<td>" . implode(", ", $student_ids) . "</td>";
                                echo "</tr>";
                        }
                        else{
                            echo "<tr>";
                            echo "<td>{$timeslot}</td>";
                            echo "<td>No Student</td>";
                            echo "</tr>";

                        }
                    }

                    // Get all student IDs for the meeting
                    $stmt = $conn->prepare("SELECT `studentid` FROM `meeting` WHERE `uuid` = ?");
                    $stmt->bind_param("s", $_GET['uuid']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $all_students = array(); // store all student IDs
                    while ($row = $result->fetch_assoc()) {
                        $student_id = $row['studentid'];
                        $id = json_decode($student_id);
                        $all_students = array_merge($all_students, $id);
                    }

                    // Get assigned student IDs
                    $assigned_students = array();
                    foreach ($assignments as $student_ids) {
                        $assigned_students = array_merge($assigned_students, $student_ids);
                    }

                    // Get unassigned student IDs
                    $unassigned_students = array_diff($all_students, $assigned_students);

                    // Display unassigned students
                    if (!empty($unassigned_students)) {
                        echo "<tr>";
                        echo "<td>Unassigned Students</td>";
                        echo "<td>" . implode(", ", $unassigned_students) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";

                    // Create a button to download the assignments as an Excel file
                    echo "<button onclick='generateExcelFile()'>Download as Excel</button>";
                    ?>
                    </tbody>

            </div>
        </div>
    </main>

</div>




</body>
</html>

<?php
}
?>

<script>
function generateExcelFile() {
  // Select the table to be converted to Excel
  const table = document.querySelector("table");

  // Convert the table to a worksheet object
  const worksheet = XLSX.utils.table_to_sheet(table);

  // Convert the worksheet object to a workbook object
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "Assignments");

  // Convert the workbook object to a binary string
  const excelBuffer = XLSX.write(workbook, { bookType: "xlsx", type: "array" });

  // Create a Blob object from the binary string
  const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

  // Create a download link and trigger the download
  const downloadLink = document.createElement("a");
  downloadLink.href = URL.createObjectURL(blob);
  downloadLink.download = "assignments.xlsx";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}

</script>