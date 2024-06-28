<html><body>
<?php
session_start();
date_default_timezone_set("Asia/Hong_Kong");
$servername = "localhost";  // Replace with your server name or IP address
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "capstone_project";   // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

    $studentname = $_POST['studentname'];
    $studentid = $_POST['studentid'];  
    $password = $_POST['password'];
      
        //to prevent from mysqli injection
        $studentname = stripcslashes($studentname);  
        $studentid = stripcslashes($studentid);  
        $password = stripcslashes($password);

        $sql = "Select * From data where username = '$studentid' and password = '$password'";  
        $result = mysqli_query($conn, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);
          
        if($row["type"] == 'student'){
            $_SESSION['studentname'] = $row["studentname"];
            $_SESSION['studentid'] = $studentid;
            $_SESSION['type'] = $row["type"]; 
            header("Location: main.php?id={$_POST['studentid']}");
        }

        elseif($row["type"] == 'teacher'){
            $_SESSION['studentname'] = $row['studentname'];
            $_SESSION['studentid'] = $studentid;
            $_SESSION['type'] = $row["type"];  
            header("Location: main.php?id={$_POST['studentid']}");
        }

        else{
            ?>
            <script>
                    alert("Login failed. Invalid studentID or password.");
                    window.location.href = 'index.html';
            </script>
            <?php 
        }
?> 
</body></html>

