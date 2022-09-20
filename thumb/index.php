<?php
/*
@author: Shahrukh Khan
@website: http://www.thesoftwareguy.in
@facebook fanpage: https://www.facebook.com/Thesoftwareguy7
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="http://www.thesoftwareguy.in/favicon.ico" type="image/x-icon" />
<!--iOS/android/handheld specific -->
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="description" content="Generating thumbnails on the fly with php">
<meta name="keywords" content="thumbnails, image resize, php timthumb">
<meta name="author" content="Shahrukh Khan">
<title>Generating thumbnails on the fly with php - thesoftwareguy</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<div id="container">
  <div id="body">
    <div class="mainTitle" >Generating thumbnails on the fly with php</div>
    <div class="height20"></div>
    <article>
    <div style="text-align:center"><span style="text-decoration:underline;">Original Image 512 X 512</span><br /><img src="images/img1.jpg" alt="no image found" /></div>
    <div class="height20"></div>
	 <table class="bordered" >
      <tr>
	     <th width="30%">Output</th>
         <th >Code</th>
         <th >Comments</th>
     </tr>
     
      <tr>
      	<td style="text-align:center;"><img src="timthumb.php?src=images/img1.jpg&w=100&h=100" alt="no image found" /></td>
        <td style="text-align:center;"> timthumb.php?src=images/img1.jpg&w=100&h=100 </td>
        <td style="text-align:center;">Set image width and height to 100px </td>
      </tr>
      <tr>
      	<td style="text-align:center;"><img src="timthumb.php?src=images/img1.jpg&w=100&h=100&q=50" alt="no image found" /></td>
        <td style="text-align:center;">timthumb.php?src=images/img1.jpg&w=100&h=100&q=50 </td>
        <td style="text-align:center;">Low Image quality </td>
      </tr> 
      <tr>
      	<td style="text-align:center;"><img src="timthumb.php?src=images/img1.jpg&w=180&h=100&a=t" alt="no image found" /></td>
        <td style="text-align:center;">timthumb.php?src=images/img1.jpg&w=180&h=100&a=t </td>
        <td style="text-align:center;">Set Image Alignment position when cropping, c, t, l, r, b, tl, tr, bl, br </td>
      </tr>
      <tr>
      	<td style="text-align:center;"><img src="timthumb.php?src=images/img1.jpg&w=150&h=100&zc=0" alt="no image found" /></td>
        <td style="text-align:center;">timthumb.php?src=images/img1.jpg&w=150&h=100&zc=0 </td>
        <td style="text-align:center;">Resize to Fit specified dimensions (no cropping) </td>
      </tr>
      <tr>
      	<td style="text-align:center;"><img src="timthumb.php?src=images/img1.jpg&w=150&h=100&zc=2" alt="no image found" /></td>
        <td style="text-align:center;">timthumb.php?src=images/img1.jpg&w=150&h=100&zc=2 </td>
        <td style="text-align:center;">Resize proportionally to fit entire image into specified dimensions, and add borders if required </td>
      </tr>
      
      <tr>
      	<td style="text-align:center;"><img src="timthumb.php?src=images/img1.jpg&w=100&f=5,150,0,0,25" alt="no image found" /></td>
        <td style="text-align:center;"> timthumb.php?src=images/img1.jpg&w=100&h=100&f=5,150,0,0,25 </td>
        <td style="text-align:center;">Colorize, or tint, your images </td>
      </tr>
      
      <tr>
      	<td style="text-align:center;"><img src="timthumb.php?src=images/logo.png&w=150&h=100&zc=2&ct=0" alt="no image found" /></td>
        <td style="text-align:center;">timthumb.php?src=images/img1.jpg&w=150&h=100&zc=2&ct=0 </td>
        <td style="text-align:center;">Use transparency and ignore background colour, set ct = 1 to use transparency </td>
      </tr>
      
     </table>
    </article>
    <div class="height10"></div>
    <footer>
      <div class="copyright"> &copy; 2013 <a href="http://www.thesoftwareguy.in" target="_blank">thesoftwareguy</a>. All rights reserved </div>
      <div class="footerlogo"><a href="http://www.thesoftwareguy.in" target="_blank"><img src="http://www.thesoftwareguy.in/thesoftwareguy-logo-small.png" width="200" height="47" alt="thesoftwareguy logo" /></a> </div>
    </footer>
  </div>
</div>
</body>
</html>
