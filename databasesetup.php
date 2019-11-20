<?php

        /** Settings **/
        $settings = array();
        $settings['serveraddress'] = 'localhost';//may change
        $settings['SQLuser'] = '';//fill in
        $settings['SQLpassword'] = '';//fill in
        $settings['databasename'] = 'posts';//check that this matches

        /* The number of profile images to randomly select from. */
        $settings['numimages'] = 47;//Named anon1.jpg through anon*.jpg
        $settings['imagetype'] = "jpg";//or anon1.png through anon*.png for example.

	/** Set up Database **/
	//connect or die with error message.
	$conn = mysqli_connect($settings['serveraddress'],$settings['SQLuser'],$settings['SQLpassword'],$settings['databasename']);
        mysqli_set_charset("utf8");
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
?>
