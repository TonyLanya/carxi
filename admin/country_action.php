<?
	include_once('../common.php');

	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();

	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
	$action 	= ($id != '')?'Edit':'Add';

	$tbl_name 	= 'country';
	$script 	= 'Settings';

	//echo '<prE>'; print_R($_REQUEST); echo '</pre>';

	// set all variables with either post (when submit) either blank (when insert)
	$vCountry = isset($_POST['vCountry'])?$_POST['vCountry']:'';
	$vCountryCode = isset($_POST['vCountryCode'])?$_POST['vCountryCode']:'';
	$vCountryCodeISO_3 = isset($_POST['vCountryCodeISO_3'])?$_POST['vCountryCodeISO_3']:'';
	$vPhoneCode = isset($_POST['vPhoneCode'])?$_POST['vPhoneCode']:'';
	$eStatus_check = isset($_POST['eStatus'])?$_POST['eStatus']:'off';
	$eStatus = ($eStatus_check == 'on')?'Active':'Inactive';

	if(isset($_POST['submit'])) {


				if(SITE_TYPE=='Demo')
				{
						header("Location:country_action.php?id=".$id.'&success=2');
						exit;
				}

		$q = "INSERT INTO ";
		$where = '';

		if($id != '' ){
			$q = "UPDATE ";
			$where = " WHERE `iCountryId` = '".$id."'";
		}


		$query = $q ." `".$tbl_name."` SET
		`vCountry` = '".$vCountry."',
		`vCountryCode` = '".$vCountryCode."',
		`vCountryCodeISO_3` = '".$vCountryCodeISO_3."',
		`vPhoneCode` = '".$vPhoneCode."',
		`eStatus` = '".$eStatus."'"
		.$where;

		$obj->sql_query($query);
		$id = ($id != '')?$id:mysql_insert_id();
		header("Location:country_action.php?id=".$id.'&success=1');

	}

	// for Edit
	if($action == 'Edit') {
		$sql = "SELECT * FROM ".$tbl_name." WHERE iCountryId = '".$id."'";
		$db_data = $obj->MySQLSelect($sql);

		$vLabel = $id;
		if(count($db_data) > 0) {
			foreach($db_data as $key => $value) {
				$vCountry	 = $value['vCountry'];
				$vCountryCode	 = $value['vCountryCode'];
				$vCountryCodeISO_3	 = $value['vCountryCodeISO_3'];
				$vPhoneCode	 = $value['vPhoneCode'];
				$eStatus = $value['eStatus'];
			}
		}
	}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title>Admin | Country <?=$action;?></title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />

		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

		<? include_once('global_files.php');?>
		<!-- On OFF switch -->
		<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
		<link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
	</head>
	<!-- END  HEAD-->
	<!-- BEGIN BODY-->
	<body class="padTop53 " >

		<!-- MAIN WRAPPER -->
		<div id="wrap">
			<? include_once('header.php'); ?>
			<? include_once('left_menu.php'); ?>
			<!--PAGE CONTENT -->
			<div id="content">
				<div class="inner">
					<div class="row">
						<div class="col-lg-12">
							<h2><?=$action;?> Country</h2>
							<a href="country.php">
								<input type="button" value="Back to Listing" class="add-btn">
							</a>
						</div>
					</div>
					<hr />
					<div class="body-div">
						<div class="form-group">
							<? if($success == 1) { ?>
								<div class="alert alert-success alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									Record Updated successfully.
								</div><br/>
								<? }elseif ($success == 2) { ?>
									<div class="alert alert-danger alert-dismissable">
											 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											 "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
									</div><br/>
								<? }?>
							<form method="post" action="">
								<input type="hidden" name="id" value="<?=$id;?>"/>
								<div class="row">
									<div class="col-lg-12">
										<label>Country Name<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vCountry"  id="vCountry" value="<?=$vCountry;?>" placeholder="Country Name" required>
									</div>
								</div>

								
								<div class="row">
									<div class="col-lg-12">
										<label>Country Code<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vCountryCode"  id="vCountryCode" value="<?=$vCountryCode;?>" placeholder="Country Code" required>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<label>Country Code ISO_3<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vCountryCodeISO_3"  id="vCountryCodeISO_3" value="<?=$vCountryCodeISO_3;?>" placeholder="Country Code ISO_3" required>
									</div>
								</div>
								
								<div class="row">
									<div class="col-lg-12">
										<label>Country Phone Code<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vPhoneCode"  id="vPhoneCode" value="<?=$vPhoneCode;?>" placeholder="Country Phone Code" required>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<label>Status</label>
									</div>
									<div class="col-lg-6">
										<div class="make-switch" data-on="success" data-off="warning">
											<input type="checkbox" name="eStatus" <?=($id != '' && $eStatus == 'Inactive')?'':'checked';?>/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<input type="submit" class="save btn-info" name="submit" id="submit" value="<?=$action;?> Country">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!--END PAGE CONTENT -->
		</div>
		<!--END MAIN WRAPPER -->


		<? include_once('footer.php');?>
		<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
	</body>
	<!-- END BODY-->
</html>
