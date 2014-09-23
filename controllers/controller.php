<?php
function __autoload($class_name) {
  require_once $class_name.".php";
}

/*------      куки     ---------  */
function set_cookie($var, $n){
	setcookie("$var", $n, time() + 3600*24*30*12, "/");
}



function controller_companies_index()
{
	$id = @$_GET["id"];
	$nameCompany = @$_GET["nameCompany"];
	$adress = @$_GET["adress"];
	$phone = @$_GET["phone"];
	switch(@$_GET["action"])
	{
		case "delete": 
		$strSQL = "DELETE FROM companies WHERE id=$id";
		mysqli_query(connect(), $strSQL); 
		header("Location:http://localhost/companies/index.php");
		close_bd();
		break;
		
		case "edit":
		setcookie("editCompany", 1, time() + 3600*24*30*12, "/");
		header("Location:http://localhost/companies/index.php?page=companies&id=$id");
		break;
		
		case "view":
		header("Location:http://localhost/companies/index.php?page=products&id=$id");
		setcookie("otherCompanyID", $id, time() + 3600*24*30*12, "/");
		break;
		
	}
	$data = getCompanies();
	view_companies($data);
}

function controller_free_companies_index()
{
	
	$data = getCompanies(); //переписать!
	view_companies($data);
}

function controller_products_index()
{
	$id = @$_GET["id"];
	switch(@$_GET["action"])
	{
		case "delete": 
		$strSQL = "DELETE FROM products WHERE id=$id";
		mysqli_query(connect(), $strSQL); 
		header("Location:http://localhost/companies/index.php?page=products");
		close_bd();
		break;
		
		case "edit":
		setcookie("editProduct", 1, time() + 3600*24*30*12, "/");
		header("Location:http://localhost/companies/index.php?page=products&id=$id");
		break;
		
		case "insert":
		setcookie("insertProduct", 1, time() + 3600*24*30*12, "/");
		break;
	}
	$data = getProducts();
	////////////////////////
	if ($_COOKIE["otherCompanyID"]==0)
	{
		view_products($data);
	}
	else
	{
		view_my_products($data);
	}
}

