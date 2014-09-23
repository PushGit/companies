<?php
$link = mysqli_connect('localhost', 'root', '435123451', 'companies');
if (!$link) {
    die('Ошибка соединения: ' . mysql_error());
}

// $del = $_REQUEST['del'];
function fdelete($del) { 

	//echo $del;
	$strSQL = "DELETE FROM companies WHERE id=$del";
	mysqli_query($link, $strSQL); 

	header("Location:http://localhost/companies/index.php?page=companies");
}

fdelete($_REQUEST['del']);
?>