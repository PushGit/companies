<?php
// ------------ VIEW -------------------

/*Регистрация и логин*/

function view_login()
{
	echo "<h1>Введите данные</h1>";
	echo "<form method=post action=check.php> 
	Логин
	<br><input class=input required placeholder='Введите логин' name=login value=>  
	<br><br>Пароль
	<br><input class=input required placeholder='Введите пароль' name=passw value=> 
	<input type=hidden name=enter value=yes> 
	<br><br><input class=button type=submit value=Войти>  
	</form>";
}


function view_reg()
{
	if($_COOKIE["query"]==1)
	{
		set_cookie("query","0");
		echo "<br><h2>Пользователь добавлен</h2>
		<form method=post action=http://localhost/companies/index.php> 
		<input class=button type=submit value=ОК>";
	}
	else
	{
		echo "<h1>Добавить пользователя</h1>
		<form method=post> Логин <br>
		<input class=input required placeholder='Введите логин' name=login value=>  
		
		<br><br> Пароль <br>

		<input required placeholder='Введите пароль' class=input name=passw value=> 
		<input type=hidden name=enter value=yes> 
		<br><br>
		<input class=button type=submit value=Зарегистрироваться>  
		</form>";
	}
}
/*-------------------------------------------------------------*/


function view_companies($company_list)
{
	if (@$_POST['but'] && $_COOKIE["editCompany"]==2)
		{
			editCompany();
			echo"Компания отредактирована!";
			if($_COOKIE["log"]!="")
			{
				echo "<form method=post action=http://localhost/companies/index.php> 
				<input class=button type=submit value=ОК>";
			}
			else
			{
				echo "<form method=post action=http://localhost/companies/index.php?page=companies> 
				<input class=button type=submit value=ОК>";
			}
			set_cookie("editCompany","0");
		}
	if($_COOKIE["editCompany"]==1)//редактирование компании
	{
		$id = $_GET['id'];
		
		$result = mysqli_query(connect(), "SELECT * FROM companies WHERE id = '$id'" );
		
		while ($rslt = mysqli_fetch_row($result)) 
		{ 
			$n = $rslt[1]; 
			$a = $rslt[2]; 
			$p = $rslt[3]; 
		}
		close_bd();
		echo "
		<h1>Редактировать компанию '$n'</h1>
		<form method=post>Наименование<br>

		<input class=input required name=newnameCompany value=$n>  
		<br><br>Адрес<br>

		<input class=input required name=newadress value=$a>  
		<br><br>Телефон<br>

		<input class=input required name=newphone value=$p><br><br>

		<input class=button type=submit value=Редактировать name = but>  
		</form></h1>";
		set_cookie("editCompany","2");
		}
	if($_COOKIE["editCompany"]==0 || @!$_POST['but'] && $_COOKIE["editCompany"]==2)
	{
		echo "<h1>Компании
		<form method=post action=index.php?page=insertCompany> 
		
		</form></h1>";
		echo "<table border='1'>
		<tr>
		<th>id</th>
		<th>Наименование</th>
		<th>Адрес</th>
		<th>Телефон</th>
		<th>Просмотр товаров</th>
		</tr>";
		
		foreach ($company_list as $row)
		{
		  echo "<tr>";
		  echo "<td>" . $row['id'] . "</td>";
		  echo "<td>" . $row['name'] . "</td>";
		  echo "<td>" . $row['adress'] . "</td>";
		  echo "<td>" . $row['phone'] . "</td>
		  <td><a name=\"view\" href=\"index.php?page=companies&action=view&id=".$row["id"]."\"><img src=\"list.ico\" style=\"width: 16px; height: 16px;\">Товары</a>
		  </td>\n";
		  echo "</tr>";
		  }
		echo "</table>";
		controller_pages('companies');
	}
}

function view_insertCompany()
{
	if($_COOKIE["insertCompany"]==0)
	{
		set_cookie("insertCompany","1");
		echo "<br><h2>Компания добавлена!</h2>
		<form method=post action=http://localhost/companies/index.php?page=companies> 
		<input class=button type=submit value=ОК>";
	}
	else
	{
		echo "<h1>Добавить компанию</h1>
		<form method=post action=index.php?page=insertCompany> 
		Наименование
		<br><input class=input required placeholder='Введите наименование' name=nameCompany value=>  
		<br><br>Адрес
		<br><input class=input required placeholder='Введите адрес' name=adress value=>  
		<br><br>Телефон
		<br><input class=input required placeholder='Введите телефон' name=phone value=>  
		<input type=hidden name=enter value=yes> 
		<br><br><input class=button type=submit value=Добавить name = button>  
		</form></h1>";
	}
}


