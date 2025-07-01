
<?php
session_start();
session_destroy();
header("Location: loginbootstrap.php");
exit;
