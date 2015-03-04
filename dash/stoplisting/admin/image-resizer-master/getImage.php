<?php
if(isset($_GET['app'])) {
	$image_path = "../../upload_dropbox/".$_GET['url'];
} else if (isset($_GET['publish'])) {
	$image_path	= "../../upload_dropbox/".$_GET['url'];
	$height		= 1350;
	$width		= 900;
}
else{$image_path = $_GET['url'];}

if(isset($_GET['url']))
{
    //echo $image_path;
    if (!isset($_GET['publish'])) {
	    $width  = (isset($_GET['width'])) ? $_GET['width'] : 250;
	    $height =  (isset($_GET['height'])) ? $_GET['height'] : 100;
    }

    require_once 'lib/Image.php';
    $image = new Image($image_path);
    $image->resize($width, $height);
    $image->display();
}
