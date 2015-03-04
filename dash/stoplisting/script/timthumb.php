<?php
# ----------------------------------------------------------------------
# DFN Thumbnailer
# http://www.digifuzz.net
# digifuzz@gmail.com
# ----------------------------------------------------------------------

# Constants
$IMAGE_BASE = "http://agapeworks.x10.mx/dash/stoplisting/";

$image_file = $_GET['img'];
$MAX_WIDTH  = $_GET['mw'];
$MAX_HEIGHT = $_GET['mh'];

global $img;

# No Image?  No go.
if( !$image_file || $image_file == "" )
{
      die( "NO FILE FOUND.");
}      

# if no max width is set, set one.
if( !$MAX_WIDTH || $MAX_WIDTH == "" )
{
  $MAX_WIDTH="150";
}      

# if not max height is set, set one.
if( !$MAX_HEIGHT || $MAX_HEIGHT == "" )
{
  $MAX_HEIGHT="150";
}      

# Get image location
$image_path = $IMAGE_BASE . $image_file;

# Load image
$img = null;
//$ext = strtolower(end(explode('.', $image_path)));
$ext = pathinfo($image_path, PATHINFO_EXTENSION);
if ($ext == 'jpg' || $ext == 'jpeg') 
{
    $img = @imagecreatefromjpeg($image_path);
} 
else if ($ext == 'png') 
{
  $img = @imagecreatefrompng($image_path);
} 
else if ($ext == 'gif') 
{
  # Only if your version of GD includes GIF support
  $img = @imagecreatefromgif($image_path);
}

# If an image was successfully loaded, test the image for size
if ($img) 
{
  # Get image size and scale ratio
  $width = imagesx($img);
  $height = imagesy($img);
  $scale = min($MAX_WIDTH/$width, $MAX_HEIGHT/$height);

  # If the image is larger than the max shrink it
  if ($scale < 1)   
  {
     $new_width = floor($scale*$width);
     $new_height = floor($scale*$height);
     # Create a new temporary image
     $tmp_img = imagecreatetruecolor($new_width, $new_height);
     # Copy and resize old image into new image
     imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
     imagedestroy($img);
     $img = $tmp_img;
  }
}  

# Create error image if necessary  
if (!$img)   
{   
    $img = imagecreate($MAX_WIDTH, $MAX_HEIGHT);
    imagecolorallocate($img,255,255,255);
    $c = imagecolorallocate($img,255,0,0);
    imageline($img,0,0,$MAX_WIDTH,$MAX_HEIGHT,$c);
    imageline($img,$MAX_WIDTH,0,0,$MAX_HEIGHT,$c);
}  

# Display the image 
header("Content-type: image/jpeg");  
imagejpeg($img,'',500);
?>