<?php
	include_once("common.php");
	$meta_arr = $generalobj->getsettingSeo(2);
	
	$sql = "SELECT * from language_master where eStatus = 'Active'" ;
	$db_lang = $obj->MySQLSelect($sql);
	$sql = "SELECT * from country where eStatus = 'Active'" ;
	$db_code = $obj->MySQLSelect($sql);
	//echo "<pre>";print_r($db_lang);
	$script="Contact Us";
	
	$add = "";
	
	$vName = base64_decode($_REQUEST['1']);
	$vName = explode(" ",$vName);
	
	$vName0 = $vName['0'];
	if($vName[1] == "")
	$_vLastName = "";	
	else
	$_vLastName = $vName[1];
	
	$vEmail = base64_decode($_REQUEST['2']);
	$vPhone = base64_decode($_REQUEST['3']);
	
	if(isset($_POST['action']) && $_POST['action'] == 'send_mail')
	{
		unset($_POST['action']);
		
		$maildata = array();
		$maildata['EMAIL'] = $_POST['vEmail'];
		$maildata['NAME'] = $_POST['vName']." ".$_POST['vLastName'];
		$maildata['PASSWORD'] = '123456';
		
		//$generalobj->send_email_user("DRIVER_REGISTRATION_ADMIN",$maildata);
		$generalobj->send_email_user("DRIVER_REGISTRATION_USER",$maildata);
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'add_dummy')
	{
		unset($_POST['action']);
		
		$email = $_POST['vEmail'];
		$msg= $generalobj->checkDuplicateFront('vEmail', 'register_driver' , Array('vEmail'),$tconfig["tsite_url"]."dummy_data_insert.php?error=1&var_msg=Email already Exists", "Email already Exists","" ,"");
		
		#echo "<pre>";print_r($_POST); die;
		
		//Insert Driver
		$Data1['vName'] = $_POST['vName'];
		$Data1['vLastName'] = $_POST['vLastName'];
		$Data1['vLang'] = 'EN';
		$Data1['vPassword'] = $generalobj->encrypt('123456');
		$Data1['vEmail'] = $_POST['vEmail'];
		$Data1['dBirthDate'] = '1992-02-02';
		$Data1['vPhone'] = (isset($_POST['vPhone']) && $_POST['vPhone'] != '')?$_POST['vPhone']:'9876543210';
		$Data1['vCaddress'] = "test address";
		$Data1['vCadress2'] = "test address";
		$Data1['vCity'] = "test city";
		$Data1['vZip'] = "121212";
		$Data1['vCountry'] = "US";
		$Data1['vCode'] = "1";
		$Data1['vFathersName'] = 'test';
		$Data1['vCompany'] = 'test';
		$Data1['tRegistrationDate']=Date('Y-m-d H:i:s');
		$Data1['eStatus'] = 'Active';
		$Data1['vCurrencyDriver'] = 'USD';
		$Data1['iCompanyId'] = 1;
		//echo "<pre>";print_r($Data1); echo "</pre>";
		$id = $obj->MySQLQueryPerform('register_driver',$Data1,'insert');
		
		
		//Add Driver Vehicle
		if($id != "") {
			$Drive_vehicle['iDriverId'] = $id;
			$Drive_vehicle['iCompanyId'] = "1";
			$Drive_vehicle['iMakeId'] = "5";
			$Drive_vehicle['iModelId'] = "18";
			$Drive_vehicle['iYear'] = "2014";
			$Drive_vehicle['vLicencePlate'] = "CK201";
			$Drive_vehicle['eStatus'] = "Active";
			$Drive_vehicle['eCarX'] = "Yes";
			$Drive_vehicle['eCarGo'] = "Yes";		
			$Drive_vehicle['vCarType'] = "1,2,3";
			$iDriver_VehicleId=$obj->MySQLQueryPerform('driver_vehicle',$Drive_vehicle,'insert');
			$sql = "UPDATE register_driver set iDriverVehicleId='".$iDriver_VehicleId."' WHERE iDriverId='".$id."'";
			$obj->sql_query($sql);
		}	
		
		//Insert Company
		$Data2['vName'] = $_POST['vName'];
		$Data2['vLastName'] = $_POST['vLastName'];
		$Data2['vLang'] = 'EN';
		$Data2['vPassword'] = $generalobj->encrypt('123456');
		$Data2['vEmail'] = "company_".$_POST['vEmail'];
		$Data2['dBirthDate'] = '1992-02-02';
		$Data2['vPhone'] = (isset($_POST['vPhone']) && $_POST['vPhone'] != '')?$_POST['vPhone']:'9876543210';
		$Data2['vCaddress'] = "test address";
		$Data2['vCadress2'] = "test address";
		$Data2['vCity'] = "test city";
		$Data2['vZip'] = "121212";
		$Data2['vCountry'] = "US";
		$Data2['vCompany'] = $_POST['vName']." ".$_POST['vLastName'];
		$Data2['vCode'] = "1";
		$Data2['vFathersName'] = 'test';
		$Data2['tRegistrationDate']=Date('Y-m-d H:i:s');
		$Data2['eStatus'] = 'Active';
		//echo "<pre>";print_r($Data2); echo "</pre>";
		//$id = $obj->MySQLQueryPerform('company',$Data2,'insert');
		
		//Insert rider
		$eReftype = "Rider";
		$Data['vRefCode'] = '';
		$Data['iRefUserId'] = '';
		$Data['eRefType'] = '';
		$Data['vName'] = $_POST['vName'];
		$Data['vLang'] = 'EN';
		$Data['vLastName'] = $_POST['vLastName'];
		//$Data['vLoginId'] = "";
		$Data['vPassword'] = $generalobj->encrypt('123456');
		$Data['vEmail'] = "rider_".$_POST['vEmail'];
		$Data['vPhone'] = (isset($_POST['vPhone']) && $_POST['vPhone'] != '')?$_POST['vPhone']:'9876543210';
		$Data['vCountry']= "US";
		$Data['vPhoneCode'] = "1";
		//$Data['vExpMonth'] = $_POST['vExpMonth'];
		//$Data['vExpYear'] = $_POST['vExpYear'];
		$Data['vZip'] = '121212';
		//$Data['iDriverVehicleId	'] = "";
		$Data['vInviteCode'] = "";
		$Data['vCreditCard'] = "";
		$Data['vCvv'] = "";
		$Data['vCurrencyPassenger'] = "USD";
		$Data['dRefDate'] =  Date('Y-m-d H:i:s');
		$Data['eStatus'] = 'Active'; 
		
		$id = $obj->MySQLQueryPerform("register_user",$Data,'insert');
		$add = "Yes";
	}
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<!--<title><?=$COMPANY_NAME?> | Contact Us</title>-->
		<title>Dummy</title>
		<!-- Default Top Script and css -->
		<?php include_once("top/top_script.php");?>
		<?php include_once("top/validation.php");?>
		<!-- End: Default Top Script and css-->
	</head>
	<body>
		<!-- home page -->
		<div id="main-uber-page">
			<!-- Top Menu -->
			<!-- End: Top Menu-->
			<!-- contact page-->
			
			<div class="page-contant">
				<div class="page-contant-inner">
					<div class="footer-text-center">			
						<? if($add == "Yes"){?>
							<!-- <h3 style="padding-top:15px;"> Company Details </h3>
							<h5>
								<p>Name: <?php echo $_POST['vName']." ".$_POST['vLastName']; ?></p>
								<p>Email: company_<?php echo $_POST['vEmail']; ?></p>
								<p>Password: 123456 </p>
							</h5> -->
							<h3 style="padding-top:15px;"> Driver Details </h3>
							<h5>
								<p>Name: <?php echo $_POST['vName']." ".$_POST['vLastName']; ?></p>
								<p>Email: <?php echo $_POST['vEmail']; ?></p>
								<p>Password: 123456 </p>
							</h5>
							<h3 style="padding-top:15px;"> Rider Details </h3>
							<h5>
								<p>Name: <?php echo $_POST['vName']." ".$_POST['vLastName']; ?></p>
								<p>Email: rider_<?php echo $_POST['vEmail']; ?></p>
								<p>Password: 123456 </p>
							</h5>
							
							<form method="post" action="">
								<input type="hidden" name="vName" id="vName" value="<?=$_POST['vName'];?>">
								<input type="hidden" name="vLastName" id="vLastName" value="<?=$_POST['vLastName'];?>">
								<input type="hidden" name="vEmail" id="vEmail" value="<?=$_POST['vEmail'];?>">
								<input type="hidden" name="vPhone" id="vPhone" value="<?=$_POST['vPhone'];?>">
								<input type="hidden" name="action" id="action" value="send_mail">
								<div class="contact-form">
									<b>
										<input type="submit" class="submit-but" value="Send Email to Driver" name="send_email" />
									</b>
								</div>
							</form>
						<? } ?>
					</div>
					
					<h2 class="header-page">Add Dummy Data
						<p>It will automatically create dummy record for company , driver, driver vehicle , rider .</p>
					</h2>
					<!-- contact page -->
					<div style="clear:both;"></div>
					<?php
						if ($_REQUEST['error']) {
						?>
						<div class="row" id="showError">
							<div class="col-sm-12 alert alert-danger">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button" onclick="hideError();" >×</button>
								<?=$_REQUEST['var_msg']; ?>
							</div>
						</div>
						<?php 
						}
					?>
                    <div style="clear:both;"></div>
					<form name="frmsignup" id="frmsignup" method="post" action="">
						<input type="hidden" name="action" value="add_dummy" >
						<div class="contact-form">
							<b>
								<strong>
									<em>First Name:</em><br/>
									<input type="text" name="vName" placeholder="<?=$langage_lbl['LBL_CONTECT_US_FIRST_NAME_HEADER_TXT']; ?>" class="contact-input required" value="<?=$vName0?>" />
								</strong>
								<strong>
									<em>Last Name:</em><br/>
									<input type="text" name="vLastName" placeholder="<?=$langage_lbl['LBL_CONTECT_US_LAST_NAME_HEADER_TXT']; ?>" class="contact-input required" value="<?=$_vLastName?>" />
								</strong>
								<strong>
									<em>Email address:</em><br/>
									<input type="text" placeholder="<?=$langage_lbl['LBL_CONTECT_US_EMAIL_LBL_TXT']; ?>" name="vEmail" value="<?=$vEmail?>" autocomplete="off" onChange="return validate_email(this.value)"  class="contact-input required"/>
								</strong>
								<strong>
									<em>Phone Number:</em><br/>
									<input type="text" placeholder="777-777-7777" value="<?=$vPhone?>" name="vPhone" class="contact-input" onChange="return validate_mobile(this.value)"/>
								</strong>
							</b>
							<b>
								<input type="submit" onClick="return submit_form();"  class="submit-but floatLeft" value="ADD" name="SUBMIT" />
							</b> 
						</div>
					</form>
					<div style="clear:both;"></div>
				</div>
			</div>
			<script>
				function submit_form()
				{
					if( validatrix() ){
						//alert("Submit Form");
						document.frmsignup.submit();
					}else{
						console.log("Some fields are required");
						return false;
					}
					return false; //Prevent form submition
				}
			</script>
			<script type="text/javascript">
				function validate_email(id)
				{
					var eml=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
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
						return false;
					}
				}
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
				
				function validate_mobile(mobile)
				{
					
					var eml=/^[0-9]+$/;
					result=eml.test(mobile);
					if(result==true)
					{
						$('#mobileCheck').html('<i class="icon icon-ok alert-success alert"> Valid</i>');
						$('input[type="submit"]').removeAttr('disabled');
					}
					else
					{
						$('#mobileCheck').html('<i class="icon icon-remove alert-danger alert"> Enter Proper Mobile No</i>');
						$('input[type="submit"]').attr('disabled','disabled');
						return false;
					}
				}
				
				function hideError() {
					$('#showError').fadeOut();
				}
				
				
			</script>
			<!-- End: Footer Script -->
		</body>
	</html>
