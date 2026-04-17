<?php
session_start();
include('includes/config.php');
$pwd = '';
$errors = [];
$found = false;
if(isset($_POST['login']))
{
$email=trim($_POST['email']);
$contact=trim($_POST['contact']);

if(empty($email)) $errors[]="Email is required.";
elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[]="Please enter a valid email address.";
if(empty($contact)) $errors[]="Contact number is required.";
elseif(!preg_match('/^[0-9]{10}$/',$contact)) $errors[]="Contact number must be exactly 10 digits.";

if(empty($errors)){
$stmt=$mysqli->prepare("SELECT email,contactNo,password FROM userregistration WHERE (email=? && contactNo=?) ");
$stmt->bind_param('ss',$email,$contact);
$stmt->execute();
$stmt->bind_result($remail,$rcontact,$password);
$rs=$stmt->fetch();
if($rs){ $pwd=$password; $found=true; }
else { $errors[]="No account found with that Email and Contact Number combination."; }
}
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>Forgot Password</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<style>
.error-msg{color:#a94442;font-size:12px;margin-top:4px;display:block;}
.has-error .form-control{border-color:#a94442;}
.alert-danger-box{background:#f2dede;border:1px solid #ebccd1;color:#a94442;padding:10px 15px;border-radius:4px;margin-bottom:12px;}
.alert-success-box{background:#dff0d8;border:1px solid #d6e9c6;color:#3c763d;padding:10px 15px;border-radius:4px;margin-bottom:12px;}
</style>
</head>
<body>
	<div class="login-page bk-img" style="background-image: url(img/login-bg.jpg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center text-bold text-light mt-4x">Forgot Password</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<?php if($found): ?>
								<div class="alert-success-box">Your password is: <strong><?php echo htmlspecialchars($pwd); ?></strong><br>Please change it after login.</div>
								<?php endif; ?>
								<?php if(!empty($errors)): ?>
								<div class="alert-danger-box"><ul style="margin:0;padding-left:18px;"><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div>
								<?php endif; ?>
								<form action="" class="mt" method="post" id="forgotForm" novalidate>
									<div id="grp-email">
									<label class="text-uppercase text-sm">Your Email <span style="color:red">*</span></label>
									<input type="email" placeholder="Email" name="email" id="femail" class="form-control mb" value="<?php echo isset($_POST['email'])?htmlspecialchars($_POST['email']):'';?>" maxlength="100">
									<span class="error-msg" id="err-femail"></span>
									</div>
									<br>
									<div id="grp-contact">
									<label class="text-uppercase text-sm">Contact No <span style="color:red">*</span></label>
									<input type="text" placeholder="10-digit Contact number" name="contact" id="fcontact" class="form-control mb" value="<?php echo isset($_POST['contact'])?htmlspecialchars($_POST['contact']):'';?>" maxlength="10">
									<span class="error-msg" id="err-fcontact"></span>
									</div>
									<br>
									<input type="submit" name="login" class="btn btn-primary btn-block" value="Find my Password">
									<p class="text-center" style="margin-top:10px;"><a href="index.php">Back to Login</a></p>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
<script>
document.getElementById('forgotForm').addEventListener('submit',function(e){
var valid=true;
var em=document.getElementById('femail').value.trim();
var co=document.getElementById('fcontact').value.trim();
var emailPat=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
document.getElementById('err-femail').textContent='';document.getElementById('grp-email').classList.remove('has-error');
document.getElementById('err-fcontact').textContent='';document.getElementById('grp-contact').classList.remove('has-error');
if(em===''){document.getElementById('err-femail').textContent='Email is required.';document.getElementById('grp-email').classList.add('has-error');valid=false;}
else if(!emailPat.test(em)){document.getElementById('err-femail').textContent='Please enter a valid email address.';document.getElementById('grp-email').classList.add('has-error');valid=false;}
if(co===''){document.getElementById('err-fcontact').textContent='Contact number is required.';document.getElementById('grp-contact').classList.add('has-error');valid=false;}
else if(!/^[0-9]{10}$/.test(co)){document.getElementById('err-fcontact').textContent='Contact number must be exactly 10 digits.';document.getElementById('grp-contact').classList.add('has-error');valid=false;}
if(!valid)e.preventDefault();
});
</script>
</body></html>
