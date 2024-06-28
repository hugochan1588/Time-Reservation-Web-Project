<?php
date_default_timezone_set("Asia/Hong_Kong");
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
//include "conn.php";

// initialize the array that will hold the numbers for each day
$final_days = array();
for ($x = 1; $x <= $_POST["total"]; $x++) {

    $days = $_POST["choice{$x}"];
    array_push($final_days, $days);
}

$timeslots = json_encode($final_days);
$timeslots_str = implode(", ", $final_days);

$time = $_POST["time"];
$code = $_POST["code"];
$studentid = $_POST["studentid"];

$stmt = $conn->prepare('REPLACE INTO `choices` (`studentid`, `code`, `selected_time`, `submission_time`) VALUES (?, ? ,? ,?);');

$stmt->bind_param("ssss", $studentid, $code, $timeslots_str, $time);

$stmt->execute();

$stmt->close();

header("Location: successchoose.php?uuid={$code}&password={$password}&success");
?>
