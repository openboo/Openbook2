<!DOCTYPE html>
<head>
    <style>
        body{
		font-family: Tahoma, Verdana, Arial, sans-serif;
		font-size: 18px;
        }
    </style>
</head>
<body>
<?php include("databasesetup.php");//this gives us $conn to connect to mysql.?>
<?php
	/* Show the rest of the page only if $_GET['id'] is set, otherwise the page was reached improperly. */
	if(isset($_GET['id'])){
		/** Get post id **/
		//to update "watch" list right away, since you're "viewing" it right now.
		$postid = "";
		if(isset($_GET['id'])){
			$postid = htmlentities($_GET['id']);
		}


		/** Select post or comment details. **/
		$query = "SELECT id,post,timestamp FROM posts WHERE id='".mysqli_real_escape_string($conn,$postid)."';";
		            //removed parent=0 so that comments could be easily copied as well.
		$post = mysqli_query($conn,$query);
		$timestamp = 0;//for later
		$row = mysqli_fetch_array($post);
		if($row){
			//get main post
			$post = mysqli_real_escape_string($conn,$row['post']);
			//get main post timestamp
			$timestamp = $row['timestamp'];
		}else{
			$post = "Error: Not found.";
		}
		
?>
  
	<?php

		$post = stripslashes(str_replace("\\r\\n","<br/> ",$post));
		$post = preg_replace('~<br/>\s*<br/>~','<br/>-<br/>',$post);
		$post = preg_replace('~<br/>\s*<br/>~','<br/>-<br/>',$post);
		echo $post;

	}else{// </if $id>
		echo "Error: ?id= must be set.";
	}



	?>

</body>
</html>
