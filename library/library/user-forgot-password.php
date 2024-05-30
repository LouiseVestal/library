<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(isset($_POST['change']))
{

    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);

    $sql = "SELECT EmailId FROM tblstudents WHERE EmailId=:email AND MobileNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0)
    {
        $con = "UPDATE tblstudents SET Password=:newpassword WHERE EmailId=:email AND MobileNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();

        echo "<script>alert('Your Password successfully changed');</script>";
    }
    else {
        echo "<script>alert('Email id or Mobile no is invalid');</script>";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System | Student Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESSE STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script type="text/javascript">
    function valid()
    {
        if(document.signup.password.value!= document.signup.confirmpassword.value)
        {
            alert("Password and Confirm Password Field do not match!!");
            document.signup.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>
    <script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data:'emailid='+$("#emailid").val(),
            type: "POST",
            success:function(data){
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function (){}
        });
    }
    </script>    

</head>
<body>
    <?php include('includes/header.php');?>
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">User Signup</h4>
            </div>
        </div>
             <div class="row">
           
    <div class="col-md-9 col-md-offset-1">
               <div class="panel panel-danger">
                        <div class="panel-heading">
                           SINGUP FORM
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">
    <div class="form-group">
        <label>Enter Full Name</label>
        <input class="form-control" type="text" name="fullanme" autocomplete="off" required />
    </div>

    <div class="form-group">
        <label>Mobile Number :</label>
        <input class="form-control" type="text" name="mobileno" maxlength="10" autocomplete="off" required />
    </div>
                                        
    <div class="form-group">
        <label>Enter Email</label>
        <input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()" autocomplete="off" required  />
       <span id="user-availability-status" style="font-size:12px;"></span> 
    </div>

    <div class="form-group">
        <label>Enter Password</label>
        <input class="form-control" type="password" name="password" autocomplete="off" required  />
    </div>

    <div class="form-group">
        <label>Confirm Password </label>
        <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required  />
    </div>
    <button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now </button>
                            </form>
                        </div>
                        </div>
                            </div>
        </div>
  </div>
    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <!-- CUSTOM SCRIPTS  -->
</body>
</html>
