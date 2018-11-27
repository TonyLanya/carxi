<?php
	include_once('../common.php');
		if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();

	$start = @date("Y");
	$end = '1970';

	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
	$action = ($id != '') ? 'Edit' : 'Add';
	$tbl_name = 'driver_vehicle';
	/*if ($_SESSION['sess_user'] == 'driver') {
		$sql = "select iCompanyId from `register_driver` where iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
		$db_usr = $obj->MySQLSelect($sql);
		$iCompanyId = $db_usr[0]['iCompanyId'];
	}*/
	$script = 'Vehicle';
	/*if ($_SESSION['sess_user'] == 'company') {

		$sql = "select * from register_driver where iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "'";
		$db_drvr = $obj->MySQLSelect($sql);
	}*/
	$sql = "select * from driver_vehicle where iDriverVehicleId = '" . $id . "' ";
	$db_mdl = $obj->MySQLSelect($sql);

	$sql = "select * from driver_vehicle where iDriverVehicleId = '" . $id . "' ";
	$db_driver = $obj->MySQLSelect($sql);

	
	
	// set all variables with either post (when submit) either blank (when insert)
	$vLicencePlate = isset($_POST['vLicencePlate']) ? $_POST['vLicencePlate'] : '';
	$iMakeId = isset($_POST['iMakeId']) ? $_POST['iMakeId'] : '';
	$iModelId = isset($_POST['iModelId']) ? $_POST['iModelId'] : '';
	$iYear = isset($_POST['iYear']) ? $_POST['iYear'] : '';
	$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
	$iDriverId = isset($_POST['iDriverId']) ? $_POST['iDriverId'] :'';
	$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';
	$iCompanyId = isset($_POST['iCompanyId']) ? $_POST['iCompanyId'] : '';
	$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';
	//echo "<pre>";print_R($_REQUEST);exit;
