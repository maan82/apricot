
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>






</head>



<body >
<?php require_once('functions.php'); ?>

<?php 



echo normaliseText("adjas\njdklas\r\njdkla");
?>

<textarea ><?php echo normaliseText("adjas\njdklas\r\njdkla");?></textarea>

</body>

</html>