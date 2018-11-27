<?php
	include_once('common.php');
	$generalobj->check_member_login();
	$abc = 'admin,driver,company';
	$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$generalobj->setRole($abc, $url);
	$start = @date("Y");
	$end = '1970';

	$script="Vehicle";
	$_REQUEST['id'] = base64_decode(base64_decode(trim($_REQUEST['id'] )));
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	
	$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
	$action = ($id != '') ? 'Edit' : 'Add';
	$tbl_name = 'driver_vehicle';
	if ($_SESSION['sess_user'] == 'driver') {
		$sql = "select iCompanyId from `register_driver` where iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
		$db_usr = $obj->MySQLSelect($sql);
		$iCompanyId = $db_usr[0]['iCompanyId'];
	}
	if ($_SESSION['sess_user'] == 'company') {
		$iCompanyId = $_SESSION['sess_iCompanyId'];
		$sql = "select * from register_driver where iCompanyId = '" . $_SESSION['sess_iCompanyId'] . "'";
		$db_drvr = $obj->MySQLSelect($sql);
	}
	$sql = "select * from driver_vehicle where iDriverVehicleId = '" . $id . "' ";
	$db_mdl = $obj->MySQLSelect($sql);


	// set all variables with either post (when submit) either blank (when insert)
	$vLicencePlate = isset($_POST['vLicencePlate']) ? $_POST['vLicencePlate'] : '';
	$iMakeId = isset($_POST['iMakeId']) ? $_POST['iMakeId'] : '';
	$iModelId = isset($_POST['iModelId']) ? $_POST['iModelId'] : '';
	$iYear = isset($_POST['iYear']) ? $_POST['iYear'] : '';
	$eStatus_check = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'off';
	$iDriverId = isset($_POST['iDriverId']) ? $_POST['iDriverId'] : $_SESSION['sess_iUserId'];
	$vCarType = isset($_POST['vCarType']) ? $_POST['vCarType'] : '';
	$eStatus = ($eStatus_check == 'on') ? 'Active' : 'Inactive';


	$sql = "SELECT * from make WHERE eStatus='Active' ORDER BY vMake ASC";
	$db_make = $obj->MySQLSelect($sql);

	if (isset($_POST['submit'])) {
	//echo "<pre>";print_r($_POST);
	// if(SITE_TYPE=='Demo' && $action=='Edit')
	// {
		// $error_msg="Edit / Delete Record Feature has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.";
		 // header("Location:vehicle_add_form.php?id=" .$id."&error_msg=".$error_msg."&success=2");
		 // exit;
	// }
	//echo "sasa".$id;exit;
		if(!isset($_REQUEST['vCarType'])) {
			$error_msg = "You must select at least one car type!";
			header("Location:vehicle_add_form.php?id=".$id."&error_msg=".$error_msg."&success=2");
			exit;
		}

		if($APP_TYPE == 'UberX'){

			$vLicencePlate ='My Services';
		}else{
			$vLicencePlate = $vLicencePlate;
		}
		
		$dsql="";
		if($id!='')
		{
			$dsql=" and iDriverVehicleId != '$id'";
		}
		$sql="select * from driver_vehicle where vLicencePlate='".$vLicencePlate."' and eStatus!='Deleted' ".$dsql;
		$db_li_plate=$obj->MySQLSelect($sql);
		//echo "<pre>";print_r($db_li_plate);exit;
		if(count($db_li_plate)>0){
			$error_msg= "Sorry,Licence Plate Already Exist.";
			header("Location:vehicle_add_form.php?id=".$id."&error_msg=".$error_msg."&success=2");
			exit;
		}
		else
		{
		$q = "INSERT INTO ";
		$where = '';
		//echo "<pre>";print_R($_REQUEST);exit;

		if ($action == 'Edit') {
			$str = ' ';
			} else {
				if(SITE_TYPE=='Demo')
				$str = ", eStatus = 'Active' ";
				else
				$str = ", eStatus = 'Inactive' ";
					
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
		`vCarType` = '" . $cartype . "' $str"
		. $where;
		

		$obj->sql_query($query);
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
      $maildata['DETAIL']="Thanks for adding your vehicle.<br />We will soon verify and check it's documentation and proceed ahead with activating your account.<br />We will notify you once your account become active and you can then take rides with passengers.";
			
			$generalobj->send_email_user("VEHICLE_BOOKING",$maildata);
			$maildata['DETAIL']="Vehicle is Added For ".$db_compny[0]['vCompany']." . Below is link to activate.<br>
			<p><a href='".$tconfig["tsite_url"]."admin/vehicle_add_form.php?id=$id'>Active this Vehicle</a></p>";
			$generalobj->send_email_user("VEHICLE_BOOKING_ADMIN",$maildata);
			//print_R($maildata);
		}
		$var_msg="Record insert successfully.";
		header("Location:vehicle.php?success=1&var_msg=".$var_msg);
	}
	}

	// for Edit
	if ($action == 'Edit') {
		if($_SESSION['sess_user'] == 'driver'){
			$ssql = "and iDriverId = '".$_SESSION['sess_iUserId']."'";
		}else{
			$ssql = "and iCompanyId = '".$_SESSION['sess_iCompanyId']."'";
		}
		
		$sql = "SELECT * from  $tbl_name where iDriverVehicleId = '" . $id . "' ".$ssql." ";
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
			}
		}else{
			header("location:vehicle.php");
		}
	}
	$vCarTyp = explode(",", $vCarType);

	$Vehicle_type_name = ($APP_TYPE == 'Delivery')? 'Deliver':$APP_TYPE ;	
	if($Vehicle_type_name == "Ride-Delivery"){

		$vehicle_type_sql = "SELECT * from  vehicle_type where(eType ='Ride' or eType ='Deliver')";
		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);


	}else{

		if($APP_TYPE == 'UberX'){

			$vehicle_type_sql = "SELECT vt.*,vc.* from  vehicle_type as vt  left join vehicle_category as vc on vt.iVehicleCategoryId = vc.iVehicleCategoryId where vt.eType='".$Vehicle_type_name."' ";
		$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);

		}else{

			$vehicle_type_sql = "SELECT * from  vehicle_type  where eType='".$Vehicle_type_name."' ";
			$vehicle_type_data = $obj->MySQLSelect($vehicle_type_sql);
		}	

		
	}	

	//echo "</pre>"; print_r($vehicle_type_data); exit;



	
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_Vehicle']; ?> <?= $action; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <!-- End: Default Top Script and css-->
</head>
<body>
        <!-- home page -->
    <div id="main-uber-page">
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <link rel="stylesheet" href="assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
        <!-- End: Top Menu-->
        <!-- contact page-->
        <div class="page-contant">
		    <div class="page-contant-inner page-trip-detail">
		      	<h2 class="header-page trip-detail driver-detail1"><?= $action; ?> <?=$langage_lbl['LBL_Vehicle']; ?><a href="vehicle.php"><img src="assets/img/arrow-white.png" alt="" /><?=$langage_lbl['LBL_BACK_MY_TAXI_LISTING']; ?></a></h2>
		      	<!-- trips detail page -->
		      	<div class="driver-add-vehicle"> 
		      	<? if($success == 1) { ?>
					<div class="alert alert-success alert-dismissable">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?=$langage_lbl['LBL_Record_Updated_successfully.']; ?>
					</div>
					<? }else if($success == 2){?>
					<div class="alert alert-danger alert-dismissable">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?= isset($_REQUEST['error_msg']) ? $_REQUEST['error_msg'] : ' '; ?>
					</div>
				<?} ?>
					<form method="post" action="">
						<!--<input type="hidden" name="id" value="<?= $id; ?>"/>-->
						<input type="hidden" name="id" value="<?=base64_encode(base64_encode($id)); ?>"/>
						<?php if($APP_TYPE != 'UberX'){ ?>

		      			<span> 
		  					<b>
                            <label><?=$langage_lbl['LBL_CHOOSE_MAKE']; ?></label>
						        <select name = "iMakeId" id="iMakeId" class="custom-select-new" data-key="<?=$langage_lbl['LBL_CHOOSE_MAKE']; ?>" onChange="get_model(this.value, '')" required>
									<option value=""><?=$langage_lbl['LBL_CHOOSE_MAKE']; ?></option>
									<?php for ($j = 0; $j < count($db_make); $j++) { ?>
										<option value="<?= $db_make[$j]['iMakeId'] ?>" <?php if ($iMakeId == $db_make[$j]['iMakeId']) { ?> selected <?php } ?>><?= $db_make[$j]['vMake'] ?></option>
									<?php } ?>
								</select>
		        			</b> 
		        			<b id="carmdl">
                             <label><?=$langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?> </label>
		        				<select name = "iModelId" id="iModelId" data-key="<?=$langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?>" class="custom-select-new validate[required]" required>
									<option value=""><?=$langage_lbl['LBL_CHOOSE_VEHICLE_MODEL']; ?> </option>
									<?php for ($j = 0; $j < count($db_model); $j++) { ?>
										<option value="<?= $db_model[$j]['iModelId'] ?>" <?php if ($iModelId == $db_model[$j]['iModelId']) { ?> selected <?php } ?>><?= $db_model[$j]['vModel'] ?></option>
									<?php } ?>
								</select>
		    				</b> 
						</span> 
						<span> 
							<b>
                             <label><?=$langage_lbl['LBL_CHOOSE_YEAR']; ?></label>
		        				<select name = "iYear" data-key="<?=$langage_lbl['LBL_CHOOSE_YEAR']; ?>" id="iYear" class="custom-select-new" required>
									<option value=""><?=$langage_lbl['LBL_CHOOSE_YEAR']; ?> </option>
									<?php for ($j = $start; $j >= $end; $j--) { ?>
										<option value="<?= $j ?>" <? if($iYear == $j){?> selected <?} ?>><?= $j ?></option>
									<?php } ?>
								</select>
		        			</b> 
		        			<b>
                             <label><?=$langage_lbl['LBL_LICENCE_PLATE_TXT'];?></label>
		        				<input type="text" class="form-control" name="vLicencePlate"  id="vLicencePlate" value="<?= $vLicencePlate; ?>" placeholder="<?=$langage_lbl['LBL_LICENCE_PLATE_TXT']; ?>" onblur="check_licence_plate(this.value,'<?=$id?>')" required>
								<span id="plate_warning"></span>
		        			</b> 
		    			</span>
		    			<?if($_SESSION['sess_user'] == 'company') {?>
						<span>
							<b>
                            <label><?=$langage_lbl['LBL_CHOOSE_DRIVER']; ?></label>
								<select name = "iDriverId" id="iDriverId" class="custom-select-new" required>
									<option value=""><?=$langage_lbl['LBL_CHOOSE_DRIVER']; ?></option>
									<?php for ($j = 0; $j < count($db_drvr); $j++) { ?>
										<option value="<?= $db_drvr[$j]['iDriverId'] ?>" <? if($db_drvr[$j]['iDriverId'] == $iDriverId){?> selected <?} ?>><?= $db_drvr[$j]['vName'] ?></option>
									<?php } ?>
								</select>
							</b>
						</span>

						<? } ?>
		    			<h3><?=$langage_lbl['LBL_Car_Type']; ?></h3>
		    			<?php } ?>
		    			<div class="car-type">
				          
				          	<ul>
		      				<?php
								foreach ($vehicle_type_data as $key => $value) {

									if($APP_TYPE == 'UberX'){

										$vName = 'vCategory_'.$_SESSION['sess_lang'];
										$vehicle_typeName =$value[$vName].'-'.$value['vVehicleType'];	

									}else{

										$vehicle_typeName = $value['vVehicleType'];
									}?>
								<li>
									<b><?=$vehicle_typeName; ?></b>
									<div class="make-switch" data-on="success" data-off="warning">
										<input type="checkbox" class="chk" name="vCarType[]" <?php if(in_array($value['iVehicleTypeId'],$vCarTyp)){?>checked<?php } ?> value="<?=$value['iVehicleTypeId'] ?>"/>
									</div>
								</li>
						<?php }?>
							</ul>
		      				<strong><input type="submit" class="save-vehicle" name="submit" id="submit" value="<?= $action; ?> <?=$langage_lbl['LBL_Vehicle']; ?>"> </strong>
		  				</div>
					<!-- -->
					</form>
				</div>
                <div style="clear:both;"></div>
			</div>
		</div>
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
    	    <!-- End:contact page-->
             <div style="clear:both;"></div>
	</div>
    <!-- home page end-->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    <script src="assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
    <?php if ($action == 'Edit') { ?>
	<script>
		window.onload = function () {
			get_model('<?php echo $db_mdl[0]['iMakeId']; ?>', '<?php echo $db_mdl[0]['iModelId']; ?>');
		};
	</script>
<?} ?>
<script>
	function get_model(model, modelid) {
		//alert('dfdf');
		//$("#carmdl").html('Wait...');
		var request = $.ajax({
			type: "POST",
			url: 'ajax_find_model_new.php',
			data: "action=get_model&model=" + model + "&iModelId=" + modelid,
			success: function (data) {
				$("#iModelId").empty().append(data);
				var selectedOption = $('#iModelId').find(":selected").text();
				$('#iModelId').next(".holder").text(selectedOption);
			}
		});

		request.fail(function (jqXHR, textStatus) {
			alert("Request failed: " + textStatus);
		});
	}
	
	function check_licence_plate(plate,id1=''){
		var request= $.ajax({
			type: "POST",
			url: 'ajax_find_plate.php',
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

    <!-- End: Footer Script -->
</body>
</html>
