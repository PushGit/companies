<?php

/*////////////////// Подключение к базе ////////////////////////////*/

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
/*-----------------------------------------------------------------*/


function getCompanies($params = array())
{
	if (empty($_GET["p"]))
	{
	  $p = 1;
	}
	else $p = $_GET["p"];
	 
	$num_on_page = 10;
	$from = ($p-1)*$num_on_page;
	
	$ret = array();
	$result = mysqli_query(connect(), "SELECT * FROM companies LIMIT {$from}, {$num_on_page}" );
	
  while($row = mysqli_fetch_array($result))
  {
	$ret[] = $row;
  }
  return $ret;
  close_bd();
}

function getProducts()
{
	if (empty($_GET["p"]))
	{
	  $p = 1;
	}
	else $p = $_GET["p"];
	 
	$num_on_page = 10;
	$from = ($p-1)*$num_on_page;
	if($_COOKIE["otherCompanyID"]==0)
	{
		$companyID = $_COOKIE['companyID'];
	}
	else
	{
		$companyID = $_COOKIE['otherCompanyID'];
	}
	$ret = array();
	$result = mysqli_query(connect(), "SELECT * FROM products WHERE companyID = $companyID LIMIT {$from}, {$num_on_page}" );
	
  while($row = mysqli_fetch_array($result))
  {
	$ret[] = $row;
  }
  return $ret;
  close_bd();
}

function addUser()//добавление пользователя
{
	$pas = md5(trim($_POST['passw']));
	$log = trim($_POST['login']);
	mysqli_query(connect(), "INSERT INTO companies.users (name,pass) VALUES ('$log', '$pas')") or die(mysql_error());
    close_bd();
	header("Location:http://localhost/companies/index.php?page=reg");
	setcookie("query", 1, time() + 3600*24*30*12, "/");
}

function getCookies()//проверка пароля
{
	$query = "SELECT * FROM users WHERE name = '".$_COOKIE['log']."' AND pass = '".$_COOKIE['pa']."';";
	$zapros2 = mysqli_query(connect(),$query);
	if(mysqli_num_rows($zapros2) == 0) 
	{
		echo "Логин и пароль неверны! Попробуйте ещё раз!";
		echo "<form method=post action=logout.php> 
		<td><br><input class=button type=submit value=ОК> 
		</form>";
	}
	else
	{
		$nameUser = $_COOKIE['log'];
		echo "<form action=logout.php method=post> Привет, $nameUser!<input type=submit value=Выход /></form>";
	}
	close_bd();
}

function editCompany()//редактирование компании
{
	$id = $_GET['id'];
	$newadress = $_POST['newadress'];
	$newphone = $_POST['newphone'];
	$newnameCompany = $_POST['newnameCompany'];
	mysqli_query(connect(), "UPDATE companies.companies SET name = '$newnameCompany', adress = '$newadress', phone = '$newphone' WHERE id = '$id'");
    close_bd();
}

function editProduct()//редактирование продукта
{
	$id = $_GET['id'];
	$newnameProduct = $_POST['newnameProduct'];
	$newPrice = $_POST['newPrice'];
	mysqli_query(connect(), "UPDATE products SET name = '$newnameProduct', price = '$newPrice' WHERE id = '$id'");
    close_bd();
}

function addCompany()//добавление компании
{
	if($_COOKIE["log"]!="")
	{
	$nameUser = $_COOKIE['log'];
	$result = mysqli_query(connect(), "SELECT * FROM users WHERE name = '$nameUser'");
		while ($rslt = mysqli_fetch_row($result)) 
		{ 
			$id = $rslt[0]; 
		}
	$name = $_POST['nameCompany'];
	$adress = $_POST['adress'];
	$phone = $_POST['phone'];
	mysqli_query(connect(), "INSERT INTO companies (name, adress, phone, userID) VALUES ('$name','$adress', '$phone', '$id')") or die(mysqli_error());
    close_bd();
    $result = mysqli_query(connect(), "SELECT * FROM companies WHERE name = '$name'");
		while ($rslt = mysqli_fetch_row($result)) 
		{ 
			$newID = $rslt[0]; 
		}
	header("Location:http://localhost/companies/index.php");
	setcookie("insertCompany", 0, time() + 3600*24*30*12, "/");
	setcookie("companyID", $newID, time() + 3600*24*30*12, "/");
	}
	else
	{
		$name = $_POST['nameCompany'];
		$adress = $_POST['adress'];
		$phone = $_POST['phone'];
		mysqli_query(connect(), "INSERT INTO companies (name, adress, phone) VALUES ('$name','$adress', '$phone')") or die(mysqli_error());
		close_bd();
		header("Location:http://localhost/companies/index.php?page=insertCompany");
		setcookie("insertCompany", 0, time() + 3600*24*30*12, "/");
	}
}

function addProduct()//добавление продукта
{
	//if($_COOKIE["log"]!="")
	//{
		//добавляем продукт и записываем ид компании
		$companyID = $_COOKIE['companyID'];
		$name = $_POST['nameProduct'];
		$price = $_POST['price'];
		mysqli_query(connect(), "INSERT INTO products (name, price, companyID) VALUES ('$name', '$price', '$companyID')");
		close_bd();
		header("Location:http://localhost/companies/index.php");
		setcookie("insertProduct", 0, time() + 3600*24*30*12, "/");
	/*}
	else
	{
		$name = $_POST['nameProduct'];
		$price = $_POST['price'];
		mysqli_query(connect(), "INSERT INTO products (name, price) VALUES ('$name', '$price')");
		close_bd();
		header("Location:http://localhost/companies/index.php?page=insertProduct");
		setcookie("insertProduct", 0, time() + 3600*24*30*12, "/");
	}*/
}

function checkUser()
{
	$nameUser = $_COOKIE['log'];
	$result = mysqli_query(connect(), "SELECT * FROM users WHERE name = '$nameUser'");
		while ($rslt = mysqli_fetch_row($result)) 
		{ 
			$id = $rslt[0]; 
		}
	
	$result1 = mysqli_query(connect(), "SELECT * FROM companies WHERE userID = '$id'" );
		$nameCompany="";
	while ($rslt1 = mysqli_fetch_row($result1)) 
	{ 
		$nameCompany = $rslt1[1];
		$a = $rslt1[2];
		$p = $rslt1[3];
	}
	close_bd();
	if($nameCompany==null) 
	{
		echo "У вас нет компании";
		controller_insertCompany();
	}
	else
	{
		$id = $_COOKIE['companyID'];
		echo "<form action=http://localhost/companies/index.php?page=companies&action=edit&id=$id method=post> Ваша компания: $nameCompany<br>
		<input class=button type=submit value=Редактировать name = but/></form>";;
		//echo "<form action=http://localhost/companies/index.php?page=products&action=edit&id=$id method=post/></form>";
		echo "<form method=post action=index.php?page=companies&action=delete&id=$id><br><input class=button type=submit value=\"Удалить\" name = but/></form>";
		controller_products_index();
	}
    close_bd();
}
?>