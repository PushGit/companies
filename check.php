<?php
function connect()
{
	$link  = mysqli_connect('localhost', 'root', '7215', 'companies');
		if (!$link) {
		die('Ошибка соединения: ' . mysqli_error());
	}
	return $link;
}

function close_bd()
{
	mysqli_close(connect());
}

$login = $_POST["login"];
$userID = "";
$companyID = "";
	$result = mysqli_query(connect(), "SELECT id FROM users WHERE name = '$login'");
	while ($rslt = mysqli_fetch_row($result)) 
	{ 
		$userID = $rslt[0]; 
	}
		
	$result = mysqli_query(connect(), "SELECT id FROM companies WHERE userID = '$userID'");
	while ($rslt = mysqli_fetch_row($result)) 
	{ 
		$companyID = $rslt[0]; 
	}
	close_bd();
setcookie("companyID", $companyID, time() + 3600*24*30*12, "/");
setcookie("userID", $userID, time() + 3600*24*30*12, "/");
setcookie("log", $_POST["login"], time() + 3600*24*30*12, "/");
setcookie("pa", md5($_POST["passw"]), time() + 3600*24*30*12, "/");
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit();
?>
