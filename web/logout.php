<html><body>
<?php
session_start();
$studentid = '';
session_destroy();
header("Location: index.html");
exit();
?>
</body></html>