function view_products($products_list)
{
	if (@$_POST['but'] && $_COOKIE["editProduct"]==2)
	{
		editProduct();
		echo"Продукт отредактирован!
		<form method=post action=http://localhost/companies/index.php?page=products> 
		<input class=button type=submit value=ОК>";
		set_cookie("editProduct","0");
	}
	if($_COOKIE["editProduct"]==1)//редактирование продукта
	{
		$id = $_GET['id'];
		$result = mysqli_query(connect(), "SELECT * FROM products WHERE id = '$id'" );
		
		while ($rslt = mysqli_fetch_row($result)) 
		{ 
			$n = $rslt[1]; 
			$p = $rslt[2]; 
		}
		echo "<h1>Редактировать продукт $n</h1>
		<form method=post > 
		Наименование
		<br><input class=input required name=newnameProduct value=$n>  
		<br><br>Цена
		<br><input class=input required name=newPrice value=$p>  
		<br><br><input class=button type=submit value=Редактировать name = but>  
		</form></h1>";
		set_cookie("editProduct","2");
	}
	if($_COOKIE["editProduct"]==0 || @!$_POST['but'] && $_COOKIE["editProduct"]==2)
	{
		echo "<h1>Продукция</h1>
		<form method=post action=index.php?page=insertProduct> 
		<br><input class=button type=submit value=Добавить>
		</form>";
		echo "<table border='1'>
		<tr>
		<th>id</th>
		<th>Товар</th>
		<th>Стоимость</th>
		<th>Выбор действия</th>
		</tr>";

		foreach ($products_list as $row)
		  {
			  echo "<tr>";
			  echo "<td>" . $row['id'] . "</td>";
			  echo "<td>" . $row['name'] . "</td>";
			  echo "<td>" . $row['price'] . "</td>";
			  echo "<td><a name=\"del\" href=\"index.php?page=products&action=delete&id=".$row["id"]."\"><img src=\"delete.png\" style=\"width: 16px; height: 16px;\"> Удалить</a>
			  <a name=\"edit\" href=\"index.php?page=products&action=edit&id=".$row["id"]."\"><img src=\"edit.png\" style=\"width: 16px; height: 16px;\">Редактировать</a>
			  </td>\n";
			  echo "</tr>";
		  }
		echo "</table>";
		controller_pages('products');
	}
}

function view_insertProduct()
{
	if($_COOKIE["insertProduct"]==0)
	{
		set_cookie("insertProduct","1");
		echo "<br><h2>Продукт добавлен!</h2>";
		echo "<form method=post action=http://localhost/companies/index.php?page=products> 
		<input class=button type=submit value=ОК>";
	}
	else
	{
		echo "<h1>Добавить продукт</h1>
		<form method=post action=index.php?page=insertProduct> 
		Наименование
		<br><input class=input required placeholder='Введите наименование' name=nameProduct value=>  
		<br><br>Cтоимость
		<br><input class=input required placeholder='Введите стоимость' name=price value=>  
		<input type=hidden name=enter value=yes> 
		<br><br><input class=button type=submit value=Добавить name = button>  
		</form></h1>";
	}
}

function view_my_products($products_list)
{
	$companyID = $_GET['id'];
		$result = mysqli_query(connect(), "SELECT name FROM companies WHERE id = '$companyID'" );
		
		while ($rslt = mysqli_fetch_row($result)) 
		{ 
			$n = $rslt[0]; 
		}
		close_bd();
		echo "<h1>Продукция компании '$n' <form method=post> 
		
		</h1></form>";
		echo "<table border='1'>
		<tr>
		<th>Товар</th>
		<th>Стоимость</th>
		</tr>";

		foreach ($products_list as $row)
		  {
			  echo "<tr>";
			  echo "<td>" . $row['name'] . "</td>";
			  echo "<td>" . $row['price'] . "</td>
			  </td>\n";
			  echo "</tr>";
		  }
		echo "</table>";
		controller_pages_products($companyID);
}


?>
 