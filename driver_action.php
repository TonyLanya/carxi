<?php
	include_once('common.php');
	
	require_once(TPATH_CLASS . "/Imagecrop.class.php");
	$thumb = new thumbnail();
	$generalobj->check_member_login();
	$sql = "select * from country";
	$db_country = $obj->MySQLSelect($sql);
	
	if($_REQUEST['id'] != '' && $_SESSION['sess_iCompanyId'] != ''){
		
		$sql = "select * from register_driver where iDriverId = '".$_REQUEST['id']."' AND iCompanyId = '".$_SESSION['sess_iCompanyId']."'";
		$db_cmp_id = $obj->MySQLSelect($sql);
		
		if(!count($db_cmp_id) > 0) 
		{
			header("Location:driver.php?success=0&var_msg=".$langage_lbl['LBL_NOT_YOUR_DRIVER']);
		}
	}
	
	$var_msg = isset($_REQUEST["var_msg"]) ? $_REQUEST["var_msg"] : '';
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
	$action = ($id != '') ? 'Edit' : 'Add';
	$iCompanyId = $_SESSION['sess_iUserId'];
	$tbl_name = 'register_driver';
	$script = 'Driver';
	
	$sql = "select * from language_master where eStatus = 'Active' ORDER BY vTitle ASC";
	$db_lang = $obj->MySQLSelect($sql);
	
	$sql = "select * from company where eStatus != 'Deleted'";
	$db_company = $obj->MySQLSelect($sql);
	
	//echo '<prE>'; print_R($_REQUEST); echo '</pre>';
	// set all variables with either post (when submit) either blank (when insert)
	$vName = isset($_POST['vName']) ? $_POST['vName'] : '';
	
	$vLastName = isset($_POST['vLastName']) ? $_POST['vLastName'] : '';
	$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
	$vUserName = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
	$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
	$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
	$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
	$vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
	$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : '';
	$vLang = isset($_POST['vLang']) ? $_POST['vLang'] : '';
	$vImage = isset($_POST['vImage']) ? $_POST['vImage'] : '';
	$vPass = $generalobj->encrypt($vPassword);
	if (isset($_POST['submit'])) {
		// if(SITE_TYPE=='Demo' && $action=='Edit')
		// {
			// header("Location:driver_action.php?id=" . $id . '&success=2');
			// exit;
		// }
		$iCompanyId = $_SESSION['sess_iUserId'];
		
		
		//Start :: Upload Image Script
		if(!empty($id)){
			
			if(isset($_FILES['vImage'])){
				$id = $_REQUEST['id'];
				$img_path = $tconfig["tsite_upload_images_driver_path"];
				$temp_gallery = $img_path . '/';
				$image_object = $_FILES['vImage']['tmp_name'];
				$image_name = $_FILES['vImage']['name'];
				$check_file_query = "select iDriverId,vImage from register_driver where iDriverId=" . $id;
				$check_file = $obj->sql_query($check_file_query);
				if ($image_name != "") {
					$check_file['vImage'] = $img_path . '/' . $id . '/' . $check_file[0]['vImage'];
					
					if ($check_file['vImage'] != '' && file_exists($check_file['vImage'])) {
						unlink($img_path . '/' . $id. '/' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/1_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/2_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/3_' . $check_file[0]['vImage']);
					}
					
					$filecheck = basename($_FILES['vImage']['name']);
					$fileextarr = explode(".", $filecheck);
					$ext = strtolower($fileextarr[count($fileextarr) - 1]);
					$flag_error = 0;
					if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp") {
						$flag_error = 1;
						$var_msg = "Not valid image extension of .jpg, .jpeg, .gif, .png";
					}
					/*if ($_FILES['vImage']['size'] > 1048576) {
						$flag_error = 1;
						$var_msg = "Image Size is too Large";
					}*/
					if ($flag_error == 1) {
						$generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
						exit;
						} else {
						
						$Photo_Gallery_folder = $img_path . '/' . $id . '/';
						
						if (!is_dir($Photo_Gallery_folder)) {
							mkdir($Photo_Gallery_folder, 0777);
						}
						$img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"], '', '', '', 'Y', '', $Photo_Gallery_folder);
						$vImage = $img;
					}
					}else{
                    $vImage = $check_file[0]['vImage'];
				}
				//die();
			}
		}
		//End :: Upload Image Script
		
		$q = "INSERT INTO ";
		$where = '';
		if ($action == 'Edit') {
			$str = ", eStatus = 'Inactive' ";
		} else {
			
			if(SITE_TYPE=='Demo')
			{	
				$str = ", eStatus = 'active' ";
			}
			else
			{
				$sqlc = "select vValue from configurations where vName = 'DEFAULT_CURRENCY_CODE'";
				$db_currency = $obj->MySQLSelect($sqlc);				
				$defaultCurrency = $db_currency[0]['vValue'];
	
				$str = ", vCurrencyDriver = '$defaultCurrency'";
			}
		}
		if ($id != '') {
			$q = "UPDATE ";
			$where = " WHERE `iDriverId` = '" . $id . "'";
			
			$sql="select * from ".$tbl_name .$where;
			$edit_data=$obj->sql_query($sql);
			
			if($vEmail != $edit_data[0]['vEmail'])
			{
				$query = $q ." `".$tbl_name."` SET `eEmailVerified` = 'No' ".$where;
				$obj->sql_query($query);
			}
			#echo"<pre>";print_r($query);
			if($vPhone != $edit_data[0]['vPhone'])
			{
				$query = $q ." `".$tbl_name."` SET `ePhoneVerified` = 'No' ".$where;
				$obj->sql_query($query);
			}
			#echo"<pre>";print_r($query);
			if($vCode != $edit_data[0]['vCode'])
			{
				$query = $q ." `".$tbl_name."` SET `ePhoneVerified` = 'No' ".$where;
				$obj->sql_query($query);		
			}		
		}
		
		 $query = $q . " `" . $tbl_name . "` SET
		`vName` = '" . $vName . "',
		`vLastName` = '" . $vLastName . "',
		`vCountry` = '" . $vCountry . "',
		`vCode` = '" . $vCode . "',
		`vEmail` = '" . $vEmail . "',
		`vLoginId` = '" . $vEmail . "',
		`vPassword` = '" . $vPass . "',
		`iCompanyId` = '" . $iCompanyId . "',
		`vPhone` = '" . $vPhone . "',
		`vImage` = '" . $vImage . "',
		`vLang` = '" . $vLang . "' $str" . $where; 
		
		$obj->sql_query($query);
		
		if (mysql_insert_id() != '') {
			if(isset($_FILES['vImage'])){
                $id = mysql_insert_id();
                $img_path = $tconfig["tsite_upload_images_driver_path"];
                $temp_gallery = $img_path . '/';
                $image_object = $_FILES['vImage']['tmp_name'];
                $image_name = $_FILES['vImage']['name'];
                $check_file_query = "select iDriverId,vImage from register_driver where iDriverId=" . $id;
                $check_file = $obj->sql_query($check_file_query);
                if ($image_name != "") {
					$check_file['vImage'] = $img_path . '/' . $id . '/' . $check_file[0]['vImage'];
					
					if ($check_file['vImage'] != '' && file_exists($check_file['vImage'])) {
						unlink($img_path . '/' . $id. '/' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/1_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/2_' . $check_file[0]['vImage']);
						unlink($img_path . '/' . $id. '/3_' . $check_file[0]['vImage']);
					}
					
					$filecheck = basename($_FILES['vImage']['name']);
					$fileextarr = explode(".", $filecheck);
					$ext = strtolower($fileextarr[count($fileextarr) - 1]);
					$flag_error = 0;
					if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp") {
						$flag_error = 1;
						$var_msg = "Not valid image extension of .jpg, .jpeg, .gif, .png";
					}
					/*if ($_FILES['vImage']['size'] > 1048576) {
						$flag_error = 1;
						$var_msg = "Image Size is too Large";
					}*/
					if ($flag_error == 1) {
						$generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
						exit;
						} else {
						
						$Photo_Gallery_folder = $img_path . '/' . $id . '/';
						if (!is_dir($Photo_Gallery_folder)) {
							mkdir($Photo_Gallery_folder, 0777);
						}
						$img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"], '', '', '', 'Y', '', $Photo_Gallery_folder);
						$vImage = $img;
						
						$sql = "UPDATE ".$tbl_name." SET `vImage` = '" . $vImage . "' WHERE `iDriverId` = '" . $id . "'";
						$obj->sql_query($sql);
					}
				}
			}
		}
		$id = ($id != '') ? $id : mysql_insert_id();
		if($action== 'Edit')
		{
			$var_msg="Record Updated successfully";
		}
		else
		{
			$var_msg="Record inserted successfully";
		}
		
		$maildata['NAME'] =$vName;
		$maildata['EMAIL'] =  $vEmail;
		$maildata['PASSWORD'] = $vPassword;
		//$generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);
		if($_REQUEST['id'] == '')
		{
			$generalobj->send_email_user("DRIVER_REGISTRATION_ADMIN",$maildata);
			$generalobj->send_email_user("DRIVER_REGISTRATION_USER",$maildata);
		}
		
		/* $sql = "select * from company where iCompanyId="$sess_iCompanyId;
			$db_company = $obj->MySQLSelect($sql);
			$companydata['NAME'] =db_company[0]['vName'];
			$companydata['EMAIL'] = db_company[0]['vEmail'];
			$companydata['PASSWORD'] = " Added New Driver named".$vName;
		$generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);*/
		header("Location:driver.php?id=" . $id . '&success=1&var_msg='.$var_msg);
					exit;
	}
	// for Edit
	
	if ($action == 'Edit') {
		$sql = "SELECT * FROM " . $tbl_name . " WHERE iDriverId = '" . $id . "'";
		$db_data = $obj->MySQLSelect($sql);
		//echo "<pre>";print_R($db_data);echo "</pre>";
		$vPass = $generalobj->decrypt($db_data[0]['vPassword']);
		$vLabel = $id;
		if (count($db_data) > 0) {
			foreach ($db_data as $key => $value) {
				$vName = $value['vName'];
				$iCompanyId = $value['iCompanyId'];
				$vLastName = $value['vLastName'];
				$vCountry = $value['vCountry'];
				$vCode = $value['vCode'];
				$vEmail = $value['vEmail'];
				$vUserName = $value['vLoginId'];
				$vPassword = $value['vPassword'];
				$vPhone = $value['vPhone'];
				$vLang = $value['vLang'];
				$vImage = $value['vImage'];
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title><?=$SITE_NAME?> | Driver <?= $action; ?></title>
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
			<!-- End: Top Menu-->
			<!-- contact page-->
			<div class="page-contant ">
				<div class="page-contant-inner page-trip-detail">
					<h2 class="header-page trip-detail driver-detail1"><?= $action; ?> Driver <?= $vName; ?>
					<a href="driverlist">
						<img src="assets/img/arrow-white.png" alt=""> Back to Listing
					</a></h2>
					<!-- login in page -->
					<div class="driver-action-page">
						<? if ($success == 1) {?>
							<div class="alert alert-success alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								Record Updated successfully.
							</div>
							<?}else if($success == 2){ ?>
							<div class="alert alert-danger alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								"Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
							</div>
							<?php 
							}
						?>
						<form id="frm1" method="post" onSubmit="return editPro('login')" enctype="multipart/form-data">
							<input  type="hidden" class="edit" name="action" value="login">
							<div class="driver-action-user-image">
								<?php if($id){?>
									<?php if ($vImage == 'NONE' || $vImage == '') { ?>
										<img src="assets/img/profile-user-img.png" alt="">
										<?}else{?>
										<img src = "<?php echo $tconfig["tsite_upload_images_driver"]. '/' .$id. '/3_' .$vImage ?>" style="height:150px;"/>
									<?}?>
								<? }?>
							</div>
							<div class="driver-action-page-right validation-form">
								<div class="row">
									<div class="col-md-6">
										<span>
											<label>First Name</label>
											<input type="text" class="driver-action-page-input" name="vName"  id="vName" value="<?= $vName; ?>" placeholder="First Name" pattern="[a-zA-Z\s]+" title="Only Alphabat character allow" required>
										</span> 
									</div>
									<div class="col-md-6">
										<span>
											<label>Last Name</label>	
											<input type="text" class="driver-action-page-input" name="vLastName"  id="vLastName" value="<?= $vLastName; ?>" placeholder="Last Name" pattern="[a-zA-Z\s]+" title="Only Alphabat character allow" required>
										</span> 
									</div>
									<div class="col-md-6">
										<span>
											<label>Email Id</label>
											<input type="email" class="driver-action-page-input " name="vEmail" onChange="validate_email(this.value)"  id="vEmail" value="<?= $vEmail; ?>" placeholder="Email" required <?php  if(!empty($_REQUEST['id'])){?> readonly <?php } ?>>
											<div style="float: none;margin-top: 14px;" id="emailCheck"></div>
										</span> 
									</div>
									<div class="col-md-6">
										<span>
											<label>Select Image</label>
											<input type="file" class="driver-action-page-input" name="vImage"  id="vImage" placeholder="Name Label">
										</span> 
									</div>
									<div class="col-md-6"> 
										<span>
											<label>Select Country</label>
											<select class="custom-select-new" name = 'vCountry' onChange="changeCode(this.value);" required>
												<option value="">--select Country--</option>
												<? for($i=0;$i<count($db_country);$i++){ ?>
													<option value = "<?= $db_country[$i]['vCountryCode'] ?>" <?if($vCountry==$db_country[$i]['vCountryCode']){?>selected<? } ?>><?= $db_country[$i]['vCountry'] ?></option>
												<? } ?>
											</select>
										</span>
									</div>
									<div class="col-md-6">   
										<span class="driver-phone-number">
											<label>Phone Number</label>
											<input type="text" pattern=".{10}" class="input-phNumber1" id="code" name="vCode" value="<?= $vCode ?>" readonly >
											<input name="vPhone" type="text" value="<?= $vPhone; ?>" class="driver-action-page-input input-phNumber2" placeholder="PHONE NUMBER" pattern="[0-9]{1,}" title="Please enter proper mobile number." required/>
										</span>
									</div>
									<div class="col-md-6">
										<span>       
											<label>Select language</label>                         
											<select  class="custom-select-new" name = 'vLang' required>
												<option value="">--select Language--</option>
												<? for ($i = 0; $i < count($db_lang); $i++) { ?>
													<option value = "<?= $db_lang[$i]['vCode'] ?>" <?= ($db_lang[$i]['vCode'] == $vLang) ? 'selected' : ''; ?>><?= $db_lang[$i]['vTitle'] ?></option>
												<? } ?>
											</select>
										</span>
									</div>
									<div class="col-md-6">
										<span>
											<label>Password</label>
											<input type="password" class="driver-action-page-input" name="vPassword"  id="vPassword" value="<?= $vPass ?>" placeholder="<?=$langage_lbl['LBL_COMPANY_DRIVER_PASSWORD']; ?>" pattern=".{6,}" title="Six or more characters" required>
										</span> 
									</div>
									<p>
										<input type="submit" class="save-but" name="submit" id="submit" value="<?= $action; ?> Driver">
										
									</p>
									<div style="clear:both;"></div>
								</div>  
							</div>                      
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
		<script>
			function changeCode(id)
			{
				var request = $.ajax({
					type: "POST",
					url: 'change_code.php',
					data: 'id=' + id,
					success: function (data)
					{
						document.getElementById("code").value = data;
						//window.location = 'profile.php';
					}
				});
			}
			function validate_email(id)
			{
				
				var request = $.ajax({
					type: "POST",
					url: 'ajax_validate_email.php',
					data: 'id=' +id,
					success: function (data)
					{
						if(data==0)
						{
							$('#emailCheck').html('<i class="icon icon-remove alert-danger alert">Already Exist,Select Another</i>');
							$('input[type="submit"]').attr('disabled','disabled');
						}
						else if(data==1)
						{
							var eml=/^[-.0-9a-zA-Z]+@[a-zA-z]+\.[a-zA-z]{2,3}$/;
							result=eml.test(id);
							if(result==true)
							{
								$('#emailCheck').html('<i class="icon icon-ok alert-success alert"> Valid</i>');
								$('input[type="submit"]').removeAttr('disabled');
							}
							else
							{
								$('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> Enter Proper Email</i>');
								$('input[type="submit"]').attr('disabled','disabled');
							}
						}
					}
				});
			}
		</script>
		<!-- End: Footer Script -->
	</body>
</html>

