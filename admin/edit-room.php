<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit']))
{
$seater=intval($_POST['seater']);
$fees=trim($_POST['fees']);
$id=intval($_GET['id']);

// Server-side validation
$errors = [];
if($seater < 1 || $seater > 10) $errors[] = "Seater must be a number between 1 and 10.";
if(empty($fees)) $errors[] = "Fee is required.";
elseif(!is_numeric($fees) || floatval($fees) <= 0) $errors[] = "Fee must be a positive number.";

if(empty($errors)){
    $query="update rooms set seater=?,fees=? where id=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('iii',$seater,$fees,$id);
    $stmt->execute();
    echo"<script>alert('Room Details has been Updated successfully');</script>";
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
	<title>Edit Room Details</title>
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
					
						<h2 class="page-title">Edit Room Details </h2>
	
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit Room Details</div>
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

										<form method="post" class="form-horizontal" id="editRoomForm" novalidate>
												<?php	
												$id=$_GET['id'];
	$ret="select * from rooms where id=?";
		$stmt= $mysqli->prepare($ret) ;
	 $stmt->bind_param('i',$id);
	 $stmt->execute();
	 $res=$stmt->get_result();
	   while($row=$res->fetch_object())
	  {
	  	$val_seater = isset($seater) ? $seater : $row->seater;
	  	$val_fees = isset($fees) ? $fees : $row->fees;
	  	?>
			<div class="hr-dashed"></div>
			<div class="form-group" id="seater-group">
			<label class="col-sm-2 control-label">Seater <span style="color:red">*</span></label>
		<div class="col-sm-8">
		<input type="number" name="seater" id="seater" value="<?php echo htmlspecialchars($val_seater); ?>" class="form-control" min="1" max="10" placeholder="e.g. 2">
		<span class="error-msg" id="seater-err"></span>
		</div>
		</div>
		 <div class="form-group">
		<label class="col-sm-2 control-label">Room No</label>
		<div class="col-sm-8">
	<input type="text" class="form-control" name="rmno" id="rmno" value="<?php echo htmlspecialchars($row->room_no); ?>" disabled>
	<span class="help-block m-b-none">Room no can't be changed.</span>
			 </div>
			</div>
<div class="form-group" id="fees-group">
				<label class="col-sm-2 control-label">Fees (PM) <span style="color:red">*</span></label>
				<div class="col-sm-8">
				<input type="text" class="form-control" name="fees" id="fees" value="<?php echo htmlspecialchars($val_fees); ?>" placeholder="e.g. 5000">
				<span class="error-msg" id="fees-err"></span>
				</div>
				</div>

<?php } ?>
								<div class="col-sm-8 col-sm-offset-2">
									<input class="btn btn-primary" type="submit" name="submit" value="Update Room Details">
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
function validateEditRoomForm() {
    var valid = true;
    var seater = document.getElementById('seater').value.trim();
    var fees = document.getElementById('fees').value.trim();

    ['seater','fees'].forEach(function(id) {
        document.getElementById(id+'-err').textContent = '';
        document.getElementById(id+'-group').classList.remove('has-error');
    });

    if(seater === '') {
        document.getElementById('seater-err').textContent = 'Seater is required.';
        document.getElementById('seater-group').classList.add('has-error');
        valid = false;
    } else if(isNaN(seater) || parseInt(seater) < 1 || parseInt(seater) > 10) {
        document.getElementById('seater-err').textContent = 'Seater must be a number between 1 and 10.';
        document.getElementById('seater-group').classList.add('has-error');
        valid = false;
    }

    if(fees === '') {
        document.getElementById('fees-err').textContent = 'Fee is required.';
        document.getElementById('fees-group').classList.add('has-error');
        valid = false;
    } else if(isNaN(fees) || parseFloat(fees) <= 0) {
        document.getElementById('fees-err').textContent = 'Fee must be a positive number.';
        document.getElementById('fees-group').classList.add('has-error');
        valid = false;
    }

    return valid;
}
document.getElementById('editRoomForm').addEventListener('submit', function(e) {
    if(!validateEditRoomForm()) e.preventDefault();
});
</script>
</body>

</html>
