# PHP Essentials. 

The most essential skill while learning PHP is how to link it with database. 
So we are going to focus on the following 4 aspects. 

- How to display data which resides inside tables inside database. 
- How to insert data into a table. 
- How to edit the data. 
- How to Delete data. 


## Step 1 : Connection with Database. 

Database can be on the same server and it can also be remote. I.e located physically somewhere else. 
For security connections are password protected. 
So essentially you need. 

- Host Name : Address of the Server. Can be an IP address or domain name. 
- Database Name : to which server you want to connect. 
- Username : 
- Password 


``` php
<?php 

$hostname			= "localhost";
$database_name			= "training_database";
$username			= "root";
$password			= "";

$con= mysqli_connect($hostname,$database_name,$username,$password);
/* Checking to see if the connection is working. */
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>
```

## Step 2 : Displaying Values from the Database Table. 

Include the connection file into your page so that you can have connection. 
``` php
<?php include("connection.php"); ?>
```

Create a Query so that you can have result set and then you can loop the values which are returned. 
``` php
	<table border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
			</tr>
		</thead>
		
			<?php 
			 $user_sql_query = "select * from user";
			 $user_step_result = mysqli_query($con,$user_sql_query) or die(mysqli_error($con));
			 while($user_row = mysqli_fetch_array($user_step_result))
			 {
			 	?>
			 	<tr>
				 	<td><?php echo  $user_row['id']; ?></td>
				 	<td><?php echo  $user_row['name']; ?></td>
			 	</tr>
			 	<?php
			 }
			  ?>
	</table>
```

> By just following the above steps you can list rows from the database on your page. 

***

# Displaying the Records using a Pagination Library. 

Dependency [Brave CMS Pagination Library](https://github.com/shishirraven/Brave-CMS-Library)

When we want to display records in Page. For Example 10 records in 1 page and then other 10 on others. 

We will use Brave CMS Library to achive this easily. 

Once Library is kept you need to speicify. 
- SQL Connection
- SQL Query
- Number of Records Per Page you want to list

And the Library automatically returns 
An Array that you can Loop using foreach. 

## Step 1 Include library into your php file as shown below. 

```php
<?php 
include("connection.php");
include("library/pageination_extended.php");
?>
```
## Step 2  Configure the Library as showing in example below. 

```php
<?php
$config = array();
$config['query'] =  "select * from user";
$config['con'] = $con;
$config['rows_per_page'] = 1;
$config['page_no_variable']="page_no";

$pagination = new pagination($config);
$result_array  = $pagination->get_array();
?>
```
## Step 3 Loop the data into table to display

```php
<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
		</tr>
	</thead>
	<?php 
	foreach ($result_array as $user_row) 
	{
	 	?>
	 	<tr>
		 	<td><?php echo  $user_row['id']; ?></td>
		 	<td><?php echo  $user_row['name']; ?></td>
	 	</tr>
	 	<?php
	 }
	  ?>
</table>
```

## Step 4 : Adding Navigation bar to move between Pages.  

```php
<?php 
$pagination->show_links_google_type();
 ?>
```
You can place this anywhere you want to display the links. 

## Step 5 : Per Page Selector. 
You can optionally allow the users to select how many records they want to display on a page. 
Using the following code. 

``` php
	<?php echo $pagination->perpage_selector(); ?>
```

## Step 6 : Total No. of Records
To show total no of Records to let the user know. 
You can use the following code. 

```php
<p>
Total Records : <?php echo $pagination->show_total_records(); ?>
</p>
```

## Step 7 : If you want the users to click on table header and have it sorted. You can do so in the following fashion. 

```php
<th><?php echo $pagination->sortable_label('id',"ID") ?></th>
```
So the final code should appear as following. 

```PHP
<table class="table">
		<thead>
			<tr>
				<th><?php echo $pagination->sortable_label('id',"ID"); ?></th>
				<th><?php echo $pagination->sortable_label('name',"Name"); ?></th>
			</tr>
		</thead>
			<?php 
			foreach ($row_array as $user_row) 
			{
			 	?>
			 	<tr>
				 	<td><?php echo  $user_row['id']; ?></td>
				 	<td><?php echo  $user_row['name']; ?></td>
			 	</tr>
			 	<?php
			 }
			  ?>
	</table>
```

## Step 8 Showing Table headers only when their are Records available. 

```php

if($pagination->has_results()) 
  {
    ?>
    
    <?php } ?>
```
If you wrap the tables with the above statement them the tables will appear with headers only when their are records in the 
database. 

## Step 9 Showing a No Records Message. 

```php
<?php 
if($pagination->has_no_results())
{
 ?>
 <div uk-alert>
   No Records Found
 </div>
<?php } ?>

```

***

> Other lessons are avaiable here. https://github.com/shishirraven/PHP-Essential-Training

Thank You!





