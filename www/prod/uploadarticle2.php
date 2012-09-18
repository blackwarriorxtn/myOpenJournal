<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>My Open Journal | Upload your Article</title>
    
    <meta name="description" content="My Open Journal" />
    <meta name="keywords" content="my open journal" />
    
    <meta name="author" content="CMPSC 483 W" />
    
    <!-- CSS Stylesheets -->
    <!--<link rel="stylesheet" type="text/css" href="../../css/universal.css" />-->
    <link rel="stylesheet" type="text/css" href="../includes/upload.css" />
	    <link rel="stylesheet" type="text/css" href="css/universal.css" />
	<link rel="stylesheet" type="text/css" href="css/page.css" />
    
    <!-- Menu style and javascript -->
    <!--<link type="text/css" href="../../css/menu.css" rel="stylesheet" /> 
    
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/menu.js"></script>-->
	
	
	
	
	
	<?php include("includes/cookiecheck.inc"); 
		require('includes/dbconnect.inc');
	CookieIsNotSet('login.php?loginrequired=true');
	session_start();
	$dbcnx= new MySQL;
	$dbcnx->MySQL();
	?>

	<div id="container">
	
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php include("navigation.php"); ?>
	</div>
	<div id="body_content">
	<div id="content">	
	
	
	<?php
	function validateMainExtension($ext)
	{
		if($ext == "doc" || $ext == "pdf" || $ext == "docx" || $ext == "txt" || $ext == "rft")
			return true;
		return false;	
	}

        function validateAttachmentExtension($ext)
	{
		if($ext == "doc" || $ext == "pdf" || $ext == "docx" || $ext == "txt" || $ext == "rft" || $ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "bmp")
			return true;
		return false;	
	}


	function processUpload()
	{
		$article_title = mysql_real_escape_string($_POST["document_title"]);
		$article_description = mysql_real_escape_string($_POST["description"]);
		$article_categories = mysql_real_escape_string($_POST["add_categories"]);
		$has_errors = false;
		if(empty($article_title))
		{
			//echo("You must have an article title!<br />");
			$build_errors .= "<font color=red>  *    You must have an article title!<br></font>";
			$has_errors = true;
		}
		if(empty($article_description))
		{
			$build_errors .= " <font color=red>  *    You must have an article description!<br></font>";
			$has_errors = true;
		}
		if(empty($article_categories))
		{
			$build_errors .= " <font color=red>  *    You must have at least a main category!<br></font>";
			$has_errors = true;
		}
		$document_type = $_FILES['uploadedfile']['type'];
		if(empty($document_type))
		{
			$build_errors .= " <font color=red>  *    Invalid main document type! Valid types include doc, pdf, docx, txt, rtf!<br></font>";
			$has_errors = true;
		}
		if(!empty($build_errors))
		{
			echo $build_errors;
			$has_errors = true;
			return $has_errors;
		}
		$attachments = $_FILES["additionalfiles"];
		$authors = $_POST["authors"];
		//echo "Author: "; 
		//print_r($authors);
		//echo "<br />";
		$the_query = "SELECT * FROM document D";
		$parse_query = mysql_query($the_query);
		//$doc_array = mysql_fetch_array($parse_query);
		//print_r($doc_array);
		$main_doc_num = mysql_num_rows($parse_query) + 1;
		//$target_path .= $_FILES['uploadedfile']['name']; 
		$query =   mysql_query("insert into document (published, title, abstract)
		         		values(CURDATE(), '$article_title', '$article_description')") or die(mysql_error());
		
		$doc_num = mysql_insert_id();
		$main_full_name = $_FILES['uploadedfile']['name'];
		$main_file_ext = pathinfo($main_full_name, PATHINFO_EXTENSION);
		$document_name = basename($main_full_name);
		$dir_path = "/var/www/html/uploads/" . $doc_num;
		$mk_dir = $dir_path;	
		mkdir($dir_path, 0777, true);
		$target_path = $dir_path;
		$version = 1;
		$main_file_name = $doc_num . "_" . $version . "." . $main_file_ext;
		$target_path = $dir_path . "/" . $main_file_name;
		//echo "NO MOVE YEY <br /><br />";
		if(empty($main_file_ext) || !validateMainExtension($main_file_ext))
		{
			$build_errors .= " <font color=red>  *    Invalid main document type!<br></font>";
			echo $build_errors;
			$has_errors = true;
			return $has_errors;
		}
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
		{
			//echo "WE MOVED IT <br /><br />";
			$insert_true = 1;
			$query_main_file = mysql_query("INSERT INTO file (ismain, document_number, filename, date_uploaded) VALUES ('$insert_true', '$doc_num', '$main_file_name', CURDATE())") or die(mysql_error());
			if(isset($_COOKIE['username']))
			{
				$uname = $_COOKIE['username'];
			}
			else
			{
				$uname = $_SESSION['username'];
			}
			$query_user_id = mysql_query("SELECT user_id FROM users WHERE username = '$uname'") or die(mysql_error());
			$arr_user_id = mysql_fetch_assoc($query_user_id);
			$u_id = $arr_user_id['user_id'];
			$add_author = mysql_query("INSERT INTO authors (user_id, document_number) VALUES ('$u_id', '$doc_num')") or die(mysql_error());
			
			$attach_num = 0;
			foreach(array_keys($attachments['name']) as $i)
			{
				if(empty($attachments['name'][$i]))
				{
					continue;
				}
				$attach_file_name = $attachments['name'][$i];
				$attach_file_type =  pathinfo($attach_file_name, PATHINFO_EXTENSION);
				//echo "type: " . $main_file_ext;
				if(empty($attach_file_type) || !validateAttachmentExtension($attach_file_type))
				{
					$build_errors .= "<font color=red>  *    Invalid attachment extension! Please try again!<br /></font>";
					$has_errors = true;
					echo $build_errors;
					return $has_errors;
				}
				$attach_num++;
				$target_path = "/var/www/html/uploads/";
				$target_path .= $document_name; 
				$target_path = $dir_path;
				$attach_file_name = $doc_num . "_" . $version . "_" . $attach_num . "." . $attach_file_type;
				$target_path = $dir_path . "/" . $attach_file_name;
				if(move_uploaded_file($attachments['tmp_name'][$i], $target_path)) 
				{
					$insert_false = 0;
					$query = mysql_query("INSERT INTO file (ismain, document_number, filename, date_uploaded) VALUES ('$insert_false', '$doc_num', '$attach_file_name', CURDATE())") or die(mysql_error());
				}
				else
				{
					$build_errors .= "<font color=red>  *    There was an error uploading the attachment file, please try again!<br /></font>";
					$has_errors = true;				
					echo $build_errors;
					return $has_errors;
				}
			}

			$author_insert = false;
			foreach(array_keys($authors) as $i)
			{
				$cur_author = mysql_real_escape_string($authors[$i]);
				//echo "Author: " . $authors[$i] . "<br />";
				if(empty($cur_author))
				{
					continue;
				}
				$query_author_id = mysql_query("SELECT user_id FROM users WHERE username = '$cur_author'") or die(mysql_error());
				$arr_author_id = mysql_fetch_assoc($query_author_id);
				//print_r($arr_author_id);
				$author_id = $arr_author_id['user_id'];
				//echo "<br />" . $author_id;
				if(mysql_num_rows($query_author_id) == 0)
				{
					if(!$author_insert)
					{
						echo "<br /><br /><form method='post' action='uploadarticle3.php'>";
						$author_insert = true;
					}
					if($author_insert)
					{
						echo "We do not recoginize the username " . $cur_author . ". Please enter the following information so we contact the author you specified:<br />";
						echo "Email Address: <input name='author_email[]' type='input' /><br />";
						echo "First Name: <input name='author_first_name[]' type='input' /><br />";
						echo "Last Name: <input name='author_last_name[]' type='input' /><br /><br />";
						echo "<input name='doc_num[]' type='hidden' value='".$doc_num."' />";
						echo "<input name='author_username[]' type='hidden' value='".$cur_author."' />";
					}
				}
				else
				{
					//echo "In so author is here and id = " .$author_id;
					$add_author = mysql_query("INSERT INTO authors (user_id, document_number) VALUES ('$author_id', '$doc_num')") or die(mysql_error());
				}
			}
			if($author_insert)
			{
				echo "<input type='submit' value = ' Save Author Data ' /></form>";
			}
			$get_cats = explode(" ", $article_categories);
			foreach($get_cats as $insert_cat)
			{
				$query_cats = mysql_query("INSERT INTO categories (category_id, document_number) VALUES ('$insert_cat', '$doc_num')") or die(mysql_error());

			}
		}
		else
		{
				$build_errors .= "<font color=red>  *    There was an error uploading the file, please try again!<br /></font>";
				$has_errors = true;
				echo $build_errors;
				return $has_errors;
		}
		if(!$author_insert)
		{
			$goto_doc = "viewarticle.php?id=" . $doc_num;
			header("Location: " . $goto_doc);
		}
		//if($attach_file_type
	}
	$has_errors = false;
	$has_errors = processUpload();
	if($has_errors)
	{
		echo "<br />";
		include("uploadform.php");
	}
	$dbcnx->close();
	//$dbcnx->close();
	?>	
</div></div><?php include("footer.shtml"); ?></div></div>

</html>

	
