<?php
session_start();
if(!(isset($_SESSION['logged-in']))){
    header('Location: user_login.php');
    exit();
}



logout();
create_tasks();
getTasks();

function create_connection(){	
    require 'login.php';
    $con = new mysqli($hn, $un, $pw, $db);
    if($con->connect_error) die (mysql_error());
    return $con;	
}

function logout(){
     $username = $_SESSION["user"];
    echo<<<_END
    <div>
    <h1>Task Master</h1>
    <div>
        <h4>Welcome <strong> $username </strong></h4>
        <h5><a href="logout.php">Logout</a></h5> 
    </div>
_END;
}

function create_tasks(){
    $conn = create_connection();
    $username = $_SESSION["user"];
    echo<<<_END
    <html>
    <head>
    
    <style>
    .container{
        max-width:1200px;
        margin-right: auto;
        margin-left: auto ;
        margin-top:5vh;
       
        
    }
    span{
        display: table;
        margin: 0 auto;
    }
    input[type="text"]
    {
    width:250px;
    display: block;
    height: 32px;
    border-radius: 4px;
    border: 2px solid #bebebe;
    background-color: #fff;
    outline:none; 
    margin-bottom:15px;
    }
    textarea
    {
    width:250px;
    display: block;
    height: 150px;
    border-radius: 4px;
    border: 2px solid #bebebe;
    background-color: #fff;
    outline:none; 
    margin-bottom:15px;
}
.btn{
    margin-top: 15px;
    background-color: #179bd7;
    width: 250px;
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
.card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    max-width: 200px;
    max-height: 280px;
    margin: auto;
    text-align: center;
    font-family: arial;
    background-color: #FFF5E8;
    border-color: #ff00ff;
    overflow: scroll;
  }
  
 
  .row {margin: 0 15px 100px;}
  .row:after {
      content: "";
      display: table;
      clear: both;
  }
  * {
      box-sizing: border-box;
  }
  .column {
      float: left;
      width: 16.5%;
      padding: 0 10px;
      margin-bottom: 20px;
  }
  @media screen and (max-width: 800px) {
      .column {
          width: 100%;
          display: block;
          margin-bottom: 20px;
      }
  }
p {
    font-size: 12px;
    text-align: left;
    margin-left:5px;

}
.status{
font-size: 12px;
text-align: left;
margin-left:-30px;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 20px;
    margin-left: 400px;
    top: 50px;
    width: 500px;
    height: 100%;
    overflow: auto;
}
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 60%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}
.close {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.close_card {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close_card:hover,
.close_card:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}

.modal-body {padding: 2px 16px;}
.error {
    color: red;
    font-size: 90%;
}
    </style>
<script type="text/javascript">
		function errorMsg(elementID, Msg){
			document.getElementById(elementID).innerHTML = Msg
		}
		function check(){	
			fail = validate_title(document.task.title.value)
			fail += validate_description(document.task.description.value)
			if(fail == "") return true
			else return false	
		}
		function validate_title(field){
			titleErr = true
			if(field == "") return errorMsg("titleErr", "Title is empty")
			else{
				titleErr = false
				return ""
			}
		}
		function validate_description(field){
			descriptionErr = true
			if(field == ""){
				return errorMsg("descriptionErr","No Password was entered")
			}
			else{
				descriptionErr = false
				return ""
			}
		}
        </script>

    </head>
    <body>
    <div class="container">
        <form name ="task" action="task.php" method="post" onsubmit="return check()">
        <div>
            <label >Title</label><br>
           <input type="text" name="title" placeholder="Note title">
           <div class="error" id="titleErr"></div>
            </div>
            <div>
            <label >Description</label><br>
            <textarea name="description" placeholder="Note Description"></textarea>
            <div class="error" id="descriptionErr"></div>
            </div>
            <div>
            <button type="submit" class="btn">Add</button>
            </div>
        </form>
    </div>
    </body>
    </html> 
_END;

    if (isset($_POST['title']) && isset($_POST['description'])){
        $title = get_post($conn, $_POST['title']);
        if(empty($title)) {
            //  echo "<span>Title is empty </span> <br><br>";
            return false;
            }
        else{
            $description = get_post($conn, $_POST['description']);
            if(empty($description)){ 
                // echo "<span>Description is empty </span> <br><br>";
                return false;
            }
            else{
                $query = "INSERT INTO tasks (id,title, description,  state, uname)
                            VALUES (null, '$title', '$description', 1, '$username' );";

                $res = $conn->query($query);
                
                if(!$res){ echo "<span >INSERT failed </span> <br><br>";
                    // echo $query;
                }
            // else echo "<span>Task Created </span> <br><br>";

                $conn->close();
            }
        }
    }
    
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

function getTasks(){
    $conn = create_connection();
    $username = $_SESSION["user"];
    $query = "SELECT * FROM tasks WHERE uname = '$username'";
    $result = $conn->query($query);
	if(!$result) die (mysql_error());
	$rows = $result->num_rows;
	if($rows == 0) echo "<span>No Task Created </span><br><br>";
    else{
        echo<<<_END
        <h2 style="text-align: center"> Task Lists</h2>
        <div class="row">
_END; 
      
		for($i = 0; $i < $rows; ++$i){
            $edit="edit_";
            $edit .= $i;
            $myModal="myModal_";
            $myModal .= $i;
            $close="close_";
            $close .= $i;
			$result->data_seek($i);
			$row = $result->fetch_array(MYSQLI_ASSOC);
            $id = $row['id'];
			$title = $row['title'];
			$description = $row['description'];
            $status = $row['state'];
            $date = $row['create_date'];
            if($status == 1){$sta = "To do";}
            elseif($status == 2){ $sta = " In progress"; }
            elseif($status == 3){$sta = "Done"; }
echo<<<_END
    
    <div class="column">
        <div class="card">
            <h5 style='text-align: left; margin-left: 5px'> Title: $title </h5>
            <hr>
            <p>$description </p>
            <hr>
            <p >
            <form class="status" action="update_status.php" method="post"  style='display: inline'>
                 <input type="hidden" name="id" value="$id">
                 <select name="status" onchange="this.form.submit()" >
                <option value="0">Select Status</option>
                <option value="1">Todo</option>
                <option value="2">In progress</option>
                <option value="3">Done</option>
                </select>
            </form>
            </p>
            <p>Current Status: <strong>$sta </strong></p>
            <p>Date: $date</p>

            <div style="margin-bottom: 20px">
             
                <button class="btn" style='max-width:30%' onclick="show_edit($i)" id="$edit">Edit</button>
             
            <form action="delete.php" method="post" style='display: inline '>
                 <input type="hidden" name="id" value="$id">
                <button class="btn" style='max-width:30%' type="submit" name="delete">Delete</button>
            </form>
            </div>
        </div> 
    </div>

    <div id="$myModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span id="$close"class="close">&times;</span>
                <h2>Update a task</h2>
            </div>

            <div class="modal-body">
                <form name="form" method="post" action="edit.php">
                    <input type="hidden" name="id" value="$id">
                    <div>
                        <input type="text" name="title" placeholder="Note title" value="$title">
                    </div>
                    <div>
                        <textarea rows="10" name="description" placeholder="Note Description" >$description</textarea>
                    </div>
                    <div>
                    <button class="btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function show_edit(id){
        var modal = document.getElementById("myModal_" + id);
        var btn = document.getElementById("edit_" + id);
        var span = document.getElementById("close_" + id);
        btn.onclick = function() {
            modal.style.display = "block";
        }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        }
    </script>

_END;
 }
 echo<<<_END
 </div>
 _END;
$result->close();	
$conn->close();

}

}    

function mysql_error(){
	echo<<<_END
	Ooops! Something Went Wrong.
_END;

}



?>