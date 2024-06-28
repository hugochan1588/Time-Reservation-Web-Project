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
                    <h1 class="mb-4 text-dark">Create password</h1>
                    <form id="form" action="createpasswordsql.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="studentid" class="form-label">Student ID </label><br>
                            <label for="studentid" class="form-label"> <small>(use commas to separate more than one id, example: 20032741d, 20032743d, 20032752d)</small></label>
                            <textarea class="form-control" id="studentid" name="studentid" rows="3" required></textarea>

                            <input id="file_upload" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            <button onclick="upload()">Upload in Text</button>   
                            <br>
                            <br>
                            <!-- table to display the excel data -->
                            <table style="width: auto;"id="display_excel_data" border="1"></table>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-secondary fw-bold text-white" >Submit</button>
                        </div>
                    </form>
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
     
  // Method to upload a valid excel file
  function upload() {
    var files = document.getElementById('file_upload').files;
    if(files.length==0){
      alert("Please choose any file...");
      return;
    }
    var filename = files[0].name;
    var extension = filename.substring(filename.lastIndexOf(".")).toUpperCase();
    if (extension == '.XLS' || extension == '.XLSX') {
        excelFileToJSON(files[0]);
    }else{
        alert("Please select a valid excel file.");
    }
  }
   
  //Method to read excel file and convert it into JSON 
  function excelFileToJSON(file){
      try {
        var reader = new FileReader();
        reader.readAsBinaryString(file);
        reader.onload = function(e) {

            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type : 'binary'
            });
            var result = {};
            var firstSheetName = workbook.SheetNames[0];
            //reading only first sheet data
            var jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheetName]);

            //displaying the json result into text
            displayJsonToText(jsonData);
            }
        }catch(e){
            console.error(e);
        }
  }

  //Method to display the data in text form
  function displayJsonToText(jsonData){
  var textarea=document.getElementById("studentid");
  if(jsonData.length>0){
    var ids = '';
    for(var i=0;i<jsonData.length;i++){
        var row=jsonData[i];
        ids += row["ID"] + ',';
    }
    textarea.value = ids.slice(0, -1); // remove the last comma
  }else{
    textarea.value = 'There is no data in Excel';
  }
}
</script>

<script>
function generatePassword() {
  // Read the student IDs from the textarea
  const studentIds = document.getElementById("studentid").value
    .split(",")
    .map(id => id.trim());

  // Create a table to display the student IDs and passwords
  const table = document.getElementById("password-table");
  table.innerHTML = "<tr><th>Student ID</th><th>Password</th></tr>";
  
  // Generate a random password for each student
  studentIds.forEach(id => {
    const password = Math.random().toString(36).slice(-8);
    // sqlStatements += `('${id}', '${password}', 'student'),`;
    table.innerHTML += `<tr><td>${id}</td><td>${password}</td></tr>`;
  });
  
  // Set the value of the textarea to the SQL statements
//   const sql = document.getElementById("sql");
//   sql.value = sqlStatements.slice(0, -1);
//   sql.style.display = "block";
}


function generateExcelFile() {
  // Select the table to be converted to Excel
  const table = document.getElementById("password-table");

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