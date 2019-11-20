<?php
		/** Big Brother Alert **/
		$off = "";
		if(isset($_GET['off'])){
			$off = $_GET['off'];
			if($off=="bigbrotheralert"){
				setcookie("bigbrotheralert","off");
			}
		}elseif(!isset($_COOKIE['bigbrotheralert'])){
?>
		<div id="alert">
			<a href="index.php?off=bigbrotheralert" style="float: right;"><img id="closebutton" style="border: 0px;" src="closebutton.png"></a>
			Browser activity and IP addresses are <br class="rwd-break" />being logged by government surveillance.
			<br/><br class="rwd-break" />
			Anonymity is won only by <a href="https://www.eff.org/pages/tor-and-https">understanding</a> <br class="rwd-break" />and using multiple <a href="https://en.wikipedia.org/wiki/Tails_(operating_system)">tools</a> like <a href="http://lifehacker.com/what-is-tor-and-should-i-use-it-1527891029">Tor-Browser</a>.
		</div>
<?php
		}
?>