function controller_reg_index()//регистрация
{
	view_reg();
	if(!empty($_POST['login']) and !empty($_POST['passw'])) 
	{   
		$name = $_POST['login']; 
		$res = mysqli_query(connect(), "SELECT id FROM users WHERE name = '$name'");
		$row = mysqli_fetch_row($res);
		$count = $row[0];
		close_bd();
		if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
		{
			echo "Логин может состоять только из букв английского алфавита и цифр";
		}
		elseif(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
		{
			echo "Логин должен быть не меньше 3-х символов и не больше 30";
		}
		//проверка нет ли уже такого пользователя
		elseif (!empty($count))
		{
			echo "Пользователь с таким логином уже существует в базе данных";
		}
		else
		{
			addUser();
		}
	}
}

function controller_login_index()//логинимся
{
	if ((empty($_COOKIE['log'])) && (empty($_COOKIE['pa'])))
	{ 
		view_login();
	}
	else 
	{
		getCookies();
	}
}	
//-----------------------------

function controller_pages_products()
{
	echo "<div class=\"pages\">";
	if (empty($_GET['p']))
		{
		  $_GET['p'] = 1;
		}
	$p = $_GET['p'];
	$companyID = $_COOKIE['otherCompanyID'];
	$max_items = mysqli_num_rows(mysqli_query(connect(), "SELECT * FROM products WHERE companyID = '$companyID'"));
	$num_on_page = 10;
	$pages = ceil($max_items/$num_on_page);
	for ($i=1; $i<=$pages; $i++)
	 {
		 if ($i!=$p) echo "<a href=\"http://localhost/companies/index.php?page=products&p={$i}\">{$i}</a>";
		 else echo "<b>{$i}</b>";
	 }
	echo "</div>";
	close_bd();
	setcookie("otherCompanyID", 0, time() + 3600*24*30*12, "/");
}

function controller_pages($page)
{
	//echo $page;
	echo "<div class=\"pages\">";
	if (empty($_GET['p']))
		{
		  $_GET['p'] = 1;
		}
	$p = $_GET['p'];
	//  1 2 3 4 5 <a href="...&p=6">6</a>  7 8 9 10 
	if ($page == "products")
	{
		$companyID = $_COOKIE['companyID'];
		$max_items = mysqli_num_rows(mysqli_query(connect(), "SELECT * FROM products WHERE companyID = '$companyID'"));
	}
	else
	{
		$max_items = mysqli_num_rows(mysqli_query(connect(), "SELECT * FROM companies"));
	}
	 $num_on_page = 10;
	 $pages = ceil($max_items/$num_on_page);
	 for ($i=1; $i<=$pages; $i++)
	 {
		 //echo "<form method=\"GET\" action=\"\">"
		 if ($i!=$p) echo "<a href=\"http://localhost/companies/index.php?page={$page}&p={$i}\">{$i}</a>";
		 else echo "<b>{$i}</b>";
		 //echo "</form>"
	 }
	echo "</div>";
	close_bd();
}

function main_controller()
{
	if($_GET['action']=='search')
	{
		$data = getProductsSearch();
		view_products($data);
	}
	if ((empty($_COOKIE['log'])) && (empty($_COOKIE['pa'])))
	{ 
		echo "<ul><a href=\"http://localhost/companies/index.php?page=reg\" >Зарегистрируйтесь</a>";
		echo "<li>или</li>";
		echo "<a href=\"http://localhost/companies/index.php?page=login\">Войдите</a>";
	}
	else 
	{
		$nameUser = $_COOKIE['log'];
		echo "<form action=logout.php method=post> Привет, $nameUser!<input type=submit value=Выход /></form>";
		checkUser();
	}
}

function controller_editProduct()
{
	
}

function controller_addProduct()
{
	echo "blablabla";
}

function controller_insertCompany()
{
	view_insertCompany();
	if(!empty($_POST['nameCompany'])) 
	{  
	  //проверка нет ли уже такой компании
		$nameCompany = $_POST['nameCompany']; 
		$res = mysqli_query(connect(), "SELECT id FROM companies WHERE name = '$nameCompany'");
		$row = mysqli_fetch_row($res);
		$count = $row[0];
		close_bd();
		if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['nameCompany']))
		{
			echo "Название может состоять только из букв английского алфавита и цифр";
		}
		elseif(strlen($_POST['nameCompany']) < 3 or strlen($_POST['nameCompany']) > 30)
		{
			echo "Название должно быть не меньше 3-х символов и не больше 30";
		}
		elseif (!empty($count))
		{
			echo "Компания с таким названием уже существует, придумайте другое";
		}
		else
		{
			addCompany();
		}
	}
}
function controller_insertProduct()
{
	view_insertProduct();
	if(!empty($_POST['nameProduct'])) 
	{  
		$nameProduct = $_POST['nameProduct']; 
		$price = $_POST['price']; 
		$res = mysqli_query(connect(), "SELECT id FROM products WHERE name = '$nameProduct'");
		$row = mysqli_fetch_row($res);
		$count = $row[0];
		close_bd();
		
		if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['nameProduct']))
		{
			echo "Название может состоять только из букв английского алфавита и цифр";
		}
		elseif(!preg_match("/^[0-9]+$/",$_POST['price']))
		{
			echo "Цена может состоять только цифр<br>";
		}
		elseif(strlen($_POST['nameProduct']) < 3 or strlen($_POST['nameProduct']) > 30)
		{
			echo "Название должно быть не меньше 3-х символов и не больше 30";
		}
		elseif (!empty($count))
		{
			echo "Продукт с таким названием уже существует, придумайте другое";
		}
		else
		{
			addProduct();
		}
	}
}



?>