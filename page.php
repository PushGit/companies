<?php 
//Controller::index()

// точка входа
// 8 903 983 0803
//FrontController - index.php
/*FrontController extends Controller
FrontController::index(){
///... switch ($_GET["page"]) -- какую страницу грузим
{
	
}
}

/*CompanyController extends Controller
CompanyController::index() {
switch($_GET["action"])
{
// какое дейтствие на странице выполняем - удаление или показ

}

}*/

/*СompanyModel::getCompanies()
{
	$ret = array();
	$result = mysqli_query($link, "SELECT * FROM companies LIMIT 0,10" );
	while($row = mysqli_fetch_array($result))
	{
		$ret[] = $row;
	}
	return $ret;
}*/

/*class CompanyView
{
	function viewCompanyList($company_list)
	{
		echo "<table border='1'>
		<tr>
		<th>id</th>
		<th>NAME</th>
		<th>DELETE</th>
		</tr>";
		foreach ($company_list as $row)
		  {
			  echo "<tr>";
			  echo "<td>" . $row['id'] . "</td>";
			  echo "<td>" . $row['name'] . "</td>";
			  echo "<td><a name=\"del\" href=\"index.php?page=companies&action=delete&id=".$row["id"]."\"><img src=\"delete.png\" style=\"width: 16px; height: 16px;\"> Удалить</a></td>\n";
			  echo "</tr>";
		  }
		echo "</table>";
	}
}*/
?>