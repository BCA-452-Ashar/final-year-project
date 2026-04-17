<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

$profile_errors = [];
$pwd_errors = [];

//code for update email id
if(isset($_POST['update']))
{
$email=trim($_POST['emailid']);
$aid=$_SESSION['id'];
$udate=date('Y-m-d');

// Server-side validation for email
if(empty($email)) $profile_errors[] = "Email is required.";
elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) $profile_errors[] = "Please enter a valid email address.";
elseif(strlen($email) > 100) $profile_errors[] = "Email must not exceed 100 characters.";

if(empty($profile_errors)){
    $query="update admin set email=?,updation_date=? where id=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('ssi',$email,$udate,$aid);
    $stmt->execute();
    echo"<script>alert('Email id has been successfully updated');</script>";
}
}

// code for change password
if(isset($_POST['changepwd']))
{
  $op=trim($_POST['oldpassword']);
  $np=trim($_POST['newpassword']);
  $cp=trim($_POST['cpassword']);
  $ai=$_SESSION['id'];
  $udate=date('Y-m-d');

  // Server-side validation for password change
  if(empty($op)) $pwd_errors[] = "Old password is required.";
  if(empty($np)) $pwd_errors[] = "New password is required.";
  elseif(strlen($np) < 6) $pwd_errors[] = "New password must be at least 6 characters.";
  elseif(strlen($np) > 50) $pwd_errors[] = "New password must not exceed 50 characters.";
  if(empty($cp)) $pwd_errors[] = "Confirm password is required.";
  elseif($np !== $cp) $pwd_errors[] = "New password and Confirm password do not match.";

  if(empty($pwd_errors)){
      $sql="SELECT password FROM admin where password=?";
      $chngpwd = $mysqli->prepare($sql);
      $chngpwd->bind_param('s',$op);
      $chngpwd->execute();
      $chngpwd->store_result();
      $row_cnt=$chngpwd->num_rows;
      if($row_cnt>0)
      {
          $con="update admin set password=?,updation_date=?  where id=?";
          $chngpwd1 = $mysqli->prepare($con);
          $chngpwd1->bind_param('ssi',$np,$udate,$ai);
          $chngpwd1->execute();
          $_SESSION['msg']="Password Changed Successfully !!";
      }
      else
      {
          $_SESSION['msg']="Old Password not match !!";
      }
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
	<title>Admin Profile</title>
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
.password-strength { font-size: 12px; margin-top: 4px; display: block; }
.strength-weak { color: #a94442; }
.strength-medium { color: #f0ad4e; }
.strength-strong { color: #3c763d; }
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
					
						<h2 class="page-title">Admin Profile</h2>
	<?php	
$aid=$_SESSION['id'];
	$ret="select * from admin where id=?";
		$stmt= $mysqli->prepare($ret) ;
	 $stmt->bind_param('i',$aid);
	 $stmt->execute();
	 $res=$stmt->get_result();
	   while($row=$res->fetch_object())
	  {
	  	?>
						<div class="row">
							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">Admin profile details</div>
									<div class="panel-body">

									<?php if(!empty($profile_errors)): ?>
									<div class="validation-errors">
										<strong>Please fix the following errors:</strong>
										<ul style="margin:5px 0 0 0; padding-left:18px;">
										<?php foreach($profile_errors as $e): ?>
											<li><?php echo htmlspecialchars($e); ?></li>
										<?php endforeach; ?>
										</ul>
									</div>
									<?php endif; ?>

										<form method="post" class="form-horizontal" id="profileForm" novalidate>
											
											<div class="hr-dashed"></div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Username </label>
												<div class="col-sm-10">
													<input type="text" value="<?php echo htmlspecialchars($row->username);?>" disabled class="form-control"><span class="help-block m-b-none">
													Username can't be changed.</span> </div>
											</div>
											<div class="form-group" id="email-group">
												<label class="col-sm-2 control-label">Email <span style="color:red">*</span></label>
												<div class="col-sm-10">
	<input type="email" class="form-control" name="emailid" id="emailid" value="<?php echo htmlspecialchars($row->email);?>" placeholder="admin@example.com" maxlength="100" onBlur="checkAvailability()">
	<span class="error-msg" id="email-err"></span>
	<span id="user-availability-status" class="help-block m-b-none" style="font-size:12px;"></span>
												</div>
											</div>
<div class="form-group">
					<label class="col-sm-2 control-label">Reg Date</label>
					<div class="col-sm-10">
					<input type="text" class="form-control" value="<?php echo htmlspecialchars($row->reg_date);?>" disabled >
									</div>
									</div>

									<div class="col-sm-8 col-sm-offset-2">
										<button class="btn btn-default" type="button" onclick="window.history.back()">Cancel</button>
										<input class="btn btn-primary" type="submit" name="update" value="Update Profile">
									</div>
								</div>

							</form>

									</div>
								</div>
								<?php }  ?>
							<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">Change Password</div>
								<div class="panel-body">
				<form method="post" class="form-horizontal" name="changepwd" id="change-pwd" novalidate>

 <?php if(isset($_POST['changepwd'])): ?>
	<p style="color: red"><?php echo htmlspecialchars($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg']=""); ?></p>
	<?php endif; ?>

	<?php if(!empty($pwd_errors)): ?>
	<div class="validation-errors">
		<strong>Please fix the following errors:</strong>
		<ul style="margin:5px 0 0 0; padding-left:18px;">
		<?php foreach($pwd_errors as $e): ?>
			<li><?php echo htmlspecialchars($e); ?></li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

								<div class="hr-dashed"></div>
								<div class="form-group" id="oldpwd-group">
									<label class="col-sm-4 control-label">Old Password <span style="color:red">*</span></label>
									<div class="col-sm-8">
					<input type="password" value="" name="oldpassword" id="oldpassword" class="form-control" onBlur="checkpass()" placeholder="Enter old password">
					<span class="error-msg" id="oldpwd-err"></span>
					<span id="password-availability-status" class="help-block m-b-none" style="font-size:12px;"></span> </div>
								</div>
								<div class="form-group" id="newpwd-group">
									<label class="col-sm-4 control-label">New Password <span style="color:red">*</span></label>
									<div class="col-sm-8">
								<input type="password" class="form-control" name="newpassword" id="newpassword" value="" placeholder="Min. 6 characters" onKeyUp="checkPasswordStrength()">
								<span class="password-strength" id="pwd-strength"></span>
								<span class="error-msg" id="newpwd-err"></span>
									</div>
								</div>
<div class="form-group" id="cpwd-group">
				<label class="col-sm-4 control-label">Confirm Password <span style="color:red">*</span></label>
				<div class="col-sm-8">
				<input type="password" class="form-control" value="" id="cpassword" name="cpassword" placeholder="Re-enter new password">
				<span class="error-msg" id="cpwd-err"></span>
				</div>
				</div>

								<div class="col-sm-6 col-sm-offset-4">
									<button class="btn btn-default" type="button" onclick="window.history.back()">Cancel</button>
									<input type="submit" name="changepwd" Value="Change Password" class="btn btn-primary">
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
// Profile form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    var email = document.getElementById('emailid').value.trim();
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    document.getElementById('email-err').textContent = '';
    document.getElementById('email-group').classList.remove('has-error');
    if(email === '') {
        document.getElementById('email-err').textContent = 'Email is required.';
        document.getElementById('email-group').classList.add('has-error');
        e.preventDefault();
    } else if(!emailPattern.test(email)) {
        document.getElementById('email-err').textContent = 'Please enter a valid email address.';
        document.getElementById('email-group').classList.add('has-error');
        e.preventDefault();
    }
});

// Password form validation
document.getElementById('change-pwd').addEventListener('submit', function(e) {
    var valid = true;
    var op = document.getElementById('oldpassword').value.trim();
    var np = document.getElementById('newpassword').value.trim();
    var cp = document.getElementById('cpassword').value.trim();

    ['oldpwd','newpwd','cpwd'].forEach(function(id) {
        document.getElementById(id+'-err').textContent = '';
        document.getElementById(id+'-group').classList.remove('has-error');
    });

    if(op === '') {
        document.getElementById('oldpwd-err').textContent = 'Old password is required.';
        document.getElementById('oldpwd-group').classList.add('has-error');
        valid = false;
    }
    if(np === '') {
        document.getElementById('newpwd-err').textContent = 'New password is required.';
        document.getElementById('newpwd-group').classList.add('has-error');
        valid = false;
    } else if(np.length < 6) {
        document.getElementById('newpwd-err').textContent = 'New password must be at least 6 characters.';
        document.getElementById('newpwd-group').classList.add('has-error');
        valid = false;
    }
    if(cp === '') {
        document.getElementById('cpwd-err').textContent = 'Confirm password is required.';
        document.getElementById('cpwd-group').classList.add('has-error');
        valid = false;
    } else if(np !== cp) {
        document.getElementById('cpwd-err').textContent = 'Passwords do not match.';
        document.getElementById('cpwd-group').classList.add('has-error');
        valid = false;
    }
    if(!valid) e.preventDefault();
});

// Password strength indicator
function checkPasswordStrength() {
    var pwd = document.getElementById('newpassword').value;
    var el = document.getElementById('pwd-strength');
    if(pwd.length === 0) { el.textContent = ''; return; }
    var score = 0;
    if(pwd.length >= 6) score++;
    if(pwd.length >= 10) score++;
    if(/[A-Z]/.test(pwd)) score++;
    if(/[0-9]/.test(pwd)) score++;
    if(/[^A-Za-z0-9]/.test(pwd)) score++;
    if(score <= 2) { el.textContent = 'Strength: Weak'; el.className = 'password-strength strength-weak'; }
    else if(score <= 3) { el.textContent = 'Strength: Medium'; el.className = 'password-strength strength-medium'; }
    else { el.textContent = 'Strength: Strong'; el.className = 'password-strength strength-strong'; }
}

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

function checkpass() {
    $("#loaderIcon").show();
    jQuery.ajax({
        url: "check_availability.php",
        data:'oldpassword='+$("#oldpassword").val(),
        type: "POST",
        success:function(data){
            $("#password-availability-status").html(data);
            $("#loaderIcon").hide();
        },
        error:function (){}
    });
}
</script>
</body>

</html>
