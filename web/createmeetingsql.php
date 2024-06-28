<?php
date_default_timezone_set("Asia/Hong_Kong");
include $_SERVER["DOCUMENT_ROOT"] . "/conn/conn.php";
//include "conn.php";

// initialize the array that will hold the numbers for each day
$day_numbers = array();

for ($x = 1; $x <= $_POST["daycount"]; $x++) {

    $number = $_POST["day{$x}number"];
    array_push($day_numbers, $number);
}

$numbers = json_encode($day_numbers);
$numbers_str = implode(", ", $day_numbers);

$days = array();

for ($x = 1; $x <= $_POST["daycount"]; $x++) {

    $date = $_POST["day{$x}date"];
    array_push($days, $date);
    
}

$days_str = implode(", ", $days);  
  

$uuid = guidv4();

$timeslotsarray=[];

for ($x = 1; $x <= $_POST["daycount"]; $x++) {

    $date= $_POST["day{$x}date"];

    for ($y = 0; $y < count($_POST["day{$x}startime"]); $y++) {
        $startime = $_POST["day{$x}startime"][$y];
        $stoptime = $_POST["day{$x}endtime"][$y];
        $timeperiod = (strtotime($stoptime) - strtotime($startime))/60;

        if($timeperiod%$_POST["duration"]==0){

            $timeslotstart=$startime;
            $timeslotend=date("H:i", strtotime("+{$_POST["duration"]} minutes", strtotime($startime)));

            do {
                array_push($timeslotsarray,$date."_".$timeslotstart."-".$timeslotend);

                $timeslotstart=$timeslotend;
                $timeslotend=date("H:i", strtotime("+{$_POST["duration"]} minutes", strtotime($timeslotstart)));

            } while (strtotime($timeslotend)<=strtotime($stoptime));
        }
    }
}

$timeslots=json_encode($timeslotsarray);

$password=random_str(8);

$studentid=json_encode(explode(',', str_replace(' ', '', $_POST["studentid"])));

$stmt = $conn->prepare('INSERT INTO `meeting` (`id`, `uuid`, `title`, `subject`, `teacher`, `duration`, `deadline`, `numbers`, `days`, `timeslots`, `studentid` ,`password`) VALUES (NULL, ?,? ,? ,? ,? ,? ,? ,? ,? ,? ,? );');

$stmt->bind_param("ssssissssss", $uuid,  $_POST["title"] , $_POST["subject"],$_POST["teacher"] ,$_POST["duration"],$_POST["deadline"],$numbers_str, $days_str, $timeslots,$studentid , $password );

$stmt->execute();

$stmt->close();

header("Location: status.php?uuid={$uuid}&password={$password}&success");



function guidv4()
{
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}