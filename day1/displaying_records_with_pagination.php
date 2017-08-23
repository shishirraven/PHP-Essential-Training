<?php 
include("connection.php");
include("library/pageination_extended.php");

$config['query'] =  "select * from user";
$config['con'] = $con;
$config['rows_per_page'] = 1;
$config['page_no_variable']="page_no";
$rec_page = new pagination($config);
$row_array  = $rec_page->get_array();

?>
<html>
	<head>
		<!-- Latest compiled and minified CSS & JS -->
		<link rel="stylesheet" media="screen" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</head>
	<body>
	<div class="container">

	<h1>Users</h1>
		
	<table class="table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
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


 
	<?php 
	$rec_page->show_links_google_type();
	 ?>
	</div>

		
		
	</body>
</html>