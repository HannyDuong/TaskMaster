<?php
//cookie will be removed after 7 days
setcookie('name', 'FinalCS174', time() + 60 * 60 * 24 * 7, '/'); 
session_start();
    if(isset($_SESSION['logged-in'])){
        header('Location: task.php');
        exit();
    }
    
 
login();

function create_connection(){	
    require 'login.php';
    $con = new mysqli($hn, $un, $pw, $db);
    if($con->connect_error) die (mysql_error());
    return $con;	
}
//showing message when connection error
function mysql_error(){
	echo<<<_END
	Ooops! Something Went Wrong.
_END;

}

function login(){	
    login_form();   
	$conn = create_connection();
	$username ="";
	//if user entered username and password
	if (isset($_POST['uname']) && isset($_POST['password'])){
	    $un_temp = get_post($conn, $_POST['uname']);
	    $pw_temp = get_post($conn, $_POST['password']);
	    $query = " SELECT * FROM users WHERE username = '$un_temp' ";
	    $result = $conn->query($query);
		if (!$result) die(mysql_error());
		//if there is an object return in $result
		elseif ($result->num_rows){
			$row = $result->fetch_array(MYSQLI_NUM); //extract the data from numeric array to $row array
			$result->close();
			$username = $row[1]; //each column appears in the array same order as table in db
			$token = $row[3];
			//verify password
            if (password_verify( $pw_temp, $token)) {
                $_SESSION['logged-in'] = true;
                $_SESSION["user"] = $username;
                 header("Location:task.php");
			}
			else {
                echo "<span >Invalid username/password combination </span>";
            }
		}
		else echo "<span >Invalid username/password combination</span>";
	}
	$conn->close();
}	



//sanitizing
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

function login_form(){
echo<<<_END
<!DOCTYPE html>
<html>
<head>
<style>
    .container{
    margin-right: auto;
    margin-left: auto ;
    max-width: 1200px; 
    margin-top:50vh;
    
    }

    .login h1 span{
    display:block;
    font-size:28px;
    }

    .form{
    width:250px;
    text-align: left;
    // margin:auto;
    margin-right: auto;
    margin-left: auto ;
    margin-top:30vh;
}
.label{
    display:block;
    color:#293a4a;
    font-size:16px;
    margin-bottom:3px;
}

input[type="text"],
input[type="password"]{
    width:100%;
    display: block;
    height: 32px;
    border-radius: 4px;
    border: 2px solid #bebebe;
    background-color: #fff;
    outline:none; 
    margin-bottom:15px;
}

.btn{
    margin-top: 15px;
    background-color: #179bd7;
    width: 100%;
    font-size: 14px;
    font-family: "Open Sans",sans-serif;
    font-weight: 600;
    padding-top: 7px;
    padding-bottom: 7px;
    text-align: center;
    text-decoration: none;
    outline: none;
    border: 1px solid #095779;
    -khtml-border-radius: 4px;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
}
.error {
    color: red;
    font-size: 90%;
}
span{
    display: table;
    margin: 0 auto;
    color: red;
}
</style>
<script type="text/javascript">
		function errorMsg(elementID, Msg){
			document.getElementById(elementID).innerHTML = Msg
		}
		function validate(){	
			fail = validate_username(document.login.uname.value)
			fail += validate_Password(document.login.password.value)
			if(fail == "") return true
			else return false	
		}
		function validate_username(field){
			unameErr = true
			if(field == "") return errorMsg("unameErr", "User Name is empty")
			else{
				unameErr = false
				return ""
			}
		}
		function validate_Password(field){
			pswErr = true
			if(field == ""){
				return errorMsg("pswErr","No Password was entered")
			}
			else{
				pswErr = false
				return ""
			}
		}
        </script>
</head>
<body>

<div >
    <div class="form">
        <h1>Log in</h1>
        <form method="post" action="user_login.php" name ="login" onsubmit="return validate()">
            <div class="input label">
                <label for="login">User Name</label>
                <input type="text" name="uname" >
                <div class="error" id="unameErr"></div>
            </div>
            <div class="input label">
                <label for="password">Password</label>
                <input type="password" name="password" >
                <div class="error" id="pswErr"></div>
            </div>
            <button class="btn" type="submit">Log in</button>
        </form>
        <p style='margin-top:10px' >Don't have account yet <a href='signup.php'>Click Here</a> to sign up</p>
    </div>
    
</div>
</body>
</html>
_END;

}


//tester functions
function test_string($input, $exp){
	$act = sanitizeString($input);
	if($exp === $act){
		echo "Test passed <br><br>";
	}
	else echo "Test failed <br>";
	
}
function tester($str1, $str2){
	if($str1 === $str2){
		echo "Test passed";
	}
	else echo "Test failed";
}

?> 