//die;

	$sql = "SELECT * from make WHERE eStatus='Active' ORDER By vMake ASC";
	$db_make = $obj->MySQLSelect($sql);

	$sql = "SELECT * from company WHERE eStatus='Active'";
	$db_company = $obj->MySQLSelect($sql);

	if (isset($_POST['submit'])) {
		if(SITE_TYPE=='Demo')
		{
				$error_msg="Edit / Delete Record Feature has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.";
				header("Location:vehicle_add_form.php?id=" . $id ."&error_msg=".$error_msg."&success=2");
				exit;
		}
		if(!isset($_REQUEST['vCarType'])) {
			$error_msg = "You must select at least one car type!";
			header("Location:vehicle_add_form.php?id=".$id."&error_msg=".$error_msg."&success=1");
			exit;
		}

		if($APP_TYPE == 'UberX'){
		$vLicencePlate	= 'My Services';

		}else{

			$vLicencePlate = $vLicencePlate;
		}


		$q = "INSERT INTO ";
		$where = '';
		//echo "<pre>";print_R($_REQUEST);exit;

		if ($action == 'Edit') {

			$str = ' ';
			} else {
			$eStatus = 'Active';
		}

		$cartype = implode(",", $_REQUEST['vCarType']);
		if ($id != '') {
			$q = "UPDATE ";
			$where = " WHERE `iDriverVehicleId` = '" . $id . "'";
		}


		$query = $q . " `" . $tbl_name . "` SET
		`iModelId` = '" . $iModelId . "',
		`vLicencePlate` = '" . $vLicencePlate . "',
		`iYear` = '" . $iYear . "',
		`iMakeId` = '" . $iMakeId . "',
		`iCompanyId` = '" . $iCompanyId . "',
		`iDriverId` = '" . $iDriverId . "',
		`eStatus` = '" . $eStatus . "',
		`vCarType` = '" . $cartype . "' $str"
		. $where;
		$obj->sql_query($query);
		//echo"<pre>";print_r($query);exit;
		
		if($id != "" && $db_mdl[0]['eStatus'] != $eStatus) {
			//echo $db_mdl[0]['eStatus']; die;
			if($SEND_TAXI_EMAIL_ON_CHANGE == 'Yes') {
				$sql23 = "SELECT m.vMake, md.vTitle,rd.vEmail, rd.vName, rd.vLastName, c.vName as companyFirstName
					FROM driver_vehicle dv, register_driver rd, make m, model md, company c
					WHERE
					  dv.eStatus != 'Deleted'
					  AND dv.iDriverId = rd.iDriverId
					  AND dv.iCompanyId = c.iCompanyId
					  AND dv.iModelId = md.iModelId
					  AND dv.iMakeId = m.iMakeId AND dv.iDriverVehicleId = '".$id."'";
				$data_email_drv = $obj->MySQLSelect($sql23);
				$maildata['EMAIL'] =$data_email_drv[0]['vEmail'];
				$maildata['NAME'] = $data_email_drv[0]['vName'];
				//$maildata['LAST_NAME'] = $data_drv[0]['companyFirstName'];
				$maildata['DETAIL']="Your ".$langage_lbl_admin['LBL_TEXI_ADMIN']." ".$data_email_drv[0]['vTitle']." For COMPANY ".$data_email_drv[0]['companyFirstName'] ." is temporarly ".$eStatus;
				$generalobj->send_email_user("ACCOUNT_STATUS",$maildata);
			}
		}
		
		$id = ($id != '') ? $id : mysql_insert_id();

		if($action=="Add")
		{
			$sql="SELECT * FROM company WHERE iCompanyId = '" . $iCompanyId . "'";
			$db_compny = $obj->MySQLSelect($sql);

			$sql="SELECT * FROM register_driver WHERE iDriverId = '" . $iDriverId . "'";
			$db_status = $obj->MySQLSelect($sql);

			$maildata['EMAIL'] =$db_status[0]['vEmail'];
			$maildata['NAME'] = $db_status[0]['vName']." ".$db_status[0]['vLastName'];
			//$maildata['LAST_NAME'] = $db_compny[0]['vName'];
			//$maildata['DETAIL']="Your Vehicle is Added For ".$db_compny[0]['vCompany']." and will process your document and activate your account ";
      $maildata['DETAIL']="Thanks for adding your ".$langage_lbl_admin['LBL_TEXI_ADMIN'].".<br />We will soon verify and check it's documentation and proceed ahead with activating your account.<br />We will notify you once your account become active and you can then take ".$langage_lbl_admin['LBL_RIDE_TXT_ADMIN']." with ". $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'].".";

			$generalobj->send_email_user("VEHICLE_BOOKING",$maildata);
			//print_R($maildata);
		}
		header("Location:vehicles.php?id=" . $id . '&success=3');
	}

	// for Edit
	if ($action == 'Edit') {
		$sql = "SELECT * from  $tbl_name where iDriverVehicleId = '" . $id . "'";
		$db_data = $obj->MySQLSelect($sql);
		$vLabel = $id;
		if (count($db_data) > 0) {
			foreach ($db_data as $key => $value) {
				$iMakeId = $value['iMakeId'];
				$iModelId = $value['iModelId'];
				$vLicencePlate = $value['vLicencePlate'];
				$iYear = $value['iYear'];
				$eCarX = $value['eCarX'];
				$eCarGo = $value['eCarGo'];
				$iDriverId = $value['iDriverId'];
				$vCarType = $value['vCarType'];
				$iCompanyId=$value['iCompanyId'];
				$eStatus=$value['eStatus'];
			}
		}
	}
	 $vCarTyp = explode(",", $vCarType);
	//print_r($vCarTyp); exit;

	$Vehicle_type_name = ($APP_TYPE == 'Delivery')? 'Deliver':$APP_TYPE ;	
	if($Vehicle_type_name == "Ride-Delivery"){

		$vehicle_type_sql = "SELECT * from  vehicle_type where(eType ='Ride' or eType ='Deliver')";
		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);


	}else{

		if($Vehicle_type_name == 'UberX'){

			$vehicle_type_sql = "SELECT vt.*,vc.iVehicleCategoryId,vc.vCategory_EN from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId where vt.eType='".$Vehicle_type_name."' ";
		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);

		}else{
			$vehicle_type_sql = "SELECT * from  vehicle_type where eType='".$Vehicle_type_name."'";		
		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);

		}
		
	}

	//echo "<pre>"; print_r($vehicle_type_data);  exit;
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title><?=$SITE_NAME?> |  <?php echo $langage_lbl_admin['LBL_VEHICLE_TXT_ADMIN'];?> <?= $action; ?></title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<meta content="" name="keywords" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

		<? include_once('global_files.php');?>
		<!-- On OFF switch -->
		<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
		<link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
		<link rel="stylesheet" href="../assets/validation/validatrix.css" />
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
							<h2><?= $action." ".$langage_lbl_admin['LBL_TEXI_ADMIN'];?></h2>
							<a href="vehicles.php">
								<input type="button" value="<?=$langage_lbl_admin['LBL_BACK_TAXI_LISTING'];?>" class="add-btn">
							</a>
						</div>
					</div>
					<hr />
					<div class="body-div">
						<div class="form-group">
							<? if($success == 1) { ?>
								<div class="alert alert-success alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?> Updated successfully.
								</div><br/>
								<? }else if($success == 2){?>
								<div class="alert alert-danger alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<?= isset($_REQUEST['error_msg']) ? $_REQUEST['error_msg'] : ' '; ?>
								</div><br/>
							<?} ?>
							
							<form method="post" action="">
								<input type="hidden" name="id" value="<?= $id; ?>"/>
									<?php if($APP_TYPE != 'UberX'){ ?> 
								<div class="row">
									<div class="col-lg-12">
										<label>Make<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<select name = "iMakeId" id="iMakeId" class="form-control" onChange="get_model(this.value, '')" required>
											<option value="">CHOOSE MAKE</option>
											<?php for ($j = 0; $j < count($db_make); $j++) { ?>
												<option value="<?= $db_make[$j]['iMakeId'] ?>" <?php if ($iMakeId == $db_make[$j]['iMakeId']) { ?> selected <?php } ?>><?= $db_make[$j]['vMake'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<label>Model<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<div id="carmdl">
											<select name = "iModelId" id="iModelId" class="form-control" required>
												<option value="">CHOOSE  <?php echo $langage_lbl_admin['LBL_VEHICLE_CAPITAL_TXT_ADMIN'];?> MODEL </option>

											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<label>Year<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<select name = "iYear" id="iYear" class="form-control" required>
											<option value="">CHOOSE YEAR </option>
											<?php for ($j = $start; $j >= $end; $j--) { ?>
												<option value="<?= $j ?>" <? if($iYear == $j){?> selected <?} ?>><?= $j ?></option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<label>License Plate<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vLicencePlate"  id="vLicencePlate" value="<?= $vLicencePlate; ?>" onblur="check_licence_plate(this.value,'<?=$id?>')" placeholder="Licence Plate" required>
										<b><span id="plate_warning"></span></b>
									</div>
								</div>
									<?php } ?> 
								<div class="row">
									<div class="col-lg-12">
										<label>Company<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">

											<select name = "iCompanyId" id="iCompanyId" onChange="get_driver(this.value, '')" class="form-control" required>
												<option value="">CHOOSE COMPANY</option>
											<?php for ($j = 0; $j < count($db_company); $j++) { ?>
												<option value="<?= $db_company[$j]['iCompanyId'] ?>" <?php if ($iCompanyId == $db_company[$j]['iCompanyId']) { ?> selected <?php } ?>><?= $db_company[$j]['vCompany'] ?></option>

												<?php } ?>
											</select>

									</div>
								</div>
									<div class="row">
										<div class="col-lg-12">
											<label><?php echo $langage_lbl_admin['LBL_VEHICLE_DRIVER_TXT_ADMIN'];?> <span class="red"> *</span></label>
										</div>
										<div class="col-lg-6">
											<!-- <div id="driver"> -->
											<select name = "iDriverId" id="driver" class="form-control" required>
												<option value=""><?php echo $langage_lbl_admin['LBL_CHOOSE_DRIVER_ADMIN'];?> </option>

											</select>
											<!-- </div> -->
										</div>
									</div>

								<div class="row">
									<div class="col-lg-12">
										<label><?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?> Type <span class="red"> *</span></label>
									</div>
								</div>
								<div class="alert alert-danger alert-dismissable" style="display:none;" id="car_error">
								<button class="close" type="button" id="cartypeClosed">×</button>
								You must select at least one <?=$langage_lbl_admin['LBL_TEXI_ADMIN'];?> type!
							</div><br/>
								<div class="checkbox-group required">
								<?php
									foreach ($vehicle_type_data as $key => $value) { ?>
									<div class="row">
									<?php
										if($Vehicle_type_name =='UberX'){
											$vname = $value['vCategory_EN'].'-'.$value['vVehicleType'];
										}else{
											$vname= $value['vVehicleType'];	
										}
										
										 ?>
										<div class="col-lg-2">										
										<?php echo $vname;?>
										</div>
										<div class="col-lg-2">
											<div class="make-switch" data-on="success" data-off="warning">
												<input type="checkbox" class="chk" name="vCarType[]" <?php if(in_array($value['iVehicleTypeId'],$vCarTyp)){?>checked<?php } ?> value="<?=$value['iVehicleTypeId'] ?>"/>
											</div>
										</div>
									</div>
								<?php }?>
							</div>
							<div class="row">
									 <div class="col-lg-12">
										  <label>Status</label>
									 </div>
									 <div class="col-lg-6">
										  <div class="make-switch" data-on="success" data-off="warning">
											   <input type="checkbox" name="eStatus" id="eStatus" <?= ($id != '' && $eStatus == 'Inactive') ? '' : 'checked'; ?> />
										  </div>
									 </div>
								</div>
								<div class="row">
									<div class="col-lg-12">
                                        <input type="submit" class="save btn-info" onClick="return chk1();" name="submit" id="submit" value="<?= $action." ".$langage_lbl_admin['LBL_TEXI_ADMIN'];?>" >
									</div>
								</div>
							</form>
						</div>
					</div>
                    <div style="clear:both;"></div>
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
<?php if ($action == 'Edit') { ?>
	<script>
		window.onload = function () {
			get_model('<?php echo $db_mdl[0]['iMakeId']; ?>', '<?php echo $db_mdl[0]['iModelId']; ?>');
			//get_driver('<?php echo $db_driver[0]['iCompanyId']; ?>', '<?php echo $db_driver[0]['iDriverId']; ?>');
			get_driver('<?php echo $iCompanyId; ?>', '<?php echo $iDriverId; ?>');
		};
	</script>
<?} ?>
<script>

function chk1(){
	var a=$('div.checkbox-group.required :checkbox:checked').length;
	if(a>0){
		return true;
	}
	else{
		$("#car_error").show();
		return false;
	}
}


$('#cartypeClosed' ).click(function() {

$("#car_error").hide();

});

	function get_model(model, modelid) {

		$("#carmdl").html('Wait...');
		var request = $.ajax({
			type: "POST",
			url: '../ajax_find_model.php',
			data: "action=get_model&model=" + model + "&iModelId=" + modelid,
			success: function (data) {
				$("#carmdl").html(data);
			}
		});
		request.fail(function (jqXHR, textStatus) {
			alert("Request failed: " + textStatus);
		});
	}
	function get_driver(company, companyid) {
		//alert(company);
		$("#driver").html('Wait...');
		var request = $.ajax({
			type: "POST",
			url: '../ajax_find_driver.php',
			data: "action=get_driver&company=" + company + "&iDriverId=" + companyid,
			success: function (data) {
				$("#driver").html(data);
			}
		});

		request.fail(function (jqXHR, textStatus) {
			alert("Request failed: " + textStatus);
		});
	}
	
	function check_licence_plate(plate,id1=''){
		var request= $.ajax({
			type: "POST",
			url: '../ajax_find_plate.php',
			data: "plate="+plate+"&id="+id1,
			success: function (data){			
				if($.trim(data) == 'yes') {
					$('input[type="submit"]').removeAttr('disabled');
					$("#plate_warning").html("");
				}else {
					$("#plate_warning").html(data);
					$('input[type="submit"]').attr('disabled','disabled');
				}
			}
			});
		}
</script>
