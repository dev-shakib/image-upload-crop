<?php

  $image = $_GET['img'];

  /** Get full name of image */
  $imageFullName = substr($image, strrpos($image, '/') + 1);

  /** Get extension */
  $ext = substr($imageFullName, strrpos($imageFullName, '.') + 1);
  
  /** Create image by types */
  if($ext == 'jpg' || $ext == 'jpeg') {
    $img_r = imagecreatefromjpeg($image);
    header('Content-type: image/jpeg');
  }elseif($ext == 'png') {
    $img_r = imagecreatefrompng($image);
    header('Content-type: image/png');
  }elseif($ext == 'gif') {
    $img_r = imagecreatefromgif($image);
    header('Content-type: image/gif');
  }

  /** Create a new true color image */
  $dst_r = ImageCreateTrueColor( $_GET['w'], $_GET['h'] );

  /** Copy and resize part of an image with resampling */
  imagecopyresampled($dst_r, $img_r, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);
  
  /** Output image based on type */
  if($ext == 'jpg' || $ext == 'jpeg') {
    imagejpeg($dst_r, 'cropped/'.$imageFullName);
  }elseif($ext == 'png') {
    imagepng($dst_r, 'cropped/'.$imageFullName);
  }elseif($ext == 'gif') {
    imagegif($dst_r, 'cropped/'.$imageFullName);
  }

  echo '/cropped/'.$imageFullName;
  
?>