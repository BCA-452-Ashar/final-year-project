<?php
session_start();
include('includes/config.php');
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$ai=$_SESSION['id'];
$pwd_errors = [];

if(isset($_POST['changepwd']))
{
  $op=trim($_POST['oldpassword']);
  $np=trim($_POST['newpassword']);
  $cp=trim($_POST['cpassword']);
  $udate=date('d-m-Y h:i:s', time());

  if(empty($op)) $pwd_errors[]="Old password is required.";
  if(empty($np)) $pwd_errors[]="New password is required.";
  elseif(strlen($np)<6) $pwd_errors[]="New password must be at least 6 characters.";
  if(empty($cp)) $pwd_errors[]="Confirm password is required.";
  elseif($np !== $cp) $pwd_errors[]="New password and Confirm password do not match.";

  if(empty($pwd_errors)){
  $sql="SELECT password FROM userregistration where password=?";
  $chngpwd = $mysqli->prepare($sql);
  $chngpwd->bind_param('s',$op);
  $chngpwd->execute();
  $chngpwd->store_result();
  $row_cnt=$chngpwd->num_rows;
  if($row_cnt>0){
    $con="update userregistration set password=?,passUdateDate=? where id=?";
    $chngpwd1 = $mysqli->prepare($con);
    $chngpwd1->bind_param('ssi',$np,$udate,$ai);
    $chngpwd1->execute();
    $_SESSION['msg']="Password Changed Successfully !!";
  } else {
    $_SESSION['msg']="Old Password does not match.";
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
	<title>Change Password</title>
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
.password-strength{font-size:12px;margin-top:4px;display:block;}
.strength-weak{color:#a94442;}.strength-medium{color:#f0ad4e;}.strength-strong{color:#3c763d;}
</style>
</head>
<body>
<?php include('includes/header.php');?>
<div class="ts-main-content">
<?php include('includes/sidebar.php');?>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row"><div class="col-md-8 col-md-offset-2">
<h2 class="page-title">Change Password</h2>
<div class="panel panel-default">
<div class="panel-heading">Change Your Password</div>
<div class="panel-body">

<?php if(isset($_SESSION['msg']) && $_SESSION['msg']): ?>
<p style="color:<?php echo strpos($_SESSION['msg'],'Successfully')!==false?'green':'red';?>;font-weight:500;"><?php echo htmlspecialchars($_SESSION['msg']); ?><?php $_SESSION['msg']=""; ?></p>
<?php endif; ?>

<?php if(!empty($pwd_errors)): ?>
<div class="validation-errors"><strong>Please fix the following errors:</strong>
<ul style="margin:5px 0 0 0;padding-left:18px;"><?php foreach($pwd_errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" class="form-horizontal" name="changepwd" id="change-pwd" novalidate>
<div class="hr-dashed"></div>

<div class="form-group" id="grp-oldpwd">
<label class="col-sm-4 control-label">Old Password <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="password" name="oldpassword" id="oldpassword" class="form-control" placeholder="Enter current password" onBlur="checkpass()">
<span class="error-msg" id="err-oldpwd"></span>
<span id="password-availability-status" style="font-size:12px;"></span></div></div>

<div class="form-group" id="grp-newpwd">
<label class="col-sm-4 control-label">New Password <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="Min 6 characters" onKeyUp="checkPasswordStrength()">
<span class="password-strength" id="pwd-strength"></span>
<span class="error-msg" id="err-newpwd"></span></div></div>

<div class="form-group" id="grp-cpwd">
<label class="col-sm-4 control-label">Confirm Password <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Re-enter new password">
<span class="error-msg" id="err-cpwd"></span></div></div>

<div class="col-sm-7 col-sm-offset-4">
<input type="submit" name="changepwd" value="Change Password" class="btn btn-primary">
</div>
</form>
</div></div></div></div></div></div></div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
function checkPasswordStrength(){
var pwd=document.getElementById('newpassword').value;
var el=document.getElementById('pwd-strength');
if(pwd.length===0){el.textContent='';return;}
var score=0;
if(pwd.length>=6)score++;if(pwd.length>=10)score++;
if(/[A-Z]/.test(pwd))score++;if(/[0-9]/.test(pwd))score++;if(/[^A-Za-z0-9]/.test(pwd))score++;
if(score<=2){el.textContent='Strength: Weak';el.className='password-strength strength-weak';}
else if(score<=3){el.textContent='Strength: Medium';el.className='password-strength strength-medium';}
else{el.textContent='Strength: Strong';el.className='password-strength strength-strong';}
}
function checkpass(){
$.ajax({url:"check_availability.php",data:'oldpassword='+$("#oldpassword").val(),type:"POST",
success:function(data){$("#password-availability-status").html(data);}});
}
document.getElementById('change-pwd').addEventListener('submit',function(e){
var valid=true;
var op=document.getElementById('oldpassword').value.trim();
var np=document.getElementById('newpassword').value;
var cp=document.getElementById('cpassword').value;
['grp-oldpwd','grp-newpwd','grp-cpwd'].forEach(function(g){document.getElementById(g).classList.remove('has-error');});
['err-oldpwd','err-newpwd','err-cpwd'].forEach(function(g){document.getElementById(g).textContent='';});
if(op===''){document.getElementById('err-oldpwd').textContent='Old password is required.';document.getElementById('grp-oldpwd').classList.add('has-error');valid=false;}
if(np===''){document.getElementById('err-newpwd').textContent='New password is required.';document.getElementById('grp-newpwd').classList.add('has-error');valid=false;}
else if(np.length<6){document.getElementById('err-newpwd').textContent='New password must be at least 6 characters.';document.getElementById('grp-newpwd').classList.add('has-error');valid=false;}
if(cp===''){document.getElementById('err-cpwd').textContent='Confirm password is required.';document.getElementById('grp-cpwd').classList.add('has-error');valid=false;}
else if(np!==cp){document.getElementById('err-cpwd').textContent='Passwords do not match.';document.getElementById('grp-cpwd').classList.add('has-error');valid=false;}
if(!valid)e.preventDefault();
});
</script>
</body></html>
