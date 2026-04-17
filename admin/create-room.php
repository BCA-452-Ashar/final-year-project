<?php session_start();
error_reporting(0);
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit']))
{
$seater=intval($_POST['seater']);
$roomno=trim($_POST['rmno']);
$fees=trim($_POST['fee']);

// Server-side validation
$errors = [];
if($seater < 1 || $seater > 5) $errors[] = "Please select a valid seater option.";
if(empty($roomno)) $errors[] = "Room Number is required.";
elseif(!preg_match('/^\d{1,10}$/', $roomno)) $errors[] = "Room Number must be a positive number.";
if(empty($fees)) $errors[] = "Fee is required.";
elseif(!is_numeric($fees) || floatval($fees) <= 0) $errors[] = "Fee must be a positive number.";

if(empty($errors)){
    $sql="SELECT room_no FROM rooms where room_no=?";
    $stmt1 = $mysqli->prepare($sql);
    $stmt1->bind_param('i',$roomno);
    $stmt1->execute();
    $stmt1->store_result();
    $row_cnt=$stmt1->num_rows;
    if($row_cnt>0){
        $errors[] = "Room number already exists. Please use a different room number.";
    } else {
        $query="insert into  rooms (seater,room_no,fees) values(?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc=$stmt->bind_param('iii',$seater,$roomno,$fees);
        $stmt->execute();
        echo"<script>alert('Room has been added successfully');</script>";
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
	<title>Create Room</title>
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
					
						<h2 class="page-title">Add a Room </h2>
	
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Add a Room</div>
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

										<form method="post" class="form-horizontal" id="createRoomForm" novalidate>
											
											<div class="hr-dashed"></div>
											<div class="form-group" id="seater-group">
												<label class="col-sm-2 control-label">Select Seater <span style="color:red">*</span></label>
												<div class="col-sm-8">
												<select name="seater" id="seater" class="form-control">
<option value="">Select Seater</option>
<option value="1" <?php echo (isset($seater) && $seater==1)?'selected':''; ?>>Single Seater</option>
<option value="2" <?php echo (isset($seater) && $seater==2)?'selected':''; ?>>Two Seater</option>
<option value="3" <?php echo (isset($seater) && $seater==3)?'selected':''; ?>>Three Seater</option>
<option value="4" <?php echo (isset($seater) && $seater==4)?'selected':''; ?>>Four Seater</option>
<option value="5" <?php echo (isset($seater) && $seater==5)?'selected':''; ?>>Five Seater</option>
</select>
<span class="error-msg" id="seater-err"></span>
</div>
</div>
<div class="form-group" id="rmno-group">
<label class="col-sm-2 control-label">Room No. <span style="color:red">*</span></label>
<div class="col-sm-8">
<input type="text" class="form-control" name="rmno" id="rmno" value="<?php echo isset($roomno) ? htmlspecialchars($roomno) : ''; ?>" placeholder="e.g. 101" maxlength="10">
<span class="error-msg" id="rmno-err"></span>
</div>
</div>
<div class="form-group" id="fee-group">
<label class="col-sm-2 control-label">Fee (Per Student) <span style="color:red">*</span></label>
<div class="col-sm-8">
<input type="text" class="form-control" name="fee" id="fee" value="<?php echo isset($fees) ? htmlspecialchars($fees) : ''; ?>" placeholder="e.g. 5000" maxlength="10">
<span class="error-msg" id="fee-err"></span>
</div>
</div>

<div class="col-sm-8 col-sm-offset-2">
<input class="btn btn-primary" type="submit" name="submit" value="Create Room">
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
function validateRoomForm() {
    var valid = true;
    var seater = document.getElementById('seater').value;
    var rmno = document.getElementById('rmno').value.trim();
    var fee = document.getElementById('fee').value.trim();

    ['seater','rmno','fee'].forEach(function(id) {
        document.getElementById(id+'-err').textContent = '';
        document.getElementById(id+'-group').classList.remove('has-error');
    });

    if(seater === '' || seater === '0') {
        document.getElementById('seater-err').textContent = 'Please select a seater option.';
        document.getElementById('seater-group').classList.add('has-error');
        valid = false;
    }

    if(rmno === '') {
        document.getElementById('rmno-err').textContent = 'Room Number is required.';
        document.getElementById('rmno-group').classList.add('has-error');
        valid = false;
    } else if(!/^\d{1,10}$/.test(rmno)) {
        document.getElementById('rmno-err').textContent = 'Room Number must be a positive integer.';
        document.getElementById('rmno-group').classList.add('has-error');
        valid = false;
    }

    if(fee === '') {
        document.getElementById('fee-err').textContent = 'Fee is required.';
        document.getElementById('fee-group').classList.add('has-error');
        valid = false;
    } else if(isNaN(fee) || parseFloat(fee) <= 0) {
        document.getElementById('fee-err').textContent = 'Fee must be a positive number.';
        document.getElementById('fee-group').classList.add('has-error');
        valid = false;
    }

    return valid;
}

document.getElementById('createRoomForm').addEventListener('submit', function(e) {
    if(!validateRoomForm()) e.preventDefault();
});
</script>
</body>

</html>
