<?php
session_start();
if(!(isset($_SESSION['logged-in']))){
    header('Location: user_login.php');
    exit();
}

require_once 'login.php';
$con = new mysqli($hn, $un, $pw, $db);
if($con->connect_error) die (mysql_error());
if(isset($_POST['id'])){
$id =  get_post($con,$_POST['id']);
$query = "DELETE FROM tasks WHERE id = '$id' ";
$result = $con->query($query);
if(!$result) die (mysql_error());
}
$con->close();
header('Location: task.php');

function get_post($conn,$var){
	if(empty($var)) return false;
	$var = $conn->real_escape_string($var);
	$var = sanitizeString($var);
	return $var;
}

 function sanitizeString($string){
	if(empty($string)) return false;
	$string = stripslashes($string);
	$string = strip_tags($string);
	$string = htmlentities($string);
	return $string;
}

function mysql_error(){
	echo<<<_END
	Ooops! Something Went Wrong.
_END;

}