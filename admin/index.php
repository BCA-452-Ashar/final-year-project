<?php
session_start();
include('includes/config.php');
$login_error = '';
if(isset($_POST['login']))
{
$username=trim($_POST['username']);
$password=trim($_POST['password']);

$errors = [];
if(empty($username)) $errors[] = "Username is required.";
if(empty($password)) $errors[] = "Password is required.";

if(empty($errors)){
$stmt=$mysqli->prepare("SELECT username,email,password,id FROM admin WHERE (userName=?|| email=?) and password=? ");
$stmt->bind_param('sss',$username,$username,$password);
$stmt->execute();
$stmt -> bind_result($username,$username,$password,$id);
$rs=$stmt->fetch();
$_SESSION['id']=$id;
if($rs){ header("location:dashboard.php"); exit(); }
else { $login_error = "Invalid Username/Email or Password."; }
}
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>Admin login</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<style>
.error-msg { color:#a94442; font-size:12px; margin-top:4px; display:block; }
.has-error .form-control { border-color:#a94442; }
.alert-danger-box { background:#f2dede; border:1px solid #ebccd1; color:#a94442; padding:10px 15px; border-radius:4px; margin-bottom:12px; }
</style>
</head>
<body>
	<div class="login-page bk-img" style="background-image: url(img/login-bg.jpg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3" style="margin-top:4%">
						<h1 class="text-center text-bold text-light mt-4x">Hostel Management System</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<h3 class="text-center text-bold">Admin Login</h3>
								<?php if($login_error): ?>
								<div class="alert-danger-box"><?php echo htmlspecialchars($login_error); ?></div>
								<?php endif; ?>
								<form action="" class="mt" method="post" id="adminLoginForm" novalidate>
									<div id="uname-group">
									<input type="text" placeholder="Username or Email" name="username" id="username" class="form-control mb" value="<?php echo isset($username) ? htmlspecialchars($_POST['username'] ?? '') : ''; ?>" maxlength="100">
									<span class="error-msg" id="uname-err"></span>
									</div>
									<br>
									<div id="pwd-group">
									<input type="password" placeholder="Password" name="password" id="password" class="form-control mb">
									<span class="error-msg" id="pwd-err"></span>
									</div>
									<br>
									<input type="submit" name="login" class="btn btn-primary btn-block" value="Login">
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
	<script src="js/main.js"></script>
<script>
document.getElementById('adminLoginForm').addEventListener('submit', function(e){
	var valid = true;
	var u = document.getElementById('username').value.trim();
	var p = document.getElementById('password').value.trim();
	document.getElementById('uname-err').textContent = '';
	document.getElementById('pwd-err').textContent = '';
	document.getElementById('uname-group').classList.remove('has-error');
	document.getElementById('pwd-group').classList.remove('has-error');
	if(u === ''){ document.getElementById('uname-err').textContent='Username is required.'; document.getElementById('uname-group').classList.add('has-error'); valid=false; }
	if(p === ''){ document.getElementById('pwd-err').textContent='Password is required.'; document.getElementById('pwd-group').classList.add('has-error'); valid=false; }
	if(!valid) e.preventDefault();
});
</script>
</body>
</html>
