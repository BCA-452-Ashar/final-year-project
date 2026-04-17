<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
$errors=[];

if(isset($_POST['submit']))
{
$roomno=intval($_POST['room']);
$seater=intval($_POST['seater']);
$feespm=floatval($_POST['fpm']);
$foodstatus=intval($_POST['foodstatus']);
$stayfrom=trim($_POST['stayf']);
$duration=trim($_POST['duration']);
$course=trim($_POST['course']);
$regno=trim($_POST['regno']);
$fname=trim($_POST['fname']);
$mname=trim($_POST['mname']);
$lname=trim($_POST['lname']);
$gender=$_POST['gender'];
$contactno=trim($_POST['contact']);
$emailid=trim($_POST['email']);
$emcntno=trim($_POST['econtact']);
$gurname=trim($_POST['gname']);
$gurrelation=trim($_POST['grelation']);
$gurcntno=trim($_POST['gcontact']);
$caddress=trim($_POST['address']);
$ccity=trim($_POST['city']);
$cstate=trim($_POST['state']);
$cpincode=trim($_POST['pincode']);
$paddress=trim($_POST['paddress']);
$pcity=trim($_POST['pcity']);
$pstate=trim($_POST['pstate']);
$ppincode=trim($_POST['ppincode']);

if($roomno<=0) $errors[]="Please select a valid room.";
if($seater<=0) $errors[]="Please select seater.";
if($feespm<=0) $errors[]="Fees per month must be a positive number.";
if(empty($stayfrom)) $errors[]="Stay from date is required.";
if(empty($duration)) $errors[]="Duration is required.";
if(empty($course)) $errors[]="Course is required.";
if(empty($regno)) $errors[]="Registration Number is required.";
if(empty($fname)) $errors[]="First Name is required.";
elseif(!preg_match('/^[A-Za-z\s]{2,50}$/',$fname)) $errors[]="First Name must be letters only.";
if(empty($lname)) $errors[]="Last Name is required.";
elseif(!preg_match('/^[A-Za-z\s]{2,50}$/',$lname)) $errors[]="Last Name must be letters only.";
if(empty($gender)) $errors[]="Gender is required.";
if(empty($contactno)) $errors[]="Contact Number is required.";
elseif(!preg_match('/^[0-9]{10}$/',$contactno)) $errors[]="Contact Number must be exactly 10 digits.";
if(empty($emailid)) $errors[]="Email is required.";
elseif(!filter_var($emailid,FILTER_VALIDATE_EMAIL)) $errors[]="Please enter a valid email address.";
if(!empty($emcntno)&&!preg_match('/^[0-9]{10}$/',$emcntno)) $errors[]="Emergency Contact must be exactly 10 digits.";
if(empty($gurname)) $errors[]="Guardian Name is required.";
if(empty($caddress)) $errors[]="Correspondence Address is required.";
if(empty($ccity)) $errors[]="City is required.";
if(empty($cstate)) $errors[]="State is required.";
if(!empty($cpincode)&&!preg_match('/^[0-9]{6}$/',$cpincode)) $errors[]="Pincode must be 6 digits.";

if(empty($errors)){
$result="SELECT count(*) FROM userRegistration WHERE email=? || regNo=?";
$stmt=$mysqli->prepare($result);
$stmt->bind_param('ss',$emailid,$regno);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
if($count>0){
$errors[]="Registration number or email is already registered.";
}else{
$query="insert into registration(roomno,seater,feespm,foodstatus,stayfrom,duration,course,regno,firstName,middleName,lastName,gender,contactno,emailid,egycontactno,guardianName,guardianRelation,guardianContactno,corresAddress,corresCIty,corresState,corresPincode,pmntAddress,pmntCity,pmnatetState,pmntPincode) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt=$mysqli->prepare($query);
$rc=$stmt->bind_param('iiiisisissssisississsisssi',$roomno,$seater,$feespm,$foodstatus,$stayfrom,$duration,$course,$regno,$fname,$mname,$lname,$gender,$contactno,$emailid,$emcntno,$gurname,$gurrelation,$gurcntno,$caddress,$ccity,$cstate,$cpincode,$paddress,$pcity,$pstate,$ppincode);
$stmt->execute();
$stmt->close();
$query1="insert into userregistration(regNo,firstName,middleName,lastName,gender,contactNo,email,password) values(?,?,?,?,?,?,?,?)";
$stmt1=$mysqli->prepare($query1);
$stmt1->bind_param('sssssiss',$regno,$fname,$mname,$lname,$gender,$contactno,$emailid,$contactno);
$stmt1->execute();
echo"<script>alert('Student registered successfully');</script>";
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
	<title>Student Registration (Admin)</title>
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
<div class="row"><div class="col-md-12">
<h2 class="page-title">Register Student</h2>
<div class="panel panel-default">
<div class="panel-heading">Student Registration Form</div>
<div class="panel-body">

<?php if(!empty($errors)): ?>
<div class="validation-errors"><strong>Please fix the following errors:</strong>
<ul style="margin:5px 0 0 0;padding-left:18px;"><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" action="" class="form-horizontal" id="adminRegForm" novalidate>
<?php
$ret="select * from rooms";
$rstmt=$mysqli->prepare($ret);
$rstmt->execute();
$rres=$rstmt->get_result();
?>
<div class="hr-dashed"></div>
<h4>Room Details</h4>
<div class="form-group" id="grp-room">
<label class="col-sm-3 control-label">Select Room <span style="color:red">*</span></label>
<div class="col-sm-7">
<select name="room" id="room" class="form-control" onChange="getSeater(this.value)">
<option value="">-- Select Room --</option>
<?php while($rrow=$rres->fetch_object()): ?>
<option value="<?php echo $rrow->room_no;?>"><?php echo "Room ".$rrow->room_no." (Seater: ".$rrow->seater.")";?></option>
<?php endwhile; ?>
</select><span class="error-msg" id="err-room"></span></div></div>

<div class="form-group"><label class="col-sm-3 control-label">Seater</label>
<div class="col-sm-7"><input type="text" name="seater" id="seater" class="form-control" readonly placeholder="Auto-filled on room selection"></div></div>

<div class="form-group"><label class="col-sm-3 control-label">Fees PM</label>
<div class="col-sm-7"><input type="text" name="fpm" id="fpm" class="form-control" readonly placeholder="Auto-filled on room selection"></div></div>

<div class="form-group" id="grp-food">
<label class="col-sm-3 control-label">Food Status <span style="color:red">*</span></label>
<div class="col-sm-7"><select name="foodstatus" id="foodstatus" class="form-control">
<option value="">-- Select --</option>
<option value="1" <?php echo (isset($foodstatus)&&$foodstatus==1)?'selected':'';?>>With Food</option>
<option value="0" <?php echo (isset($foodstatus)&&$foodstatus===0)?'selected':'';?>>Without Food</option>
</select><span class="error-msg" id="err-food"></span></div></div>

<div class="form-group" id="grp-stayf">
<label class="col-sm-3 control-label">Stay From <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="date" name="stayf" id="stayf" class="form-control" value="<?php echo isset($stayfrom)?htmlspecialchars($stayfrom):'';?>">
<span class="error-msg" id="err-stayf"></span></div></div>

<div class="form-group" id="grp-duration">
<label class="col-sm-3 control-label">Duration (months) <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="number" name="duration" id="duration" class="form-control" min="1" max="60" value="<?php echo isset($duration)?htmlspecialchars($duration):'';?>" placeholder="e.g. 12">
<span class="error-msg" id="err-duration"></span></div></div>

<div class="form-group" id="grp-course">
<label class="col-sm-3 control-label">Course <span style="color:red">*</span></label>
<div class="col-sm-7">
<?php $cstmt=$mysqli->prepare("select * from courses"); $cstmt->execute(); $cres=$cstmt->get_result(); ?>
<select name="course" id="course" class="form-control">
<option value="">-- Select Course --</option>
<?php while($crow=$cres->fetch_object()): ?><option value="<?php echo $crow->id;?>"><?php echo htmlspecialchars($crow->course_fn);?></option><?php endwhile; ?>
</select><span class="error-msg" id="err-course"></span></div></div>

<div class="hr-dashed"></div><h4>Personal Details</h4>

<div class="form-group" id="grp-regno">
<label class="col-sm-3 control-label">Registration No <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="regno" id="regno" class="form-control" value="<?php echo isset($regno)?htmlspecialchars($regno):'';?>" maxlength="20">
<span class="error-msg" id="err-regno"></span></div></div>

<div class="form-group" id="grp-fname">
<label class="col-sm-3 control-label">First Name <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="fname" id="fname" class="form-control" value="<?php echo isset($fname)?htmlspecialchars($fname):'';?>" maxlength="50">
<span class="error-msg" id="err-fname"></span></div></div>

<div class="form-group"><label class="col-sm-3 control-label">Middle Name</label>
<div class="col-sm-7"><input type="text" name="mname" class="form-control" value="<?php echo isset($mname)?htmlspecialchars($mname):'';?>" maxlength="50"></div></div>

<div class="form-group" id="grp-lname">
<label class="col-sm-3 control-label">Last Name <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="lname" id="lname" class="form-control" value="<?php echo isset($lname)?htmlspecialchars($lname):'';?>" maxlength="50">
<span class="error-msg" id="err-lname"></span></div></div>

<div class="form-group" id="grp-gender">
<label class="col-sm-3 control-label">Gender <span style="color:red">*</span></label>
<div class="col-sm-7"><select name="gender" id="gender" class="form-control">
<option value="">-- Select --</option>
<option value="Male" <?php echo (isset($gender)&&$gender=='Male')?'selected':'';?>>Male</option>
<option value="Female" <?php echo (isset($gender)&&$gender=='Female')?'selected':'';?>>Female</option>
<option value="Other" <?php echo (isset($gender)&&$gender=='Other')?'selected':'';?>>Other</option>
</select><span class="error-msg" id="err-gender"></span></div></div>

<div class="form-group" id="grp-contact">
<label class="col-sm-3 control-label">Contact No <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="contact" id="contact" class="form-control" value="<?php echo isset($contactno)?htmlspecialchars($contactno):'';?>" maxlength="10">
<span class="error-msg" id="err-contact"></span></div></div>

<div class="form-group" id="grp-email">
<label class="col-sm-3 control-label">Email <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="email" name="email" id="email" class="form-control" value="<?php echo isset($emailid)?htmlspecialchars($emailid):'';?>" maxlength="100">
<span class="error-msg" id="err-email"></span></div></div>

<div class="form-group"><label class="col-sm-3 control-label">Emergency Contact</label>
<div class="col-sm-7"><input type="text" name="econtact" class="form-control" value="<?php echo isset($emcntno)?htmlspecialchars($emcntno):'';?>" maxlength="10" placeholder="10-digit number (optional)"></div></div>

<div class="hr-dashed"></div><h4>Guardian Details</h4>

<div class="form-group" id="grp-gname">
<label class="col-sm-3 control-label">Guardian Name <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="gname" id="gname" class="form-control" value="<?php echo isset($gurname)?htmlspecialchars($gurname):'';?>" maxlength="100">
<span class="error-msg" id="err-gname"></span></div></div>

<div class="form-group"><label class="col-sm-3 control-label">Guardian Relation</label>
<div class="col-sm-7"><input type="text" name="grelation" class="form-control" value="<?php echo isset($gurrelation)?htmlspecialchars($gurrelation):'';?>" maxlength="50"></div></div>

<div class="form-group"><label class="col-sm-3 control-label">Guardian Contact</label>
<div class="col-sm-7"><input type="text" name="gcontact" class="form-control" value="<?php echo isset($gurcntno)?htmlspecialchars($gurcntno):'';?>" maxlength="10" placeholder="10-digit (optional)"></div></div>

<div class="hr-dashed"></div><h4>Correspondence Address</h4>

<div class="form-group" id="grp-address">
<label class="col-sm-3 control-label">Address <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="address" id="address" class="form-control" value="<?php echo isset($caddress)?htmlspecialchars($caddress):'';?>" maxlength="200">
<span class="error-msg" id="err-address"></span></div></div>

<div class="form-group" id="grp-city">
<label class="col-sm-3 control-label">City <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="city" id="city" class="form-control" value="<?php echo isset($ccity)?htmlspecialchars($ccity):'';?>" maxlength="50">
<span class="error-msg" id="err-city"></span></div></div>

<div class="form-group" id="grp-state">
<label class="col-sm-3 control-label">State <span style="color:red">*</span></label>
<div class="col-sm-7"><input type="text" name="state" id="state" class="form-control" value="<?php echo isset($cstate)?htmlspecialchars($cstate):'';?>" maxlength="50">
<span class="error-msg" id="err-state"></span></div></div>

<div class="form-group"><label class="col-sm-3 control-label">Pincode</label>
<div class="col-sm-7"><input type="text" name="pincode" class="form-control" value="<?php echo isset($cpincode)?htmlspecialchars($cpincode):'';?>" maxlength="6" placeholder="6-digit pincode (optional)"></div></div>

<div class="hr-dashed"></div><h4>Permanent Address</h4>
<div class="form-group"><label class="col-sm-3 control-label">Address</label>
<div class="col-sm-7"><input type="text" name="paddress" class="form-control" value="<?php echo isset($paddress)?htmlspecialchars($paddress):'';?>" maxlength="200"></div></div>
<div class="form-group"><label class="col-sm-3 control-label">City</label>
<div class="col-sm-7"><input type="text" name="pcity" class="form-control" value="<?php echo isset($pcity)?htmlspecialchars($pcity):'';?>" maxlength="50"></div></div>
<div class="form-group"><label class="col-sm-3 control-label">State</label>
<div class="col-sm-7"><input type="text" name="pstate" class="form-control" value="<?php echo isset($pstate)?htmlspecialchars($pstate):'';?>" maxlength="50"></div></div>
<div class="form-group"><label class="col-sm-3 control-label">Pincode</label>
<div class="col-sm-7"><input type="text" name="ppincode" class="form-control" value="<?php echo isset($ppincode)?htmlspecialchars($ppincode):'';?>" maxlength="6"></div></div>

<div class="col-sm-7 col-sm-offset-3" style="margin-top:10px;">
<input type="submit" name="submit" value="Register Student" class="btn btn-primary">
</div>
</form>
</div></div></div></div></div></div></div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/main.js"></script>
<script>
function getSeater(roomno){
if(roomno===''){document.getElementById('seater').value='';document.getElementById('fpm').value='';return;}
$.ajax({url:"get_seater.php",data:{room:roomno},type:"POST",success:function(data){
var d=data.split(',');
document.getElementById('seater').value=d[0]||'';
document.getElementById('fpm').value=d[1]||'';
}});
}
document.getElementById('adminRegForm').addEventListener('submit',function(e){
var valid=true;

function showError(errId, grpId, msg){
  document.getElementById(errId).textContent = msg;
  document.getElementById(grpId).classList.add('has-error');
  valid = false;
}
function clearError(errId, grpId){
  document.getElementById(errId).textContent = '';
  document.getElementById(grpId).classList.remove('has-error');
}

// --- Room ---
clearError('err-room','grp-room');
if(document.getElementById('room').value==='') showError('err-room','grp-room','Please select a room.');

// --- Food Status ---
clearError('err-food','grp-food');
if(document.getElementById('foodstatus').value==='') showError('err-food','grp-food','Food status is required.');

// --- Stay From ---
clearError('err-stayf','grp-stayf');
if(document.getElementById('stayf').value==='') showError('err-stayf','grp-stayf','Stay from date is required.');

// --- Duration: numbers only, 1-60 ---
clearError('err-duration','grp-duration');
var dur=document.getElementById('duration').value.trim();
if(dur==='') showError('err-duration','grp-duration','Duration is required.');
else if(!/^[0-9]+$/.test(dur)||parseInt(dur)<1||parseInt(dur)>60) showError('err-duration','grp-duration','Duration must be a number between 1 and 60.');

// --- Course ---
clearError('err-course','grp-course');
if(document.getElementById('course').value==='') showError('err-course','grp-course','Course is required.');

// --- Registration Number: alphanumeric only ---
clearError('err-regno','grp-regno');
var rn=document.getElementById('regno').value.trim();
if(rn==='') showError('err-regno','grp-regno','Registration Number is required.');
else if(!/^[A-Za-z0-9\/\-]{2,20}$/.test(rn)) showError('err-regno','grp-regno','Registration Number must be 2-20 alphanumeric characters only (letters and digits).');

// --- First Name: letters only ---
clearError('err-fname','grp-fname');
var fn=document.getElementById('fname').value.trim();
if(fn==='') showError('err-fname','grp-fname','First Name is required.');
else if(!/^[A-Za-z\s]{2,50}$/.test(fn)) showError('err-fname','grp-fname','First Name must contain letters only (min 2 characters).');

// --- Last Name: letters only ---
clearError('err-lname','grp-lname');
var ln=document.getElementById('lname').value.trim();
if(ln==='') showError('err-lname','grp-lname','Last Name is required.');
else if(!/^[A-Za-z\s]{2,50}$/.test(ln)) showError('err-lname','grp-lname','Last Name must contain letters only (min 2 characters).');

// --- Gender ---
clearError('err-gender','grp-gender');
if(document.getElementById('gender').value==='') showError('err-gender','grp-gender','Gender is required.');

// --- Contact: exactly 10 digits, no letters ---
clearError('err-contact','grp-contact');
var co=document.getElementById('contact').value.trim();
if(co==='') showError('err-contact','grp-contact','Contact Number is required.');
else if(!/^[0-9]{10}$/.test(co)) showError('err-contact','grp-contact','Contact Number must be exactly 10 digits (numbers only, no letters).');

// --- Email ---
clearError('err-email','grp-email');
var em=document.getElementById('email').value.trim();
if(em==='') showError('err-email','grp-email','Email is required.');
else if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) showError('err-email','grp-email','Please enter a valid email address (e.g. name@example.com).');

// --- Guardian Name: letters only ---
clearError('err-gname','grp-gname');
var gn=document.getElementById('gname').value.trim();
if(gn==='') showError('err-gname','grp-gname','Guardian Name is required.');
else if(!/^[A-Za-z\s\.]{2,100}$/.test(gn)) showError('err-gname','grp-gname','Guardian Name must contain letters only.');

// --- Address ---
clearError('err-address','grp-address');
if(document.getElementById('address').value.trim()==='') showError('err-address','grp-address','Correspondence Address is required.');

// --- City: letters only ---
clearError('err-city','grp-city');
var cy=document.getElementById('city').value.trim();
if(cy==='') showError('err-city','grp-city','City is required.');
else if(!/^[A-Za-z\s]{2,50}$/.test(cy)) showError('err-city','grp-city','City must contain letters only.');

// --- State ---
clearError('err-state','grp-state');
if(document.getElementById('state').value.trim()==='') showError('err-state','grp-state','State is required.');

if(!valid) e.preventDefault();
});
</script>
</body></html>
