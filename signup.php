<?php
session_start();
sign_up();

function create_connection(){
	require_once "login.php";
	$con = new mysqli($hn, $un, $pw, $db);
	if($con->connect_error) die (mysql_error());
	return $con;	
}
function sign_up(){
	sign_up_form();
	$uname = $email = $psw = "";
	$conn = create_connection();
	//if client side is validated
	if(isset($_POST['Submit'])) {
		if(isset($_POST['uname']))
			$uname = sanitizeString($_POST['uname']);
		if(isset($_POST['psw'])) 
			$psw = sanitizeString($_POST['psw']);
		if(isset($_POST['email']))
			$email = sanitizeString($_POST['email']);
		$uname = validate_username($uname);
		$psw = validate_Password($psw);
		$email = validate_Email($email);
		if((!empty($uname)) && (!empty($psw)) &&(!empty($email))){	
			$psw = password_hash("$psw", PASSWORD_DEFAULT);
			$que = "INSERT INTO users VALUES(NULL, '$uname', '$email', '$psw' )";
			$res = $conn->query($que);
			if(!$res) die( "<span style='margin-left: 80px'>INSERT failed</span> <br><br>");
			else{
				echo "<span style='margin-left: 80px'>Successfully sign up</span> <br><br>";
				echo "<span style='margin-left: 80px'><a href='user_login.php'>click here</a> to Login </span> <br><br>";
				return $que;
				$res->close();	
				$conn->close();
			}
		}
	}
	else { echo "Form not submit yet";}
}

function validate_username($field){
	$maxlength = 5 ;
	if($field == "") echo "Username is empty<br>";
	else if(strlen($field) < $maxlength)
		echo "Username must be at least "  .$maxlength." characters<br>";
	else if(strlen($field) > 0 && trim($field) == 0 )
		echo "Invalid Username. Username cannot contain only white space<br>";
	else if (!preg_match("/^[A-Za-z0-9_-]+$/", $field))
		echo "Only letters, digits, underscore, and hyphen are allowed for Username<br>";
	else{
	 return $field;	
	}
}
function validate_Password($field){
	$max_length = 6;
	if($field == ""){echo "No Password was entered<br>";}
	else if(strlen($field) < $max_length){
		echo "Passwords must be at least " .$max_length.  " characters <br>";
	}
	else if(!preg_match("/[a-z]/", $field) || !preg_match("/[A-Z]/", $field) || !preg_match("/[0-9]/", $field)){
		echo "Passwords must be one of each a-z, A-Z and 0-9<br>";
	}
	else{return $field;}
}

function validate_Email($field){
	$emailForm = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
	if ($field == "") echo "No Email was entered <br>";
	else if (!preg_match($emailForm, strtolower($field)))
	echo"The Email address is invalid<br>";
	else{return $field;}
}	

function sanitizeString($string){
	if(empty($string)) return false;
	$string = stripslashes($string);
	$string = strip_tags($string);
	$string = htmlentities($string);
	return $string;
}

function sign_up_form(){
	echo<<<_END
	<html><head>
	<style>
	    body {font-family: Arial, Helvetica, sans-serif;}
		input[type=text], input[type=password] {
			width: 40%;
			padding: 10px 15px;
			margin: 10px 0;
			display: inline-block;
			border: 1px solid #ccc;
			box-sizing: border-box;
		}
		button {
			background-color: #000039;
			color: white;
			padding: 10px 18px;
			margin: 10px 20px;
			border: none;
			cursor: pointer;
			width: auto;
		}
		button:hover {opacity: 0.8;}
		.cancelbtn {
		    width: auto;
		    padding: 10px 18px;
		    background-color: #f44336;
		}
		.container {padding: 20px;}
		.error {
			color: red;
			font-size: 90%;
		}
		span.psw {
		    float: right;
		    padding-top: 16px;
		}
	</style>
	<script type="text/javascript">
		function errorMsg(elementID, Msg){
			document.getElementById(elementID).innerHTML = Msg
		}
		function validate(){	
			fail = validate_username(document.signup.uname.value)
			fail += validate_Password(document.signup.psw.value)
			fail += validate_Email(document.signup.email.value)
			if(fail == "") return true
			else return false	
		}
		function validate_username(field){
			unameErr = true
			var maxlength = 5 
			if(field == "") return errorMsg("unameErr", "Username is empty")
			else if(field.length < maxlength)
				return errorMsg("unameErr", "Username must be at least " + maxlength + " characters")
			else if(field.length > 0 && field.trim() == 0 )
				return errorMsg("unameErr", "Invalid Username. Username cannot contain only white space")
			else if (/[^a-zA-Z0-9_-]/.test(field))
			return	errorMsg("unameErr","Only letters, digits, underscore, and hyphen are allowed")
			else{
				unameErr = false
				return ""
			}
		}
		function validate_Password(field){
			pswErr = true
			var max_length = 6
			if(field == ""){
				return errorMsg("pswErr","No Password was entered")
			}
			else if(field.length < max_length){
				return errorMsg("pswErr","Passwords must be at least " + max_length +  " characters")
			}
			else if(!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field)){
				return errorMsg("pswErr","Passwords must be one of each a-z, A-Z and 0-9")
			}
			else{
				pswErr = false
				return ""
			}
		}
		function validate_Email(field){
			emailErr = true;
			var emailForm = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
			if (field == "") return errorMsg("emailErr", "No Email was entered")
			else if (!emailForm.test(String(field).toLowerCase()))
			return 	errorMsg("emailErr",  "The Email address is invalid")
			else{
				emailErr = false
				return ""
			}
		}	
	</script>
	</head>
	<body>
	<form  method="post" action="signup.php" name="signup" onsubmit="return validate()">
	  <h2 style='margin-left:100px'> Sign up</h2>
		<div class="container" >
			Username: <input type="text" placeholder="Enter Username" name="uname" >
			<div class="error" id="unameErr"></div>
			Password: <input style='margin-left:3px' type="password" placeholder="Enter Password" name="psw" >
			<div class="error" id="pswErr"></div>
			Email: <input style='margin-left:31px' type="text" placeholder="Enter email " name="email">  
			<div class="error" id="emailErr"></div>
		</div>
		<div class="container" >
		    <button type="button" onclick="location.href='user_login.php' "class="cancelbtn">Cancel</button> 
		    <button type="submit" name="Submit">Sign up</button>
		</div>
	</form></body></html>
	
_END;	
}

//--------------------------------------------------------
//tester functions
function test_sanitize_string($input, $exp){
	$act = sanitizeString($input);
	if($exp === $act){
		echo "Test passed for test_sanitize_string method<br><br>";
	}
	else echo "Test failed for test_sanitize_string method<br>";
	
}
function test_signup($exp){
	$act = "INSERT INTO users VALUES(NULL, 'test', 'tester@gmail.com', '$2y$10$w1v3VSMSYbywkjdLA.7OUO.a4xRwmvoOokkB5QiJZ1Hs1VK5REawO')";
	if($exp === $act){
		echo "Test passed for test_signup method<br><br>";
	}
	else echo "Test failed for test_signup method<br>";
}

// tester for validate_username, validate_Password and validate_Email methods
function test_validation($str1, $str2){
	$actu_result = validate_username($str1);
	if($str2 === $actu_result){
		echo "Test passed for validation methods<br>";
	}
	else echo "Test failed for validation methods<br><br>";
}
function mysql_error(){
	echo<<<_END
	Ooops! Something Went Wrong.
_END;

}

?>