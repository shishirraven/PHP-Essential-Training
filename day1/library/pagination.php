<?php
/*
	Descrption 			: Pagination to for MySQL Records. 
	Author 				: Shishir Raven. 
	Last Modified on 	: 23/8/2017
	Example of how to user the class starts here
	$config['query'] = "SELECT * FROM issue_comments WHERE id = '".$_REQUEST["issue_id"]."'";
	$config['rows_per_page'] = 3;
	$config['page_no_variable']="page_no";
	$rec_page = new pagination($config);
	$row_array  = $rec_page->get_array();
	// show links functions - call where you want to display the pagination links
	$rec_page->show_links()
*/

class pagination
	{
	var $query="";
	var $con="";
	var $rows_per_page=10;
	var $page_no_variable="page_no";
	var $cur_page=1;
	var $total_rows=0;
	
	var $maxs=0;
	var $mins=0;
	
	var $total_pages=0;
	var $limit="";
	var $link_page_start="";
	var $link_page_end="";
	var $link_class="";
	var $link_class_current="pagination_current";
	
	// The following Icons appears in front of the labels
	var $icon_asc="";
	var $icon_desc="";
	var $page_name="";
	
	
	// INITIALIZER
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}
	
	
	/*  When Function Called Shows a dropdown from which user can choose
		how many records he may like to display
	*/
	function perpage_selector()
	{
	?>
		<form name="per_page2" action="" method="get">
			<select name="per_page" id="per_page" onChange='location.replace("<?php
			$data= $_REQUEST;
			if(isset($data["per_page"]))
			{
				unset($data["per_page"]);
			}
			echo $_SERVER['PHP_SELF']."?".http_build_query($data); ?>&<?php echo "per_page";?>="+$("#per_page").val() )'>
			<?php 
			/* Adding a Default Value in options if it does not exist. */

			if(($this->rows_per_page%10)!=0)
			{
			 ?>
				<option selected='selected' value="<?php echo $this->rows_per_page;  ?>"><?php echo $this->rows_per_page;  ?></option>
			<?php 
			}
			?>
			<?php 
				for ($i=10; $i < 100 ; $i = $i + 10) { 
					?>
						<option <?php 
							if(isset($_GET['per_page']) && $_GET['per_page'] == $i)
							{
								echo " selected = 'selected' ";
							}
							if(!isset($_GET['per_page']) && $this->rows_per_page == $i)
							{
								echo " selected = 'selected' ";
							}
							?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
						 
					<?php
				}
			 ?>
			</select>
		</form>
		<?php
	}
	
	// CONSTRUCTOR
	function pagination($params=array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		
		}	
		if(isset($_REQUEST['per_page'])){
			$this->rows_per_page = $_REQUEST['per_page'];
			
		}
		$this->get_current_page();
		$this->query_limit();
		$this->get_pagename();
		
	}

	function get_current_page()
	{
		if(isset($_REQUEST[$this->page_no_variable]))
		{
			$this->cur_page=$_REQUEST[$this->page_no_variable];
		}
	}
	
	function sortable_label($table_field_name,$label)
	{
		$data = $_REQUEST;
		// REMOVING THE PREVIOUS sort_by COLUMN. 
		if(isset($data['sort_by']))
		{
			unset($data['sort_by']);
		}
		

		// CHECKING TO SEE IF THE LABLE VALUE IS EQUAL TO THE CURRENT SELECTION
		$sort_img ="";
		
			if(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by']==$table_field_name && ($_REQUEST['sort_direction']=="" || $_REQUEST['sort_direction']=="desc"))
			{
				$data['sort_by'] = $table_field_name;
				$data['sort_direction']='asc';
				$sort_img="<img src='".$this->icon_asc."' />";
				
			}
			else
			{
				$data['sort_by'] = $table_field_name;
				$data['sort_direction']='desc';
				if(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by']==$table_field_name)
				{
					$sort_img="<img src='".$this->icon_desc."' />";
				}
			}
		echo "<a href='".$_SERVER['PHP_SELF']."?".http_build_query($data)."' >"."".$sort_img." ".$label."</a>";
		}
	
	 
	// GET ARRAY 
	function query_limit()
	{
		 $data = mysqli_query($this->con,$this->query) or die(mysqli_error($this->con)); 
		 $rows = mysqli_num_rows($data);
		 $this->total_rows 	=  $rows ;
		if($rows>0)
		 {
		 $last = ceil($rows/$this->rows_per_page); 
		 $this->total_pages=$last;
		 if ($this->cur_page < 1) 
 		{ 
			 $this->cur_page = 1; 
		} 
		 elseif ($this->cur_page > $last) 
		 { 
			 $this->cur_page = $last; 
		 } 
		 $this->limit = ' limit ' .($this->cur_page - 1) * $this->rows_per_page .',' .$this->rows_per_page; 
		 }
		 else
		 {
			  $this->limit = '';
			  $this->total_pages=0;
		 }
	}
	
	function get_pagename() 
	{
 		$this->page_name = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	function get_array()
	{
	
		$result_array=array();
		
		if(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by']!="" )
		//if(($_REQUEST['sort_by']) && ($_REQUEST['sort_by']!=""))
		{
			$order_by = " order by ".$_REQUEST['sort_by']." ".$_REQUEST['sort_direction']. " ";
			//$filter_by = " and select_option ="."'".$_REQUEST['filter_by']."'";
		//	echo $order_by;
			//order by question_answer asc
			
		}
		else
		{
			$order_by = "";
		}
		//echo $this->query.$order_by.$this->limit;
		//exit();
		$result=mysqli_query($this->con,$this->query.$order_by.$this->limit) or die(mysqli_error($this->con));
		
		if(mysqli_num_rows($result)>0)
		{
			while($row=mysqli_fetch_array($result))
			{
					$result_array[]=$row;
					
			}
		}
		return $result_array;
	}
	
	function pagebreak($cur,$tot)
	{
		
		if($cur >= 7)
		{
			$this->mins = $cur;
			$this->maxs = (($cur+10) >= $tot)?$tot:$cur+10;
		}
		else
		{
			$this->mins = 1;
			$this->maxs = (10 > $tot)?$tot:10;
		}
		
	
		
	}
	

	function pagebreak_google_type($cur,$tot)
	{
		
		if($cur >= 7)
		{
			$this->mins = $cur-5;
			$this->maxs = (($cur+5) >= $tot)?$tot:$cur+5;
		}
		else
		{
			$this->mins = 1;
			$this->maxs = (10 > $tot)?$tot:10;
		}
		
	}

function show_links_google_type($hash_string="")
	{
		$cur_page_number=1;
		echo "<ul class='pagination'>";
		$link="";
		$querystring_array = $_REQUEST;
		

		if(isset($_REQUEST[$this->page_no_variable]))
		{
			$cur_page_number=$_REQUEST[$this->page_no_variable];
		}
		
		$querystring_array[$this->page_no_variable]=$cur_page_number;
		$this->pagebreak_google_type($cur_page_number,$this->total_pages);

		
		if($cur_page_number!=1)
		{
		$querystring_array[$this->page_no_variable] = 1;
		$query_string = http_build_query($querystring_array);	
		$link .= "<li class='".$this->link_class."' ><a href='".$this->page_name."?".$query_string.$hash_string."'>First</a></li>";
		
		
		$prev_link=	$cur_page_number-1;
		$querystring_array[$this->page_no_variable] = $prev_link;
		$query_string = http_build_query($querystring_array);	
		$link .= "<li class='".$this->link_class."'><a href='".$this->page_name."?".$query_string.$hash_string."'>&laquo; Prev</a></li>&nbsp;";
		echo $link;
		}



		for($i=$this->mins;$i<=$this->maxs;$i++)
		{
				
			// Adding Classes to the Start TAG
			if($i==$this->cur_page)
			{
			//exit();
					$temp_link_start= str_replace(">", " class='".$this->link_class_current."'>",$this->link_page_start);
			}
			else
			{
					$temp_link_start= str_replace(">", " class='".$this->link_class."'>",$this->link_page_start);
			}
			$querystring_array[$this->page_no_variable] = $i;
			$query_string = http_build_query($querystring_array);	
			
			$current_class ="";
			if($cur_page_number==$i)
			{
				$current_class = "class='".$this->link_class_current."'";
			}
			
			if($i==$this->cur_page)
			{
				$linkclass = $this->link_class_current;
			}
			else
			{
				$linkclass = $this->link_class;
			}
			
			$link = "<li class='".$linkclass."' ><a ".$current_class." href='".$this->page_name."?".$query_string.$hash_string."'>".$i."</a></li>";
			echo $temp_link_start.$link.$this->link_page_end;
			$current_class="";
		}
		
		$link="";

		if($cur_page_number!=$this->total_pages)
		{
		$prev_link=	$cur_page_number+1;
		$querystring_array[$this->page_no_variable] = $prev_link;
		$query_string = http_build_query($querystring_array);	
		
		
		$link .= "&nbsp;<li class='".$this->link_class."' ><a href='".$this->page_name."?".$query_string.$hash_string."'>Next&nbsp;&raquo;</a></li>";
		
		$querystring_array[$this->page_no_variable] = $this->total_pages;
		$query_string = http_build_query($querystring_array);	
		$link .= "<li class='".$this->link_class."' ><a href='".$this->page_name."?".$query_string.$hash_string."'>Last </a></li>";
		echo $link;
		}
	 	echo "</ul>";
	}

	function show_total_records()
	{
		return $this->total_rows;
	
	}
	
}// Class Ends here 
	


?>