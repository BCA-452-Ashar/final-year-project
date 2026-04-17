<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit']))
{
$coursecode=trim($_POST['cc']);
$coursesn=trim($_POST['cns']);
$coursefn=trim($_POST['cnf']);
$id=intval($_GET['id']);

// Server-side validation
$errors = [];
if(empty($coursecode)) $errors[] = "Course Code is required.";
elseif(!preg_match('/^[A-Za-z0-9\-_]{2,20}$/', $coursecode)) $errors[] = "Course Code must be 2-20 alphanumeric characters (letters, digits, - or _).";
if(empty($coursesn)) $errors[] = "Course Short Name is required.";
elseif(strlen($coursesn) < 2 || strlen($coursesn) > 50) $errors[] = "Course Short Name must be 2-50 characters.";
if(empty($coursefn)) $errors[] = "Course Full Name is required.";
elseif(strlen($coursefn) < 2 || strlen($coursefn) > 100) $errors[] = "Course Full Name must be 2-100 characters.";

if(empty($errors)){
    $query="update courses set course_code=?,course_sn=?,course_fn=? where id=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('sssi',$coursecode,$coursesn,$coursefn,$id);
    $stmt->execute();
    echo"<script>alert('Course has been Updated successfully');</script>";
}
}

?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	<title>Edit Course</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<style>
.error-msg { color: #a94442; font-size: 12px; margin-top: 4px; display: block; }
.has-error .form-control { border-color: #a94442; }
.validation-errors { background:#f2dede; border:1px solid #ebccd1; color:#a94442; padding:10px 15px; border-radius:4px; margin-bottom:15px; }
</style>
</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">
					
						<h2 class="page-title">Edit Course </h2>
	
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit courses</div>
									<div class="panel-body">

									<?php if(!empty($errors)): ?>
									<div class="validation-errors">
										<strong>Please fix the following errors:</strong>
										<ul style="margin:5px 0 0 0; padding-left:18px;">
										<?php foreach($errors as $e): ?>
											<li><?php echo htmlspecialchars($e); ?></li>
										<?php endforeach; ?>
										</ul>
									</div>
									<?php endif; ?>

										<form method="post" class="form-horizontal" id="editCourseForm" novalidate>
												<?php	
												$id=$_GET['id'];
	$ret="select * from courses where id=?";
		$stmt= $mysqli->prepare($ret) ;
	 $stmt->bind_param('i',$id);
	 $stmt->execute();
	 $res=$stmt->get_result();
	   while($row=$res->fetch_object())
	  {
	  	// Use POST values if re-submitted (to retain user input on error), else DB values
	  	$val_cc = isset($coursecode) ? $coursecode : $row->course_code;
	  	$val_cns = isset($coursesn) ? $coursesn : $row->course_sn;
	  	$val_cnf = isset($coursefn) ? $coursefn : $row->course_fn;
	  	?>
			<div class="hr-dashed"></div>
			<div class="form-group" id="cc-group">
			<label class="col-sm-2 control-label">Course Code <span style="color:red">*</span></label>
		<div class="col-sm-8">
		<input type="text" name="cc" id="cc" value="<?php echo htmlspecialchars($val_cc); ?>" class="form-control" placeholder="e.g. CS101" maxlength="20">
		<span class="error-msg" id="cc-err"></span>
		</div>
		</div>
		 <div class="form-group" id="cns-group">
		<label class="col-sm-2 control-label">Course Name (Short) <span style="color:red">*</span></label>
		<div class="col-sm-8">
	<input type="text" class="form-control" name="cns" id="cns" value="<?php echo htmlspecialchars($val_cns); ?>" placeholder="e.g. B.Tech" maxlength="50">
	<span class="error-msg" id="cns-err"></span>
			 </div>
			</div>
<div class="form-group" id="cnf-group">
				<label class="col-sm-2 control-label">Course Name (Full) <span style="color:red">*</span></label>
				<div class="col-sm-8">
				<input type="text" class="form-control" name="cnf" id="cnf" value="<?php echo htmlspecialchars($val_cnf); ?>" placeholder="e.g. Bachelor of Technology" maxlength="100">
				<span class="error-msg" id="cnf-err"></span>
				</div>
				</div>

<?php } ?>
								<div class="col-sm-8 col-sm-offset-2">
									<input class="btn btn-primary" type="submit" name="submit" value="Update Course">
								</div>
							</div>

						</form>

								</div>
								</div>
									
							
								</div>
							
									
							

								</div>
							</div>

						</div>
					</div> 	
					

				</div>
		</div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
<script>
function validateEditCourseForm() {
    var valid = true;
    var cc = document.getElementById('cc').value.trim();
    var cns = document.getElementById('cns').value.trim();
    var cnf = document.getElementById('cnf').value.trim();
    var ccPattern = /^[A-Za-z0-9\-_]{2,20}$/;

    ['cc','cns','cnf'].forEach(function(id) {
        document.getElementById(id+'-err').textContent = '';
        document.getElementById(id+'-group').classList.remove('has-error');
    });

    if(cc === '') {
        document.getElementById('cc-err').textContent = 'Course Code is required.';
        document.getElementById('cc-group').classList.add('has-error');
        valid = false;
    } else if(!ccPattern.test(cc)) {
        document.getElementById('cc-err').textContent = 'Course Code must be 2-20 alphanumeric characters (letters, digits, - or _).';
        document.getElementById('cc-group').classList.add('has-error');
        valid = false;
    }
    if(cns === '') {
        document.getElementById('cns-err').textContent = 'Course Short Name is required.';
        document.getElementById('cns-group').classList.add('has-error');
        valid = false;
    } else if(cns.length < 2 || cns.length > 50) {
        document.getElementById('cns-err').textContent = 'Course Short Name must be 2-50 characters.';
        document.getElementById('cns-group').classList.add('has-error');
        valid = false;
    }
    if(cnf === '') {
        document.getElementById('cnf-err').textContent = 'Course Full Name is required.';
        document.getElementById('cnf-group').classList.add('has-error');
        valid = false;
    } else if(cnf.length < 2 || cnf.length > 100) {
        document.getElementById('cnf-err').textContent = 'Course Full Name must be 2-100 characters.';
        document.getElementById('cnf-group').classList.add('has-error');
        valid = false;
    }
    return valid;
}
document.getElementById('editCourseForm').addEventListener('submit', function(e) {
    if(!validateEditCourseForm()) e.preventDefault();
});
</script>
</body>

</html>
