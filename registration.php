<?php
session_start();
include('includes/config.php');
$errors = [];
$reg_success = false;

if(isset($_POST['submit']))
{
$regno=trim($_POST['regno']);
$fname=trim($_POST['fname']);
$mname=trim($_POST['mname']);
$lname=trim($_POST['lname']);
$gender=$_POST['gender'];
$contactno=trim($_POST['contact']);
$emailid=trim($_POST['email']);
$password=$_POST['password'];
$cpassword=$_POST['cpassword'];

// Server-side validation
if(empty($regno)) $errors[]="Registration Number is required.";
elseif(!preg_match('/^[A-Za-z0-9\-\/]{3,20}$/', $regno)) $errors[]="Registration Number must be 3-20 alphanumeric characters.";
if(empty($fname)) $errors[]="First Name is required.";
elseif(!preg_match('/^[A-Za-z\s]{2,50}$/', $fname)) $errors[]="First Name must be 2-50 letters only.";
if(!empty($mname) && !preg_match('/^[A-Za-z\s]{1,50}$/', $mname)) $errors[]="Middle Name must contain letters only.";
if(empty($lname)) $errors[]="Last Name is required.";
elseif(!preg_match('/^[A-Za-z\s]{2,50}$/', $lname)) $errors[]="Last Name must be 2-50 letters only.";
if(empty($gender)) $errors[]="Gender is required.";
if(empty($contactno)) $errors[]="Contact Number is required.";
elseif(!preg_match('/^[0-9]{10}$/', $contactno)) $errors[]="Contact Number must be exactly 10 digits.";
if(empty($emailid)) $errors[]="Email is required.";
elseif(!filter_var($emailid, FILTER_VALIDATE_EMAIL)) $errors[]="Please enter a valid email address.";
if(empty($password)) $errors[]="Password is required.";
elseif(strlen($password) < 6) $errors[]="Password must be at least 6 characters.";
if(empty($cpassword)) $errors[]="Confirm Password is required.";
elseif($password !== $cpassword) $errors[]="Password and Confirm Password do not match.";

if(empty($errors)){
$result ="SELECT count(*) FROM userRegistration WHERE email=? || regNo=?";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('ss',$emailid,$regno);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
if($count>0){
$errors[]="Registration number or email is already registered.";
}else{
$query="insert into userRegistration(regNo,firstName,middleName,lastName,gender,contactNo,email,password) values(?,?,?,?,?,?,?,?)";
$stmt = $mysqli->prepare($query);
$rc=$stmt->bind_param('sssssiss',$regno,$fname,$mname,$lname,$gender,$contactno,$emailid,$password);
$stmt->execute();
$reg_success=true;
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
	<meta name="theme-color" content="#3e454c">
	<title>Student Registration</title>
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
.error-msg{color:#a94442;font-size:12px;margin-top:4px;display:block;}
.has-error .form-control{border-color:#a94442;}
.validation-errors{background:#f2dede;border:1px solid #ebccd1;color:#a94442;padding:10px 15px;border-radius:4px;margin-bottom:15px;}
.alert-success-box{background:#dff0d8;border:1px solid #d6e9c6;color:#3c763d;padding:10px 15px;border-radius:4px;margin-bottom:15px;}
.password-strength{font-size:12px;margin-top:4px;display:block;}
.strength-weak{color:#a94442;} .strength-medium{color:#f0ad4e;} .strength-strong{color:#3c763d;}
</style>
</head>
<body>
<?php include('includes/header.php');?>
<div class="ts-main-content">
<?php include('includes/sidebar.php');?>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<h2 class="page-title">Student Registration</h2>
<div class="panel panel-default">
<div class="panel-heading">Register New Student</div>
<div class="panel-body">

<?php if($reg_success): ?>
<div class="alert-success-box"><strong>Success!</strong> Student registered successfully. <a href="index.php">Login here</a>.</div>
<?php endif; ?>
<?php if(!empty($errors)): ?>
<div class="validation-errors">
<strong>Please fix the following errors:</strong>
<ul style="margin:5px 0 0 0;padding-left:18px;">
<?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form method="post" action="" name="registration" class="form-horizontal" id="regForm" novalidate>
<div class="hr-dashed"></div>

<div class="form-group" id="grp-regno">
<label class="col-sm-3 control-label">Registration No <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="regno" id="regno" class="form-control" value="<?php echo isset($regno)?htmlspecialchars($regno):'';?>" placeholder="e.g. REG2024001" maxlength="20" onBlur="checkRegnoAvailability()">
<span class="error-msg" id="err-regno"></span></div></div>

<div class="form-group" id="grp-fname">
<label class="col-sm-3 control-label">First Name <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="fname" id="fname" class="form-control" value="<?php echo isset($fname)?htmlspecialchars($fname):'';?>" placeholder="First Name" maxlength="50">
<span class="error-msg" id="err-fname"></span></div></div>

<div class="form-group" id="grp-mname">
<label class="col-sm-3 control-label">Middle Name</label>
<div class="col-sm-7"><input type="text" name="mname" id="mname" class="form-control" value="<?php echo isset($mname)?htmlspecialchars($mname):'';?>" placeholder="Middle Name (optional)" maxlength="50">
<span class="error-msg" id="err-mname"></span></div></div>

<div class="form-group" id="grp-lname">
<label class="col-sm-3 control-label">Last Name <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="lname" id="lname" class="form-control" value="<?php echo isset($lname)?htmlspecialchars($lname):'';?>" placeholder="Last Name" maxlength="50">
<span class="error-msg" id="err-lname"></span></div></div>

<div class="form-group" id="grp-gender">
<label class="col-sm-3 control-label">Gender <span style="color:red">*</span></label>
<div class="col-sm-7"><select name="gender" id="gender" class="form-control">
<option value="">-- Select Gender --</option>
<option value="Male" <?php echo (isset($gender)&&$gender=='Male')?'selected':'';?>>Male</option>
<option value="Female" <?php echo (isset($gender)&&$gender=='Female')?'selected':'';?>>Female</option>
<option value="Other" <?php echo (isset($gender)&&$gender=='Other')?'selected':'';?>>Other</option>
</select><span class="error-msg" id="err-gender"></span></div></div>

<div class="form-group" id="grp-contact">
<label class="col-sm-3 control-label">Contact No <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="contact" id="contact" class="form-control" value="<?php echo isset($contactno)?htmlspecialchars($contactno):'';?>" placeholder="10-digit mobile number" maxlength="10">
<span class="error-msg" id="err-contact"></span></div></div>

<div class="form-group" id="grp-email">
<label class="col-sm-3 control-label">Email <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="email" name="email" id="email" class="form-control" value="<?php echo isset($emailid)?htmlspecialchars($emailid):'';?>" placeholder="student@email.com" maxlength="100" onBlur="checkAvailability()">
<span class="error-msg" id="err-email"></span>
<span id="user-availability-status" style="font-size:12px;"></span></div></div>

<div class="form-group" id="grp-password">
<label class="col-sm-3 control-label">Password <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="password" name="password" id="password" class="form-control" placeholder="Min 6 characters" onKeyUp="checkPasswordStrength()">
<span class="password-strength" id="pwd-strength"></span>
<span class="error-msg" id="err-password"></span></div></div>

<div class="form-group" id="grp-cpassword">
<label class="col-sm-3 control-label">Confirm Password <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Re-enter password">
<span class="error-msg" id="err-cpassword"></span></div></div>

<div class="col-sm-7 col-sm-offset-3">
<input type="submit" name="submit" value="Register" class="btn btn-primary">
<a href="index.php" class="btn btn-default">Back to Login</a>
</div>
</form>

</div></div></div></div></div></div></div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
function checkPasswordStrength(){
var pwd=document.getElementById('password').value;
var el=document.getElementById('pwd-strength');
if(pwd.length===0){el.textContent='';return;}
var score=0;
if(pwd.length>=6)score++;if(pwd.length>=10)score++;
if(/[A-Z]/.test(pwd))score++;if(/[0-9]/.test(pwd))score++;if(/[^A-Za-z0-9]/.test(pwd))score++;
if(score<=2){el.textContent='Strength: Weak';el.className='password-strength strength-weak';}
else if(score<=3){el.textContent='Strength: Medium';el.className='password-strength strength-medium';}
else{el.textContent='Strength: Strong';el.className='password-strength strength-strong';}
}
function checkAvailability(){
$.ajax({url:"check_availability.php",data:'email='+$("#email").val(),type:"POST",
success:function(data){$("#user-availability-status").html(data);}});
}
function checkRegnoAvailability(){
$.ajax({url:"check_availability.php",data:'regno='+$("#regno").val(),type:"POST",
success:function(data){$("#user-availability-status").html(data);}});
}
document.getElementById('regForm').addEventListener('submit',function(e){
var valid=true;
var fields=[
{id:'regno',err:'err-regno',grp:'grp-regno',msg:'Registration Number is required.',pattern:/^[A-Za-z0-9\-\/]{3,20}$/,patternMsg:'Registration Number must be 3-20 alphanumeric characters.'},
{id:'fname',err:'err-fname',grp:'grp-fname',msg:'First Name is required.',pattern:/^[A-Za-z\s]{2,50}$/,patternMsg:'First Name must be 2-50 letters only.'},
{id:'lname',err:'err-lname',grp:'grp-lname',msg:'Last Name is required.',pattern:/^[A-Za-z\s]{2,50}$/,patternMsg:'Last Name must be 2-50 letters only.'},
];
fields.forEach(function(f){
var v=document.getElementById(f.id).value.trim();
document.getElementById(f.err).textContent='';
document.getElementById(f.grp).classList.remove('has-error');
if(v===''){document.getElementById(f.err).textContent=f.msg;document.getElementById(f.grp).classList.add('has-error');valid=false;}
else if(f.pattern&&!f.pattern.test(v)){document.getElementById(f.err).textContent=f.patternMsg;document.getElementById(f.grp).classList.add('has-error');valid=false;}
});
// gender
document.getElementById('err-gender').textContent='';document.getElementById('grp-gender').classList.remove('has-error');
if(document.getElementById('gender').value===''){document.getElementById('err-gender').textContent='Gender is required.';document.getElementById('grp-gender').classList.add('has-error');valid=false;}
// contact
document.getElementById('err-contact').textContent='';document.getElementById('grp-contact').classList.remove('has-error');
var c=document.getElementById('contact').value.trim();
if(c===''){document.getElementById('err-contact').textContent='Contact Number is required.';document.getElementById('grp-contact').classList.add('has-error');valid=false;}
else if(!/^[0-9]{10}$/.test(c)){document.getElementById('err-contact').textContent='Contact Number must be exactly 10 digits.';document.getElementById('grp-contact').classList.add('has-error');valid=false;}
// email
document.getElementById('err-email').textContent='';document.getElementById('grp-email').classList.remove('has-error');
var em=document.getElementById('email').value.trim();
var emailPat=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
if(em===''){document.getElementById('err-email').textContent='Email is required.';document.getElementById('grp-email').classList.add('has-error');valid=false;}
else if(!emailPat.test(em)){document.getElementById('err-email').textContent='Please enter a valid email address.';document.getElementById('grp-email').classList.add('has-error');valid=false;}
// password
document.getElementById('err-password').textContent='';document.getElementById('grp-password').classList.remove('has-error');
var pw=document.getElementById('password').value;
if(pw===''){document.getElementById('err-password').textContent='Password is required.';document.getElementById('grp-password').classList.add('has-error');valid=false;}
else if(pw.length<6){document.getElementById('err-password').textContent='Password must be at least 6 characters.';document.getElementById('grp-password').classList.add('has-error');valid=false;}
// confirm
document.getElementById('err-cpassword').textContent='';document.getElementById('grp-cpassword').classList.remove('has-error');
var cp=document.getElementById('cpassword').value;
if(cp===''){document.getElementById('err-cpassword').textContent='Confirm Password is required.';document.getElementById('grp-cpassword').classList.add('has-error');valid=false;}
else if(pw!==cp){document.getElementById('err-cpassword').textContent='Passwords do not match.';document.getElementById('grp-cpassword').classList.add('has-error');valid=false;}
if(!valid)e.preventDefault();
});
</script>
</body></html>
