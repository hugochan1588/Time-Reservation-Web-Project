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
                <a class="link create" style="background-color:rgb(156, 255, 253, 0.43);" href="createmeeting2.php">Create meeting</a>
                <a class="link password" href="createpassword.php">Create password</a>
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
                    <h1 class="mb-4 text-dark">Create meeting</h1>
                    <form id="form" action="createmeetingsql.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Meeting title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject title</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>

                        <div class="mb-3">
                            <label for="teacher" class="form-label">Teacher name</label>
                            <input type="text" class="form-control" id="teacher" name="teacher" required>
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration of each meeting (minutes)</label>
                            <input type="number" class="form-control" id="duration" name="duration" required>
                        </div>

                        <div class="mb-3">
                            <label for="deadline" class="form-label">Deadline time for input preference</label>
                                <input type="datetime-local" class="form-control" id="deadline" name="deadline" required>
                        </div>

                        <!-- <div class="mb-3">
                            <label for="total" class="form-label">Total timeslot for students</label>
                                <input type="number" class="form-control" id="total" name="total" required>
                        </div> -->


                    <div class="mb-3 " id="daybox">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="d-inline-block">Meeting day :</h3> 
                                <button type="button" id="adday" class="btn btn-poly fw-bold text-white mb-2 ms-3">Add</button>
                            </div>
                        </div>

                        <div class="card bg-light mx-1 my-3">
                        <div class="card-body">
                            <h5>Day 1 </h5>

                            <div class="col-12">
                            <label for="day1date" class="form-label ">Date : </label>
                            <div class="row">
                                <div class="col mb-2">
                                <input type="date" class="form-control"  id="day1date" name="day1date"  required>
                                </div>
                            </div>
                            </div>

                            <div class="col-12">
                            <label  class="form-label fw-bold">Time Periods : </label>
                            <button type="button" class="btn btn-poly fw-bold text-white mb-2 ms-3 addtimeslot" day="1" >Add</button>

                            <div class="row">
                                <div class="col-6">
                                <label class="form-label ">Start time: </label>
                                <div class="row">
                                    <div class="col mb-2">
                                    <input type="time" class="form-control"   name="day1startime[]" maxlength="100" required>
                                    </div>
                                </div>
                                </div>

                                <div class="col-6">
                                <label class="form-label ">End time : </label>
                                <div class="row">
                                    <div class="col mb-2">
                                    <input type="time" class="form-control" name="day1endtime[]" maxlength="100" required>
                                    </div>
                                </div>
                                </div>

                                <div class="col-6">
                                <label class="form-label"> Number of Preferred Timeslots </label>
                                <div class="row">
                                    <div class="col mb-2">
                                    <input type="number" class="form-control" name="day1number" required>
                                    </div>
                                </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
          </div>

            <div class="mb-3">
              <label for="studentid" class="form-label">Student ID </label><br>
              <label for="studentid" class="form-label"> <small>(use commas to separate more than one id, example: 20032741d, 20032743d, 20032752d)</small></label>
              <textarea class="form-control" id="studentid" name="studentid" rows="3" required></textarea>

              <input id="file_upload" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
              <button onclick="upload()">Upload in Text</button>   
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-poly fw-bold text-white" >Submit</button>
            </div>
            <input type="hidden" id="daycount" name="daycount" value="1">
          </form>
      </div>
    </div>
  </main>
</div>

</body>
</html>

<script>
     
  //Upload a valid excel file
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
   
  //Read excel file and convert it into JSON 
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
            //Read the first sheet data
            var jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheetName]);

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

<script src="/scripts/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {

    var add_day_button = $("#adday");
    var daycount=2;

    $('#daybox').on("click", ".addtimeslot", function(e) {
      e.preventDefault();

      $(this).parent().append('<div class="row"> <div class="col"> <div class="row"> <div class="col mb-2"> <input type="time" class="form-control"   name="day'+$(this).attr("day")+'startime[]" maxlength="100" required> </div> </div> </div> <div class="col"> <div class="row"> <div class="col mb-2"> <input type="time" class="form-control"   name="day'+$(this).attr("day")+'endtime[]" maxlength="100" required> </div> </div> </div> <div class="col-auto"> <button type="button" class="btn btn-danger m-0  delete fw-bold">Delete</button> </div> </div>');
    });


    $(add_day_button).click(function (e) {
      e.preventDefault();

      $(this).parent().parent().parent().append('<div class="card bg-light mx-1 my-3"> <div class="card-body"> <div class="d-flex justify-content-between"> <h5 class="card-title">Day '+daycount+'</h5> <a class="btn btn-danger  fw-bold delete " id="day'+daycount+'delete" day="'+daycount+'"   role="button">Delete</a> </div> <div class="col-12"> <label for="day'+daycount+'date" class="form-label ">Date : </label> <div class="row"> <div class="col mb-2"> <input type="date" class="form-control"  id="day'+daycount+'date" name="day'+daycount+'date"  required> </div> </div> </div> <div class="col-12"> <label  class="form-label fw-bold">Time Periods : </label> <button type="button" class="btn btn-poly fw-bold text-white mb-2 ms-3 addtimeslot" day="'+daycount+'" >Add</button> <div class="row"> <div class="col-6"> <label class="form-label ">Start time: </label> <div class="row"> <div class="col mb-2"> <input type="time" class="form-control"   name="day'+daycount+'startime[]" maxlength="100" required> </div> </div> </div> <div class="col-6"> <label   class="form-label ">End time : </label> <div class="row"> <div class="col mb-2"> <input type="time" class="form-control"   name="day'+daycount+'endtime[]" maxlength="100" required> </div> </div> </div> <div class="col-6"> <label class="form-label"> Number of Preferred Timeslots </label> <div class="row"> <div class="col mb-2"> <input type="number" class="form-control" name="day'+daycount+'number" required> </div> </div> </div> </div> </div> </div> </div>');

      if (daycount>2){
        $('#day'+(daycount-1)+'delete').addClass("disabled");
      }

      $('#daycount').val(daycount);
      daycount++;
    });

    $('#daybox').on("click", ".delete", function(e) {
      e.preventDefault();


      if ( $(this).attr("day") != null){
        $('#day'+(daycount-2)+'delete').removeClass("disabled");
        $('#daycount').val(daycount-2);
        daycount--;
        $(this).parent('div').parent('div').parent('div').remove();
      }else{
        $(this).parent('div').parent('div').remove();
      }
    })
  })
</script>