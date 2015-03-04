<?php 
// Answered on Stack Overflow By: Shafiqul Islam
// URL: http://stackoverflow.com/questions/13325754/how-to-rotate-image-in-php

if (isset($_GET['degree']) && isset($_GET['image'])) {
	$images 	= explode("-", $_GET['image']);
	$num_images 	= count($images);
	
	//How many degrees you wish to rotate
	 $degrees = (int) $_GET['degree'];
	 $base_url= "../../upload_dropbox/";
	 
	for ($i=0;$i < count($images);$i++) {
		// The file you are rotating
		 $image = $base_url.$images[$i];
		// Create the canvas
		   $src = $image;
		$system = explode(".", $images[$i]);
		//print_r($system);exit;
		if (preg_match("/jpg|jpeg/", $system[1]))
		{
		
		    //header('Content-type: image/jpeg');
		    $src_img=imagecreatefromjpeg($src);
		}
		if (preg_match("/png/", $system[1]))
		{
		
		   // header('Content-type: image/png');
		    $src_img = imagecreatefrompng($src);
		}
		if (preg_match("/gif/", $system[1]))
		{
		
		  //  header('Content-type: image/jpeg');
		    $src_img = imagecreatefromgif($src);
		}
		
		  // Rotates the image
		  $rotate = imagerotate($src_img, $degrees, 0);
		  // Outputs a jpg image, you could change this to gif or png if needed
		  if (preg_match("/png/", $system[1]))
		{
		    imagepng($rotate,$image); 
		} 
		else if (preg_match("/gif/", $system[1]))
		{
		    imagegif($rotate, $image);
		}
		else
		{
		    imagejpeg($rotate, $image); 
		}
		imagedestroy($rotate);  
		imagedestroy($src_img);
	}
}

   ?>

