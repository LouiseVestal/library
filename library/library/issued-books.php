<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

// Verify Session Variable
if(isset($_SESSION['stdid'])) {
    $sid = $_SESSION['stdid'];
    echo "Session Student ID: " . $sid . "<br>"; // Debug statement
} else {
    echo "Session Student ID is not set!<br>";
}

// Check Error Reporting
echo "Error Reporting Level: " . error_reporting() . "<br>";

// Verify Database Connection
try {
    $dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    echo "Database Connection Successful!<br>";
} catch(PDOException $e) {
    echo "Failed to connect to the database: " . $e->getMessage() . "<br>";
}

// Your existing code
if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
    exit();
}

$sql = "SELECT tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id AS rid 
        FROM tblissuedbookdetails 
        JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentID 
        JOIN tblbooks ON tblbooks.id = tblissuedbookdetails.BookId 
        WHERE tblstudents.StudentId = :sid 
        ORDER BY tblissuedbookdetails.id DESC";
echo "SQL Query: " . $sql . "<br>";

$query = $dbh->prepare($sql);
$query->bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System | Manage Issued Books</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<?php include('includes/header.php');?>
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Manage Issued Books</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Issued Books 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Name</th>
                                            <th>ISBN</th>
                                            <th>Issued Date</th>
                                            <th>Return Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $cnt = 1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>                                  
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt);?></td>
                                                    <td class="center"><?php echo htmlentities($result->BookName);?></td>
                                                    <td class="center"><?php echo htmlentities($result->ISBNNumber);?></td>
                                                    <td class="center"><?php echo htmlentities($result->IssuesDate);?></td>
                                                    <td class="center"><?php if($result->ReturnDate == "") { ?>
                                                            <span style="color:red"><?php echo htmlentities("Not Return Yet"); ?></span>
                                                        <?php } else {
                                                            echo htmlentities($result->ReturnDate);
                                                        } ?></td>
                                                    <td class="center"><?php echo htmlentities($result->Action);?></td>
                                                </tr>
                                                <?php $cnt = $cnt + 1;
                                            }
                                        } ?>                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php');?>
<!-- CORE JQUERY  -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS  -->
<script src="assets/js/bootstrap.js"></script>
<!-- DATATABLE SCRIPTS  -->
<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<!-- CUSTOM SCRIPTS  -->
<script src="assets/js/custom.js"></script>
</body>
</html>
