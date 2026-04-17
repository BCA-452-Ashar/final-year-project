<?php
session_start();
include('includes/config.php');
$login_error = '';
if(isset($_POST['login']))
{
$emailreg=trim($_POST['emailreg']);
$password=trim($_POST['password']);

$errors = [];
if(empty($emailreg)) $errors[] = "Email / Registration Number is required.";
if(empty($password)) $errors[] = "Password is required.";

if(empty($errors)){
$stmt=$mysqli->prepare("SELECT email,password,id FROM userregistration WHERE (email=? || regNo=?) and password=? ");
$stmt->bind_param('sss',$emailreg,$emailreg,$password);
$stmt->execute();
$stmt->bind_result($email,$password,$id);
$rs=$stmt->fetch();
$stmt->close();
$_SESSION['id']=$id;
$_SESSION['login']=$emailreg;
if($rs){
$uid=$_SESSION['id'];
$uemail=$_SESSION['login'];
$ip=$_SERVER['REMOTE_ADDR'];
$geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
$addrDetailsArr = @unserialize(@file_get_contents($geopluginURL));
$city = isset($addrDetailsArr['geoplugin_city']) ? $addrDetailsArr['geoplugin_city'] : '';
$country = isset($addrDetailsArr['geoplugin_countryName']) ? $addrDetailsArr['geoplugin_countryName'] : '';
$log="insert into userLog(userId,userEmail,userIp,city,country) values('$uid','$uemail','$ip','$city','$country')";
$mysqli->query($log);
header("location:dashboard.php"); exit();
}
else { $login_error = "Invalid Email/Registration Number or Password."; }
}
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="theme-color" content="#3e454c">
	<title>Student Hostel - Login</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<style>
.error-msg { color:#a94442; font-size:12px; margin-top:4px; display:block; }
.has-error .form-control { border-color:#a94442; }
.alert-danger-box { background:#f2dede; border:1px solid #ebccd1; color:#a94442; padding:10px 15px; border-radius:4px; margin-bottom:12px; font-size:14px; }
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
								<h3 class="text-center text-bold">Student Login</h3>
								<?php if($login_error): ?>
								<div class="alert-danger-box"><?php echo htmlspecialchars($login_error); ?></div>
								<?php endif; ?>
								<form action="" class="mt" method="post" id="studentLoginForm" novalidate>
									<div id="emailreg-group">
									<input type="text" placeholder="Email / Registration Number" name="emailreg" id="emailreg" class="form-control mb" value="<?php echo isset($_POST['emailreg']) ? htmlspecialchars($_POST['emailreg']) : ''; ?>" maxlength="100">
									<span class="error-msg" id="emailreg-err"></span>
									</div>
									<br>
									<div id="pwd-group">
									<input type="password" placeholder="Password" name="password" id="password" class="form-control mb">
									<span class="error-msg" id="pwd-err"></span>
									</div>
									<br>
									<input type="submit" name="login" class="btn btn-primary btn-block" value="Login">
									<p class="text-center mt" style="margin-top:10px;">
										<a href="registration.php">New Student? Register Here</a> &nbsp;|&nbsp; <a href="forgot-password.php">Forgot Password?</a>
									</p>
								</form>
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
	<script src="js/main.js"></script>
<script>
document.getElementById('studentLoginForm').addEventListener('submit', function(e){
	var valid = true;
	var er = document.getElementById('emailreg').value.trim();
	var p = document.getElementById('password').value.trim();
	document.getElementById('emailreg-err').textContent='';
	document.getElementById('pwd-err').textContent='';
	document.getElementById('emailreg-group').classList.remove('has-error');
	document.getElementById('pwd-group').classList.remove('has-error');
	if(er===''){ document.getElementById('emailreg-err').textContent='Email / Registration Number is required.'; document.getElementById('emailreg-group').classList.add('has-error'); valid=false; }
	if(p===''){ document.getElementById('pwd-err').textContent='Password is required.'; document.getElementById('pwd-group').classList.add('has-error'); valid=false; }
	if(!valid) e.preventDefault();
});
</script>
</body>
</html>
