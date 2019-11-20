<?php include("header.php");?>
<?php include("urllib.php");?>
<?php include("hashtaglib.php");?>
<?php include("databasesetup.php");//this gives us $conn to connect to mysql.?>
<?php include("watchlist.php");//set up watch list?>
<?php
	/* Show the rest of the page only if $_GET['id'] is set, otherwise the page was reached improperly. */
	if(isset($_GET['id'])){
		/** Get post id **/
		//to update "watch" list right away, since you're "viewing" it right now.
		$postid = "";
		if(isset($_GET['id'])){
			$postid = htmlentities($_GET['id']);
		}


		/** Select main post details. **/
		$query = "SELECT id,post,timestamp FROM posts WHERE parent=0 AND id='".mysqli_real_escape_string($conn,$postid)."';";
		$post = mysqli_query($conn,$query);
		$timestamp = 0;//for later
		$row = mysqli_fetch_array($post);
		if($row){
			//get main post
			$post = mysqli_real_escape_string($conn,$row['post']);
			//get main post timestamp
			$timestamp = $row['timestamp'];
		}else{
			$post = "Error";
		}


		/** Update Watch List, since you're here viewing it. **/
		if(isset($watching[$postid])){
			if($watching[$postid]<time()){
				$watching[$postid]=time();
			}
			setcookie("watching",json_encode($watching),time()+(60*60));//reexamine
		}
		
		

		/** Submit new comment **/ //(includes adding to the main post any hashtags in the comment).
		if(isset($_POST['comment'])){
			$comment = mysqli_real_escape_string($conn,htmlentities($_POST['comment']));

			//DEBUG: $comment = str_replace("\\","&#92;",$comment);

			$currtimestamp = time();
			$query="INSERT INTO posts (parent,post,timestamp) VALUES ({$postid},'{$comment}',{$currtimestamp});";
			mysqli_query($conn,$query);
			mysqli_commit($conn);

			//set cookie to remember this comment id and post time.
			$watching[$postid]=$currtimestamp;
			setcookie("watching",json_encode($watching),time()+(60*60));
			
			$comment = str_replace("\n","<br/> ",$comment);

			/* Keep count of hashtags */
			$tags  = get_hashtags($comment, $str=0);
			$usedtags = get_hashtags($post, $str=0);
			//remove tags that are already in the post, so there's no adding tags that the post already has.
			$tags = array_diff($tags,$usedtags);
			//remove doubles from tags list, so there's no adding multiple of the same tag, just cuz they used it more than once in their comment.
			$tags = array_unique($tags);

			////echo "<br/><b>Count of array_diff() array:</b> ".count($tags);
			////echo "<br/><br/>";
			////foreach($tags as $t){
			////	echo $t."<br/>";
			////}

			$num_tags = count($tags);
			////echo "<br/><br/>";
			////echo "The num_tags is: ".$num_tags;
			////echo "<br/><br/>";
			$tagstring = "";//specific for comments page to append tags in comments to the main post. (So everyone helps categorize.)
			//because of array_diff() this couldn't assum zero-indexed sequential order,
			// so "foreach" works and "for(i=0,i<num_tags,i++)" does NOT work here...
			foreach($tags as $tag){
				////echo "<br/><br/>";
				////echo "The tag is: ".$tag;
				////echo "<br/><br/>";
				//append each tag to tagstring - to later append tagstring to parent post.
				$tagstring .="#".$tag." ";
				////echo "<br/><br/>";
				////echo "The tagstring: ".$tagstring;
				////echo "<br/><br/>";
				$query = "SELECT * FROM hashtags WHERE hashtag='{$tag}';";
				$results = mysqli_query($conn,$query);
				//if count(results)>0:
				$row = mysqli_fetch_assoc($results);
				$uses = $row['uses'];
				if(count($uses)<1){
					$query = "INSERT INTO hashtags (hashtag,uses) VALUES ('".mysqli_real_escape_string($conn,$tag)."',1);";
					mysqli_query($conn,$query);
					mysqli_commit($conn);
				}else{
					$new_uses = $uses + 1;
					$query = "UPDATE hashtags SET uses={$new_uses} WHERE hashtag='{$tag}';";
					mysqli_query($conn,$query);
					mysqli_commit($conn);
				}
			}
			/* Append any hashtags from comment to main post */
			//This lets anyone tag any post with any hashtag.
			//Since only main posts are searcheable, this allows everyone to help make the main posts easier to find.
			$query = "UPDATE posts SET post=\"{$post} {$tagstring}\" WHERE id=\"{$postid}\";";
			mysqli_query($conn,$query);
			mysqli_commit($conn);
			//to update the post, even before page refresh...
			$post = $post." ".$tagstring;
		}

?>
						<!-- Nav Bar - Comments -->
						<ul id="navbar" class="nav nav-pills pull-left">
							<li><a href="index.php">Newsfeed</a></li>
							<li><a href="search.php">Search</a></li>
							<li><a href="about.php">About</a></li>
							<li class="active"><a href="?id=<?php echo $postid; ?>">Post <?php echo $postid; ?></a></li>
<?php

		/** Updates mail icon to alert user of how many posts in the users 'watch list' have had comments added. **/
		if(count($watching)>0){
			$updates = 0;
			//check for updates for each postid,timestamp pair in $watching
			foreach($watching as $postidkey=>$lastactivitytimestamp){
				//DEBUG: echo "{$postidkey}:{$lastactivitytimestamp};";
				$query = "SELECT timestamp FROM posts WHERE parent='{$postidkey}' ORDER BY timestamp DESC LIMIT 1;";
				$result = mysqli_query($conn,$query);
				$row = mysqli_fetch_array($result);
				//get timestamp for most recent comment
				$latesttimestamp = $row[0];
				//compare it with the last time we viewed or interacted with the page.
				if($lastactivitytimestamp<$latesttimestamp){//if theres newer comments since your last comment
					$updates+=1;
				}
			}
			//if there are updates, show the mail icon, and how many updates there are.
			if($updates>0){

	?>
							<li>
								<a href="updates.php"><span style="display: inline;"><img src="mailicon.png" /></span>&nbsp;&nbsp;<?php echo $updates; ?></a>
							</li>
	<?php

			}
		}

	?>
						</ul>
  
						<div style="clear:both;"></div>
					</div>
				</div>
  
				<!-- Status Update -->
				<div class="container-fluid">
					<div class="row-fluid post">

					   <div class="col-fluid">
						<div class="row-fluid">
							<!-- User Identity -->
							<div class="hidden-lg hidden-md hidden-sm col-xs-12" style="1px solid black; margin: 0px; padding: 0px; padding-left: 10px;">
								<img src="anons/anon<?php echo(rand(1,$settings['numimages']).".".$settings['imagetype']);?>" />
							</div>
						</div>
						<div class="row-fluid">
							<!-- User Identity -->
							<div class="col-sm-2 hidden-xs" style="1px solid black; margin: 0px auto; padding: 0px; padding: 0px;">
								<img src="anons/anon<?php echo(rand(1,$settings['numimages']).".".$settings['imagetype']);?>" />
								Anonymous
							</div>
						</div>
  
						<!-- Status Update Content -->
						<div class="col-sm-10 col-xs-12">
							<span class="colored">
	<?php
		//Format timestamps
		$minutesago = ceil((time()-((int)($timestamp)))/60);
		if(($minutesago/(60*24*7*4))>1){
			echo ceil($minutesago/(60*24*7*4))." months ago";
		}elseif(($minutesago/(60*24*7))>1){
			echo ceil($minutesago/(60*24*7))." weeks ago";
		}elseif(($minutesago/(60*24))>1){
			echo ceil($minutesago/(60*24))." days ago";
		}elseif(($minutesago/60)>1){
			echo ceil($minutesago/60)." hours ago";
		}elseif($minutesago>0){
			echo $minutesago." minutes ago";
		}else{
			echo "just now";
		}

	?>
							</span>
							<br/>
							<span class="content">
	<?php

		echo mark_tagged(hashtag_links(url_links(stripslashes(str_replace("\n"," <br/> ", str_replace("\\r\\n","\n ",$post))))));

	?>
							</span>
							<br/>
							<br/>
							<a href="index.php?id=<?php echo $postid;?>&vote=up" class="colored"><img style="border:0px" src="uparrow.png"></span></a>
							<a href="index.php?id=<?php echo $postid;?>&vote=down" class="colored"><img style="border:0px" src="downarrow.png"></span></a>
	<?php

		//Calculate vote score
		$query = "SELECT ups,downs FROM votes WHERE id={$postid};";
		$votes = mysqli_query($conn,$query);
		$votes = mysqli_fetch_array($votes);
		$score = ((int)($votes['ups']))-((int)($votes['downs']));

	?>
							<span class="colored">
	<?php

		//Format vote score
		if($score>-1){
			echo "+";
		}
		echo $score;

	?>
							</span>
							<form style="display: inline;" action="index.php" method="post">
								<input type="hidden" name="share" value="<?php echo $postid;?>" />
								<input class="colored buttonlink" type="submit" value="share" />
							</form>
						        <a href="raw.php?id=<?php echo $postid;?>" class="colored">raw</a>
						</div>
						</div>
						<div style="clear: both;"></div>
					</div>
				</div>
	  
	<?php

	//Select all relevant comments
	$query = "SELECT id,post,timestamp FROM posts WHERE parent='{$postid}' ORDER BY timestamp ASC;";
	$posts = mysqli_query($conn,$query);
	$latestid = 0;//store latest comment id for later use in cookies checking for updates on commented posts
	while($row = mysqli_fetch_array($posts)){
		$commentid = $row['id'];
		$post = $row['post'];
		$post = str_replace("\n","<br/> ",$post);
		$post = str_replace("\\","&#92;",$post);
		$timestamp = $row['timestamp'];
      
		if($latestid==0){
			$latestid = $commentid;
		}
      
		//Format timestamps
		$minutesago = ceil((time()-((int)($timestamp)))/60);
		if(($minutesago/(60*24*7*4))>1){
			$minutesago = ceil($minutesago/(60*24*7*4))." months ago";
		}elseif(($minutesago/(60*24*7))>1){
			$minutesago = ceil($minutesago/(60*24*7))." weeks ago";
		}elseif(($minutesago/(60*24))>1){
			$minutesago = ceil($minutesago/(60*24))." days ago";
		}elseif(($minutesago/60)>1){
			$minutesago = ceil($minutesago/60)." hours ago";
		}elseif($minutesago>0){
			$minutesago = $minutesago." minutes ago";
		}else{
			$minutesago = "just now";
		}

	?>
				<!-- Comment -->
				<div class="container-fluid">
					<div class="row-fluid post">
					   <div class="col-fluid">
						<div class="row-fluid">
							<!-- User Identity -->
							<div class="hidden-lg hidden-md hidden-sm col-xs-12" style="1px solid black; margin: 0px; padding: 0px; padding-left: 10px;">
								<img src="anons/anon<?php echo(rand(1,$settings['numimages']).".".$settings['imagetype']);?>" />
							</div>
						</div>
						<div class="row-fluid">
							<!-- User Identity -->
							<div class="col-sm-2 hidden-xs" style="1px solid black; margin: 0px auto; padding: 0px; padding: 0px;">
								<img src="anons/anon<?php echo(rand(1,$settings['numimages']).".".$settings['imagetype']);?>" />
								Anonymous
							</div>
						</div>
  
					<!-- Comment Content -->
					<div class="col-sm-10 col-xs-12">
						<span class="colored"><?php echo $minutesago;?></span>
						<br/>
						<span>
							<?php echo hashtag_links(url_links(stripslashes($post))); ?>
						</span>
						<br/>
						<br/>
						<form style="display: inline;" action="index.php" method="post">
							<input type="hidden" name="status" value="<?php echo str_replace("<br/>","\n",$post);?>" />
							<input class="colored buttonlink" type="submit" value="share" />
						</form>
						<a href="raw.php?id=<?php echo $commentid;?>" class="colored">raw</a>
					</div>
					<div style="clear: both;"></div>
				</div>
				</div>

			</div>
	<?php

		}// </while>
	}else{// </if $id>
		echo "Error: ?id= must be set.";
	}



	?>
				<!-- Comment Form -->
				<div class="container-fluid">
					<div class="row-fluid">
						<div id="replybox" class="formbox">
		
							<form id="commentform" method="post" action="?id=<?php echo $postid;?>">
								<textarea id="comment" name="comment" class="formtext" type="text" value="We are "></textarea>
								<br/>
								<input id="replybutton" class="formbutton pull-right" type="submit" value="Reply"></input>
							</form>
	  
							<div style="clear: both;"></div>
  
						</div>
					</div>
				</div>

		</div><!-- /container -->

	</body>

</html>
