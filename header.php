<?php
//ONLY on SERVER: NOT on LOCALHOST.
//setup php for working with Unicode data
/*mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');*/
?><!DOCTYPE html>
<html lang="en">

	<head>
		<!--You've uncovered another stop on the underground railroad!-->
		<!--Perhaps you're looking for: p0rtalurl/IMPORTANT.txt -->

		<!-- Metadata -->
		<meta charset="utf-8">
		<title>openbook</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="An Anonymous hive mind social media network website. One p0rtal among #thep0rtals.">
		<meta name="author" content="Anonymous">

		<!-- Styles for Bootstrap -->
		<link href="css/bootstrap.css" rel="stylesheet">

		<!-- Extra Stylin' -->
		<link href="css/style.css" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="../assets/js/html5shiv.js"></script>
		<![endif]-->

		<!-- Simple Ajax Library (SAL) -->
		<script language="javascript" src="js/ajax.js"></script>
		<script language="javascript" src="js/showcaptcharefreshbutton.js"></script>

	</head>
	<body>
<?php include("bigbrotheralert.php");//the only thing about including it here is that this alert is always above any other alerts.?>

		<!-- Page Container -->
		<div class="container-narrow">

			<!-- Header -->
			<div class="container-fluid">
				<div class="row-fluid">

					<!-- 0ther p0rtals -->
					<div id="link2p0rtals">
						0thers:
<?php
						/** Appends sub-page and GET vars to every p0rtal link.
						 *  This helps give even more continuity among #thep0rtals.
						 * And enables searching for the same thing on many p0rtals easily.
						 **/
						$url = "$_SERVER[REQUEST_URI]";
						$pageurl = pathinfo($url,PATHINFO_BASENAME);

						echo "<span style='text-decoration: underline;'><a href='IMPORTANT.txt'>more p0rtals <i>coming soon</i></a>?</span>";
?>
						<!--a href="p0rtalurl/<?php echo $pageurl;?>">p0rtal</a>
						<a href="p0rtalurl/<?php echo $pageurl;?>">p0rtal</a>
						<a href="p0rtalurl/<?php echo $pageurl;?>">p0rtal</a>
						<a href="p0rtalurl/<?php echo $pageurl;?>">p0rtal</a>
						<a href="p0rtalurl/<?php echo $pageurl;?>">p0rtal</a-->
					</div>
	  
					<!-- Title Bar -->
					<div id="header">
						<a href="index.php">
							<img style="width: 80%" src="openbook.png" />
						</a>

						<p id="slogan">
							<br/>
							<span>"Man is least himself when <br class="rwd-break" />he talks in his own person.
							<br/><br class="rwd-break" />
							<span>Give him a mask, and <br class="rwd-break" />he will tell you the truth."</span>
							<br/><br/>
							Search "<a style='color:white; text-decoration: underline;' href="search.php?search=%23openbook+%23intro">#openbook #intro</a>" to begin.
						</p>
						<div style="clear:both;"></div>
					</div>
