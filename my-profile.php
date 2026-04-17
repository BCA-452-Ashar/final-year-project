<?php
session_start();
include('includes/config.php');
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$aid=$_SESSION['id'];
$errors=[];

if(isset($_POST['update']))
{
$fname=trim($_POST['fname']);
$mname=trim($_POST['mname']);
$lname=trim($_POST['lname']);
$gender=$_POST['gender'];
$contactno=trim($_POST['contact']);
$udate=date('d-m-Y h:i:s', time());

if(empty($fname)) $errors[]="First Name is required.";
elseif(!preg_match('/^[A-Za-z\s]{2,50}$/',$fname)) $errors[]="First Name must be 2-50 letters only.";
if(!empty($mname) && !preg_match('/^[A-Za-z\s]{1,50}$/',$mname)) $errors[]="Middle Name must contain letters only.";
if(empty($lname)) $errors[]="Last Name is required.";
elseif(!preg_match('/^[A-Za-z\s]{2,50}$/',$lname)) $errors[]="Last Name must be 2-50 letters only.";
if(empty($gender)) $errors[]="Gender is required.";
if(empty($contactno)) $errors[]="Contact Number is required.";
elseif(!preg_match('/^[0-9]{10}$/',$contactno)) $errors[]="Contact Number must be exactly 10 digits.";

if(empty($errors)){
$query="update userRegistration set firstName=?,middleName=?,lastName=?,gender=?,contactNo=?,updationDate=? where id=?";
$stmt=$mysqli->prepare($query);
$rc=$stmt->bind_param('ssssisi',$fname,$mname,$lname,$gender,$contactno,$udate,$aid);
$stmt->execute();
echo"<script>alert('Profile updated successfully');</script>";
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
	<title>My Profile</title>
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
</style>
</head>
<body>
<?php include('includes/header.php');?>
<div class="ts-main-content">
<?php include('includes/sidebar.php');?>
<div class="content-wrapper">
<div class="container-fluid">
<?php
$ret="select * from userregistration where id=?";
$stmt=$mysqli->prepare($ret);
$stmt->bind_param('i',$aid);
$stmt->execute();
$res=$stmt->get_result();
while($row=$res->fetch_object()){
$val_fname=isset($fname)?$fname:$row->firstName;
$val_mname=isset($mname)?$mname:$row->middleName;
$val_lname=isset($lname)?$lname:$row->lastName;
$val_gender=isset($gender)?$gender:$row->gender;
$val_contact=isset($contactno)?$contactno:$row->contactNo;
?>
<div class="row"><div class="col-md-8 col-md-offset-2">
<h2 class="page-title">My Profile</h2>
<div class="panel panel-default">
<div class="panel-heading">Update Profile</div>
<div class="panel-body">

<?php if(!empty($errors)): ?>
<div class="validation-errors"><strong>Please fix the following errors:</strong>
<ul style="margin:5px 0 0 0;padding-left:18px;"><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" action="" class="form-horizontal" id="profileForm" novalidate>
<div class="hr-dashed"></div>

<div class="form-group">
<label class="col-sm-3 control-label">Registration No</label>
<div class="col-sm-7"><input type="text" name="regno" class="form-control" value="<?php echo htmlspecialchars($row->regNo);?>" readonly>
<span class="help-block m-b-none">Registration No can't be changed.</span></div></div>

<div class="form-group" id="grp-fname">
<label class="col-sm-3 control-label">First Name <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="fname" id="fname" class="form-control" value="<?php echo htmlspecialchars($val_fname);?>" maxlength="50">
<span class="error-msg" id="err-fname"></span></div></div>

<div class="form-group" id="grp-mname">
<label class="col-sm-3 control-label">Middle Name</label>
<div class="col-sm-7"><input type="text" name="mname" id="mname" class="form-control" value="<?php echo htmlspecialchars($val_mname);?>" maxlength="50">
<span class="error-msg" id="err-mname"></span></div></div>

<div class="form-group" id="grp-lname">
<label class="col-sm-3 control-label">Last Name <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="lname" id="lname" class="form-control" value="<?php echo htmlspecialchars($val_lname);?>" maxlength="50">
<span class="error-msg" id="err-lname"></span></div></div>

<div class="form-group" id="grp-gender">
<label class="col-sm-3 control-label">Gender <span style="color:red">*</span></label>
<div class="col-sm-7"><select name="gender" id="gender" class="form-control">
<option value="">-- Select Gender --</option>
<option value="Male" <?php echo $val_gender=='Male'?'selected':'';?>>Male</option>
<option value="Female" <?php echo $val_gender=='Female'?'selected':'';?>>Female</option>
<option value="Other" <?php echo $val_gender=='Other'?'selected':'';?>>Other</option>
</select><span class="error-msg" id="err-gender"></span></div></div>

<div class="form-group" id="grp-contact">
<label class="col-sm-3 control-label">Contact No <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="contact" id="contact" class="form-control" value="<?php echo htmlspecialchars($val_contact);?>" maxlength="10">
<span class="error-msg" id="err-contact"></span></div></div>

<div class="form-group">
<label class="col-sm-3 control-label">Email</label>
<div class="col-sm-7"><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row->email);?>" readonly>
<span class="help-block m-b-none">Email can't be changed.</span></div></div>

<div class="col-sm-7 col-sm-offset-3">
<input type="submit" name="update" value="Update Profile" class="btn btn-primary">
</div>
</form>
</div></div></div></div>
<?php } ?>
</div></div></div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
document.getElementById('profileForm').addEventListener('submit',function(e){
var valid=true;
var fn=document.getElementById('fname').value.trim();
var mn=document.getElementById('mname').value.trim();
var ln=document.getElementById('lname').value.trim();
var gn=document.getElementById('gender').value;
var co=document.getElementById('contact').value.trim();
var namePattern=/^[A-Za-z\s]{2,50}$/;
['grp-fname','grp-mname','grp-lname','grp-gender','grp-contact'].forEach(function(g){document.getElementById(g).classList.remove('has-error');});
['err-fname','err-mname','err-lname','err-gender','err-contact'].forEach(function(g){document.getElementById(g).textContent='';});
if(fn===''){document.getElementById('err-fname').textContent='First Name is required.';document.getElementById('grp-fname').classList.add('has-error');valid=false;}
else if(!namePattern.test(fn)){document.getElementById('err-fname').textContent='First Name must be 2-50 letters only.';document.getElementById('grp-fname').classList.add('has-error');valid=false;}
if(mn!==''&&!/^[A-Za-z\s]{1,50}$/.test(mn)){document.getElementById('err-mname').textContent='Middle Name must contain letters only.';document.getElementById('grp-mname').classList.add('has-error');valid=false;}
if(ln===''){document.getElementById('err-lname').textContent='Last Name is required.';document.getElementById('grp-lname').classList.add('has-error');valid=false;}
else if(!namePattern.test(ln)){document.getElementById('err-lname').textContent='Last Name must be 2-50 letters only.';document.getElementById('grp-lname').classList.add('has-error');valid=false;}
if(gn===''){document.getElementById('err-gender').textContent='Gender is required.';document.getElementById('grp-gender').classList.add('has-error');valid=false;}
if(co===''){document.getElementById('err-contact').textContent='Contact Number is required.';document.getElementById('grp-contact').classList.add('has-error');valid=false;}
else if(!/^[0-9]{10}$/.test(co)){document.getElementById('err-contact').textContent='Contact Number must be exactly 10 digits.';document.getElementById('grp-contact').classList.add('has-error');valid=false;}
if(!valid)e.preventDefault();
});
</script>
</body></html>
