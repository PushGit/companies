<?php
setcookie("companyID", $companyID, time() - 3600*24*30*12, "/");
setcookie("userID", $userID, time() - 3600*24*30*12, "/");
setcookie("log", $_POST["login"], time() - 3600*24*30*12, "/");
setcookie("pa", md5($_POST["passw"]), time() - 3600*24*30*12, "/");
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit();
?>