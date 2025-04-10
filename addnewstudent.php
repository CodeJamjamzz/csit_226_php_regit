<?php    
    include 'connect.php';    
    include 'header.php'
    //require_once 'includes/header.php'; 
?>
 

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #F0EBCE;
    }
    
    .header {
        background-color: #ffff00;
        color: black;
        text-align: center;
        padding: 20px;
    }

    .header h2 {
        margin: 0;
        font-size: 28px;
    }

    .container {
        width: 30%;
        margin: 20px auto;
        background: white;
        padding-top: 10px;
        padding-left: 20px;
        padding-right: 20px;
        box-shadow: 0 0 10px #ccc;
        border-radius: 8px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    .btn {
        margin-top: 20px;
    }

    label, select, input {
        font-size: 16px;
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        margin-top: 20px;
        margin-bottom: 15px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    select, input[type="text"], input[type="password"] {
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<div class="container" type="text">
    <center>
        <p style="color:white"><h2 style="margin-top:20px">Student Registration Page</h2></p>
    </center>

    <form method="post">
        <label>Firstname:</label>
        <input type="text" name="txtfirstname">

        <label>Lastname:</label>
        <input type="text" name="txtlastname">

        <label>Gender:</label>
        <select name="txtgender">
            <option value="">----</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <!-- <label>User Type:</label>
        <select name="txtusertype">
            <option value="">----</option>
            <option value="student">Student</option>
            <option value="employee">Employee</option>
        </select> -->

        <label>Username:</label>
        <input type="text" name="txtusername">

        <label>Password:</label>
        <input type="password" name="txtpassword">

        <label>Program:</label>
        <select name="txtprogram">
            <option value="">----</option>
            <option value="bsit">BSIT</option>
            <option value="bscs">BSCS</option>
        </select>

        <label>Year Level:</label>
        <select name="txtyearlevel">
            <option value="">----</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <input type="submit"  name="btnRegister" value="Register" style="margin-top=10px">
    </form>
</div>

<?php	
	if(isset($_POST['btnRegister'])){		
		//retrieve data from form and save the value to a variable
		//for tbluser
		$fname=$_POST['txtfirstname'];		
		$lname=$_POST['txtlastname'];
		$gender=$_POST['txtgender'];
		// $utype=$_POST['txtusertype'];
		$uname=$_POST['txtusername'];		
		$pword=$_POST['txtpassword'];	
		$hashedpw = password_hash($pword, PASSWORD_DEFAULT);
		
		//for tblstudent
		$prog=$_POST['txtprogram'];		
		$yearlevel=$_POST['txtyearlevel'];		
		
						
		//save data to tbluser	
		$sql1 ="Insert into tbluser(firstname,lastname,gender, usertype, username, password) values('".$fname."','".$lname."','".$gender."','student', '".$uname."', '".$hashedpw."')";
		mysqli_query($connection,$sql1);
				
		$last_id = mysqli_insert_id($connection);
		 
		$sql2 ="Insert into tblstudent(program, yearlevel, uid) values('".$prog."','.$yearlevel.','.$last_id.')";
		mysqli_query($connection,$sql2);
		echo "<script language='javascript'>
			alert('New record saved.');
		      </script>";
		header("location: dashboard.php");
		
			
		
	}
		

?>


<?php include 'footer.php' ?>