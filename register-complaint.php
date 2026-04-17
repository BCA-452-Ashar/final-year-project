<?php
session_start();
include('includes/config.php');
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$aid=$_SESSION['id'];
$errors=[];

if(isset($_POST['submit']))
{
$complainttype=trim($_POST['ctype']);
$complaintdetails=trim($_POST['cdetails']);
$imgfile=$_FILES["image"]["name"];
$cnumber=mt_rand(100000000,999999999);

if(empty($complainttype)) $errors[]="Complaint type is required.";
if(empty($complaintdetails)) $errors[]="Complaint details are required.";
elseif(strlen($complaintdetails)<10) $errors[]="Complaint details must be at least 10 characters.";
elseif(strlen($complaintdetails)>1000) $errors[]="Complaint details must not exceed 1000 characters.";

if($imgfile!=''){
$extension=strtolower(substr($imgfile,strrpos($imgfile,'.')));
$allowed_extensions=array(".jpg",".jpeg",".png",".gif",".pdf");
if(!in_array($extension,$allowed_extensions)) $errors[]="Invalid file format. Only JPG, JPEG, PNG, GIF, PDF allowed.";
$maxSize=5*1024*1024;
if($_FILES["image"]["size"]>$maxSize) $errors[]="File size must not exceed 5MB.";
}

if(empty($errors)){
if($imgfile!=''){
$extension=strtolower(substr($imgfile,strrpos($imgfile,'.')));
$imgnewfile=md5($imgfile.time()).$extension;
move_uploaded_file($_FILES["image"]["tmp_name"],"comnplaintdoc/".$imgnewfile);
}else{$imgnewfile='';}
$query="insert into complaints(ComplainNumber,userId,complaintType,complaintDetails,complaintDoc) values(?,?,?,?,?)";
$stmt=$mysqli->prepare($query);
$rc=$stmt->bind_param('iisss',$cnumber,$aid,$complainttype,$complaintdetails,$imgnewfile);
$stmt->execute();
echo "<script>alert('Complaint registered. Your Complaint Number is: $cnumber');</script>";
echo "<script type='text/javascript'> document.location = 'my-complaints.php'; </script>";
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
	<title>Register Complaint</title>
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
#char-count{font-size:12px;color:#888;margin-top:4px;display:block;}
</style>
</head>
<body>
<?php include('includes/header.php');?>
<div class="ts-main-content">
<?php include('includes/sidebar.php');?>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row"><div class="col-md-10 col-md-offset-1">
<h2 class="page-title">Register Complaint</h2>
<div class="panel panel-default">
<div class="panel-heading">Submit a Complaint</div>
<div class="panel-body">

<?php if(!empty($errors)): ?>
<div class="validation-errors"><strong>Please fix the following errors:</strong>
<ul style="margin:5px 0 0 0;padding-left:18px;"><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" action="" name="complaint" class="form-horizontal" id="complaintForm" enctype="multipart/form-data" novalidate>
<div class="hr-dashed"></div>

<div class="form-group" id="grp-ctype">
<label class="col-sm-3 control-label">Complaint Type <span style="color:red">*</span></label>
<div class="col-sm-7">
<select class="form-control" name="ctype" id="ctype">
<option value="">-- Select Complaint Type --</option>
<option value="Food" <?php echo (isset($complainttype)&&$complainttype=='Food')?'selected':'';?>>Food</option>
<option value="Room" <?php echo (isset($complainttype)&&$complainttype=='Room')?'selected':'';?>>Room</option>
<option value="Maintenance" <?php echo (isset($complainttype)&&$complainttype=='Maintenance')?'selected':'';?>>Maintenance</option>
<option value="Staff" <?php echo (isset($complainttype)&&$complainttype=='Staff')?'selected':'';?>>Staff</option>
<option value="Other" <?php echo (isset($complainttype)&&$complainttype=='Other')?'selected':'';?>>Other</option>
</select>
<span class="error-msg" id="err-ctype"></span></div></div>

<div class="form-group" id="grp-cdetails">
<label class="col-sm-3 control-label">Complaint Details <span style="color:red">*</span></label>
<div class="col-sm-7">
<textarea name="cdetails" id="cdetails" class="form-control" rows="5" placeholder="Describe your complaint (min 10 characters)" maxlength="1000" onKeyUp="updateCharCount()"><?php echo isset($complaintdetails)?htmlspecialchars($complaintdetails):'';?></textarea>
<span id="char-count">0 / 1000 characters</span>
<span class="error-msg" id="err-cdetails"></span></div></div>

<div class="form-group" id="grp-image">
<label class="col-sm-3 control-label">Attachment <small>(optional)</small></label>
<div class="col-sm-7">
<input type="file" name="image" id="image" class="form-control">
<span style="font-size:12px;color:#888;">Allowed: JPG, JPEG, PNG, GIF, PDF. Max size: 5MB</span>
<span class="error-msg" id="err-image"></span></div></div>

<div class="col-sm-7 col-sm-offset-3">
<input type="submit" name="submit" value="Submit Complaint" class="btn btn-primary">
</div>
</form>
</div></div></div></div></div></div></div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
function updateCharCount(){
var len=document.getElementById('cdetails').value.length;
document.getElementById('char-count').textContent=len+' / 1000 characters';
}
document.getElementById('cdetails').addEventListener('input',updateCharCount);
updateCharCount();

document.getElementById('complaintForm').addEventListener('submit',function(e){
var valid=true;
var ct=document.getElementById('ctype').value;
var cd=document.getElementById('cdetails').value.trim();
var img=document.getElementById('image');
['grp-ctype','grp-cdetails','grp-image'].forEach(function(g){document.getElementById(g).classList.remove('has-error');});
['err-ctype','err-cdetails','err-image'].forEach(function(g){document.getElementById(g).textContent='';});
if(ct===''){document.getElementById('err-ctype').textContent='Complaint type is required.';document.getElementById('grp-ctype').classList.add('has-error');valid=false;}
if(cd===''){document.getElementById('err-cdetails').textContent='Complaint details are required.';document.getElementById('grp-cdetails').classList.add('has-error');valid=false;}
else if(cd.length<10){document.getElementById('err-cdetails').textContent='Complaint details must be at least 10 characters.';document.getElementById('grp-cdetails').classList.add('has-error');valid=false;}
if(img.files.length>0){
var file=img.files[0];
var allowed=['image/jpeg','image/jpg','image/png','image/gif','application/pdf'];
if(!allowed.includes(file.type)){document.getElementById('err-image').textContent='Invalid file format. Only JPG, PNG, GIF, PDF allowed.';document.getElementById('grp-image').classList.add('has-error');valid=false;}
else if(file.size>5*1024*1024){document.getElementById('err-image').textContent='File size must not exceed 5MB.';document.getElementById('grp-image').classList.add('has-error');valid=false;}
}
if(!valid)e.preventDefault();
});
</script>
</body></html>
