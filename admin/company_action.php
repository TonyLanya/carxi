<?
	include_once('../common.php');

	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();

	$sql = "select * from country";
	$db_country = $obj->MySQLSelect($sql);
	
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
	$action 	= ($id != '')?'Edit':'Add';

	$tbl_name 	= 'company';
	$script		= "Company";

	$sql = "select * from language_master where eStatus = 'Active'";
	$db_lang = $obj->MySQLSelect($sql);

	//echo '<prE>'; print_R($_REQUEST); echo '</pre>';

	// set all variables with either post (when submit) either blank (when insert)
	$vName = isset($_POST['vName'])?$_POST['vName']:'';
	$vLastName = isset($_POST['vLastName'])?$_POST['vLastName']:'';
	$vEmail = isset($_POST['vEmail'])?$_POST['vEmail']:'';
	$vCompany = isset($_POST['vCompany'])?$_POST['vCompany']:'';
	$vPassword = isset($_POST['vPassword'])?$_POST['vPassword']:'';
	$vPhone = isset($_POST['vPhone'])?$_POST['vPhone']:'';
	$vCaddress = isset($_POST['vCaddress'])?$_POST['vCaddress']:'';
	$vCadress2 = isset($_POST['vCadress2'])?$_POST['vCadress2']:'';
	$vCity = isset($_POST['vCity'])?$_POST['vCity']:'';
	$vInviteCode = isset($_POST['vInviteCode'])?$_POST['vInviteCode']:'';
	$vPass =$generalobj->encrypt($vPassword);
	$vVatNum=isset($_POST['vVatNum'])?$_POST['vVatNum']:'';
	$vCountry=isset($_POST['vCountry'])?$_POST['vCountry']:'';
	if(isset($_POST['submit'])) {

		if(SITE_TYPE=='Demo' and $action=='Edit')
		{
				header("Location:company_action.php?id=".$id.'&success=2');
			exit;
		}

		$q = "INSERT INTO ";
		$where = '';

		if($id != '' ){
			$q = "UPDATE ";
			$where = " WHERE `iCompanyId` = '".$id."'";
		}


		$query = $q ." `".$tbl_name."` SET
			`vName` = '".$vName."',
			`vLastName` = '".$vLastName."',
			`vEmail` = '".$vEmail."',
			`vCaddress` = '".$vCaddress."',
			`vCadress2` = '".$vCadress2."',
			`vPassword` = '".$vPass."',
			`vPhone` = '".$vPhone."',
			`vCity` = '".$vCity."',
			`vCompany` = '".$vCompany."',
			`vInviteCode` = '".$vInviteCode."',
			`vVat` = '".$vVatNum."',
			`vCountry` = '".$vCountry."'"
			.$where;
			//echo"<pre>";print_r($query);exit;

		//echo $query;
		$obj->sql_query($query);
		$id = ($id != '') ? $id : mysql_insert_id();
		//echo"<pre>";print_r($action);exit;
		if ($action == 'Add') {
         $maildata['EMAIL'] = $vEmail;
        $maildata['NAME'] = $vName;
        $maildata['PASSWORD'] = $vPassword;
	   // $generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);
	     $generalobj->send_email_user("COMPANY_REGISTRATION_USER",$maildata);
	    //header("Location:company_action.php?id=".$id.'&success=1');
     }
		header("Location:company_action.php?id=".$id.'&success=1');

	}
	// for Edit
	if($action == 'Edit') {
		$sql = "SELECT * FROM ".$tbl_name." WHERE iCompanyId = '".$id."'";
		$db_data = $obj->MySQLSelect($sql);
		//echo "<pre>";print_R($db_data);echo "</pre>";
		$vPass = $generalobj->decrypt($db_data[0]['vPassword']);
		$vLabel = $id;
		if(count($db_data) > 0) {
			foreach($db_data as $key => $value)
			{
				$vName	 = $value['vName'];
				$vLastName = $value['vLastName'];
				$vEmail = $generalobjAdmin->clearEmail($value['vEmail']);
				$vCompany = $value['vCompany'];
				$vCaddress = $value['vCaddress'];
				$vCadress2 = $value['vCadress2'];
				$vPassword = $value['vPassword'];
				$vPhone = $value['vPhone'];
				$vCity = $value['vCity'];
				$vInviteCode = $value['vInviteCode'];
				$vVatNum=$value['vVat'];
				$vCountry=$value['vCountry'];
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
		<title>Admin | Company <?=$action;?></title>
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
							<h2><?=$action;?> Company <?=$vCompany;?></h2>
							<a href="company.php">
								<input type="button" value="Back to Listing" class="add-btn">
							</a>
						</div>
					</div>
					<hr />
					<div class="body-div">
						<div class="form-group">
							<? if($success == 1) { ?>
								<div class="alert alert-success alert-dismissable msgs_hide">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<?php
                                             //echo $action;exit;
                                             if($action=="Add")
                                             {?>
                                                  Company Inserted successfully.
                                             <?php }
                                             else
                                             {?>
                                                  Company Updated successfully.
                                             <?php }
                                        ?>
								</div><br/>
							<? } ?>
							<? if($success == 2) { ?>
								<div class="alert alert-danger alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									"Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
								</div><br/>
							<? } ?>
							<form method="post" action="">
								<input type="hidden" name="id" value="<?=$id;?>"/>
								<div class="row">
									<div class="col-lg-12">
										<label>Company Name<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text"  class="form-control" name="vCompany"  id="vCompany" value="<?=$vCompany;?>" placeholder="Company Name" required>
									</div>
								</div>
							<!--	<div class="row">
									<div class="col-lg-12">
										<label>First Name<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vName"  id="vName" value="<?=$vName;?>" placeholder="First Name" required>
									</div>
									</div>
								<div class="row">
									<div class="col-lg-12">
										<label>Last Name<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vLastName"  id="vLastName" value="<?=$vLastName;?>" placeholder="Last Name" required>
									</div>
								</div>-->

								<div class="row">
									<div class="col-lg-12">
										<label>Email<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" name="vEmail"  id="vEmail" value="<?=$vEmail;?>" placeholder="Email" required onChange="validate_email(this.value,'<?php echo $id; ?>')"/>
									</div><div id="emailCheck"></div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<label>Password<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="password" pattern=".{6,}" title="Six or more characters" class="form-control" name="vPassword"  id="vPassword" value="<?=$vPass?>" placeholder="Password" required>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<label>Phone<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text"  pattern="[0-9]{1,}" class="form-control" name="vPhone"  id="vPhone" value="<?=$vPhone;?>" placeholder="Phone" title="Please enter proper mobile number." required>
									</div>
								</div>
								   <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Country <span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select class="form-control" name = 'vCountry' onChange="changeCode(this.value);" required>
                                                       <option value="">--select--</option>
                                                       <? for($i=0;$i<count($db_country);$i++){ ?>
                                                       <option value = "<?= $db_country[$i]['vCountryCode'] ?>" <?if($vCountry==$db_country[$i]['vCountryCode']){?>selected<? } ?>><?= $db_country[$i]['vCountry'] ?></option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>



								<div class="row">
									<div class="col-lg-12">
										<label>Address Line 1<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vCaddress"  id="vCaddress" value="<?=$vCaddress;?>" placeholder="Address Line 1" required>
									</div>
								</div>


								<div class="row">
									<div class="col-lg-12">
										<label>Address Line 2</label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vCadress2"  id="vCadress2" value="<?=$vCadress2;?>" placeholder="Address Line 2" >
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<label>City<span class="red"> *</span></label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vCity"  id="vCity" value="<?=$vCity;?>" placeholder="City" required>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<label>Vat Number</label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vVatNum"  id="vVatNum" value="<?=$vVatNum;?>" placeholder="VAT Number">
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-lg-12">
										<label>Invite Code</label>
									</div>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="vInviteCode"  id="vInviteCode" value="<?=$vInviteCode;?>" placeholder="Invite Code">
									</div>
								</div> -->




								<!--<div class="row">
									<div class="col-lg-12">
									<label>Status</label>
									</div>
									<div class="col-lg-6">
									<div class="make-switch" data-on="success" data-off="warning">
									<input type="checkbox" name="eStatus" <?=($id != '' && $eStatus == 'Inactive')?'':'checked';?>/>
									</div>
									</div>
								</div>-->
								<div class="row admin-button">
									<div class="col-lg-12">
										<input type="submit" class="btn btn-info" name="submit" id="submit" value="<?=$action;?> Company"  >
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
		 <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
          <script>
          var successMSG1 = '<?php echo $success;?>';

                    if(successMSG1 != ''){                       
                         setTimeout(function() {
                            $(".msgs_hide").hide(1000)
                        }, 5000);
                    }
           function validate_email(id,iCompanyId)
           {
                var request = $.ajax({
                     type: "POST",
                     url: 'validate_email.php',
                     data: 'id=' +id+'&iCompanyId='+iCompanyId,
                     success: function (data)
                     {
						if(data==0)
						{
                          $('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> Invalid Email,Already Exist</i>');
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
						if(data==2)
						{
                          $('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> This Account is deleted</i>');
						 $('input[type="submit"]').attr('disabled','disabled');
						}
                     }
                });
           }
          </script>
	</body>
	<!-- END BODY-->
</html>
