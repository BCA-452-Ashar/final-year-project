<?php session_start();
error_reporting(0);
include('includes/config.php');
include('includes/checklogin.php');
check_login();
//code for add courses
if(isset($_POST['submit']))
{
$coursecode=trim($_POST['cc']);
$coursesn=trim($_POST['cns']);
$coursefn=trim($_POST['cnf']);

// Server-side validation
$errors = [];
if(empty($coursecode)) $errors[] = "Course Code is required.";
elseif(!preg_match('/^[A-Za-z0-9\-_]{2,20}$/', $coursecode)) $errors[] = "Course Code must be 2-20 alphanumeric characters (letters, digits, - or _).";
if(empty($coursesn)) $errors[] = "Course Short Name is required.";
elseif(strlen($coursesn) < 2 || strlen($coursesn) > 50) $errors[] = "Course Short Name must be 2-50 characters.";
if(empty($coursefn)) $errors[] = "Course Full Name is required.";
elseif(strlen($coursefn) < 2 || strlen($coursefn) > 100) $errors[] = "Course Full Name must be 2-100 characters.";

if(empty($errors)){
$query="insert into  courses (course_code,course_sn,course_fn) values(?,?,?)";
$stmt = $mysqli->prepare($query);
$rc=$stmt->bind_param('sss',$coursecode,$coursesn,$coursefn);
$stmt->execute();
echo"<script>alert('Course has been added successfully');</script>";
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
	<title>Add Courses</title>
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
					
						<h2 class="page-title">Add Courses </h2>
	
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Add courses</div>
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

										<form method="post" class="form-horizontal" id="addCourseForm" novalidate>
											
											<div class="hr-dashed"></div>
											<div class="form-group <?php echo (!empty($errors) && (empty($coursecode) || !preg_match('/^[A-Za-z0-9\-_]{2,20}$/', $coursecode ?? ''))) ? 'has-error' : ''; ?>">
												<label class="col-sm-2 control-label">Course Code <span style="color:red">*</span></label>
												<div class="col-sm-8">
													<input type="text" value="<?php echo isset($coursecode) ? htmlspecialchars($coursecode) : ''; ?>" name="cc" id="cc" class="form-control" placeholder="e.g. CS101" maxlength="20">
													<span class="error-msg" id="cc-err"></span>
												</div>
											</div>
											<div class="form-group <?php echo (!empty($errors) && empty($coursesn ?? '')) ? 'has-error' : ''; ?>">
												<label class="col-sm-2 control-label">Course Name (Short) <span style="color:red">*</span></label>
												<div class="col-sm-8">
												<input type="text" class="form-control" name="cns" id="cns" value="<?php echo isset($coursesn) ? htmlspecialchars($coursesn) : ''; ?>" placeholder="e.g. B.Tech" maxlength="50">
												<span class="error-msg" id="cns-err"></span>
												</div>
											</div>
											<div class="form-group <?php echo (!empty($errors) && empty($coursefn ?? '')) ? 'has-error' : ''; ?>">
											<label class="col-sm-2 control-label">Course Name (Full) <span style="color:red">*</span></label>
											<div class="col-sm-8">
											<input type="text" class="form-control" name="cnf" id="cnf" value="<?php echo isset($coursefn) ? htmlspecialchars($coursefn) : ''; ?>" placeholder="e.g. Bachelor of Technology" maxlength="100">
											<span class="error-msg" id="cnf-err"></span>
											</div>
											</div>

												<div class="col-sm-8 col-sm-offset-2">
													<input class="btn btn-primary" type="submit" name="submit" value="Add course">
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
function validateCourseForm() {
    var valid = true;

    var cc = document.getElementById('cc').value.trim();
    var cns = document.getElementById('cns').value.trim();
    var cnf = document.getElementById('cnf').value.trim();
    var ccPattern = /^[A-Za-z0-9\-_]{2,20}$/;

    document.getElementById('cc-err').textContent = '';
    document.getElementById('cns-err').textContent = '';
    document.getElementById('cnf-err').textContent = '';
    document.getElementById('cc').closest('.form-group').classList.remove('has-error');
    document.getElementById('cns').closest('.form-group').classList.remove('has-error');
    document.getElementById('cnf').closest('.form-group').classList.remove('has-error');

    if(cc === '') {
        document.getElementById('cc-err').textContent = 'Course Code is required.';
        document.getElementById('cc').closest('.form-group').classList.add('has-error');
        valid = false;
    } else if(!ccPattern.test(cc)) {
        document.getElementById('cc-err').textContent = 'Course Code must be 2-20 alphanumeric characters (letters, digits, - or _).';
        document.getElementById('cc').closest('.form-group').classList.add('has-error');
        valid = false;
    }

    if(cns === '') {
        document.getElementById('cns-err').textContent = 'Course Short Name is required.';
        document.getElementById('cns').closest('.form-group').classList.add('has-error');
        valid = false;
    } else if(cns.length < 2 || cns.length > 50) {
        document.getElementById('cns-err').textContent = 'Course Short Name must be 2-50 characters.';
        document.getElementById('cns').closest('.form-group').classList.add('has-error');
        valid = false;
    }

    if(cnf === '') {
        document.getElementById('cnf-err').textContent = 'Course Full Name is required.';
        document.getElementById('cnf').closest('.form-group').classList.add('has-error');
        valid = false;
    } else if(cnf.length < 2 || cnf.length > 100) {
        document.getElementById('cnf-err').textContent = 'Course Full Name must be 2-100 characters.';
        document.getElementById('cnf').closest('.form-group').classList.add('has-error');
        valid = false;
    }

    return valid;
}

document.getElementById('addCourseForm').addEventListener('submit', function(e) {
    if(!validateCourseForm()) e.preventDefault();
});
</script>
</body>

</html>
