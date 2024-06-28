<?php
session_start();
$studentid = $_SESSION["studentid"];
$studentIds = explode(",", $_POST['studentid']);
$studentIds = array_map('trim', $studentIds);

date_default_timezone_set("Asia/Hong_Kong");
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
//include "conn.php";

function generate_random_password($length = 8) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="icon" href="images/favicon.ico" />
  <title>Online Reservation System</title>
  <link rel="stylesheet" href="styles/bootstrap.min.css">
  <link rel="stylesheet" href="styles/main.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script>
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
                <a class="link password" style="background-color:rgb(156, 255, 253, 0.43);" href="createpassword.php">Create password</a>
                <a class="link choice" style="pointer-events: none; color: rgb(142, 168, 190);" href="checkchoose.php">Check your choice</a>
                <a class="link result" href="teachercheckresult.php">Check result</a>
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
            <button class="btn btn-info" type="submit">Logout</button>
        </form>
        </div>
    </div>

    <div class="container">
        <main class="w-100 m-auto" id="main">
            <div class="card py-md-5 py-2 px-sm-2 px-md-5 my-5 w-100">
                <div class="card-body">
                    <h1 class="mb-4 text-dark">Password are successfully generated!</h1>
                        <table class="password-table" id="password-table"></table>

                        <?php
                        $stmt = $conn->prepare('REPLACE INTO `data` (`username`, `password`, `type`) VALUES (?, ? ,?);');

                        echo '<table class="table mt-5" id="password"><tr><th>Student ID</th><th>Password</th></tr>';
                        // loop through the data sent via the HTML form and insert it into the database
                        foreach ($studentIds as $id) {
                            $password = generate_random_password();
                            
                            echo '<tr><td>'.$id.'</td><td>'.$password.'</td></tr>';

                            $type = 'student';
                            $stmt->bind_param("sss", $id, $password, $type);
                            $stmt->execute();
                        }
                        echo '</table>';
                        $stmt->close();
                        ?>

                        <div id="excel_data" class="mt-5"></div>
                        <button onclick="generateExcelFile()">Download Excel</button><br><br>
                </div>
          </div>
      </div>
    </div>
  </main>
</div>

    <!-- <footer>
        <div class="text-center p-2" style="background-color: rgb(146, 202, 255);">
            Copyright © 2023 The Hong Kong Polytechnic University. All Rights Reserved.
        </div>
    </footer> -->
</body>
</html>

<script>
function generateExcelFile() {
  // Select the table to be converted to Excel
  const table = document.getElementById("password");

  // Convert the table to a worksheet object
  const worksheet = XLSX.utils.table_to_sheet(table);

  // Convert the worksheet object to a workbook object
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "Passwords");

  // Convert the workbook object to a binary string
  const excelBuffer = XLSX.write(workbook, { bookType: "xlsx", type: "array" });

  // Create a Blob object from the binary string
  const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

  // Create a download link and trigger the download
  const downloadLink = document.createElement("a");
  downloadLink.href = URL.createObjectURL(blob);
  downloadLink.download = "passwords.xlsx";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}
</script>

