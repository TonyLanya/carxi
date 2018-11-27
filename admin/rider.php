<?php
	include_once('../common.php');
	
	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();
	
	$id 	= isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$action = isset($_REQUEST['action'])?$_REQUEST['action']:'view';
	$iUserId = isset($_REQUEST['iUserId'])?$_REQUEST['iUserId']:'';
	$res_id = isset($_REQUEST['res_id'])?$_REQUEST['res_id']:'';
	$status 	= isset($_REQUEST['status'])?$_REQUEST['status']:'';
	$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
	$script 	= "Rider";
	
	if($iUserId != '' && $status != ''){
		if(SITE_TYPE !='Demo'){
			$query = "UPDATE register_user SET eStatus = '".$status."' WHERE iUserId = '".$iUserId."'";
			$obj->sql_query($query);
			$success = "1";
			$succe_msg =$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']." ".$status." successfully.";
			header("Location:rider.php?action=view&success=1&succe_msg=".$succe_msg);
		}
		else{
			header("Location:rider.php?success=2");exit;
		}
	}
	
	$sql = "select * from country";
	$db_country = $obj->MySQLSelect($sql);
	
	$sql = "select * from language_master where eStatus = 'Active'";
	$db_lang = $obj->MySQLSelect($sql);
	$hdn_del_id = isset($_REQUEST['hdn_del_id'])?$_REQUEST['hdn_del_id']:'';
	if($action == 'delete' && $hdn_del_id != '')
	{
		//$query = "DELETE FROM `".$tbl_name."` WHERE iUserId = '".$id."'";
		if(SITE_TYPE !='Demo'){
			$query = "UPDATE register_user SET eStatus = 'Deleted' WHERE iUserId = '".$hdn_del_id."'";
			$obj->sql_query($query);
			$action = "view";
			$success = "1";
			$succe_msg = $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']." deleted successfully.";
			header("Location:rider.php?action=view&success=1&succe_msg=".$succe_msg);
			exit;
		}
		else{
			header("Location:rider.php?success=2");exit;
		}
	}
	
	if($action == 'reset' && $res_id != '')
	{
		if(SITE_TYPE !='Demo'){
			$query = "UPDATE register_user SET iTripId='0',vTripStatus='NONE',vCallFromDriver=' ' WHERE iUserId = '".$res_id."'";
			$obj->sql_query($query);
			$action = "view";
			$success = "1";
			$succe_msg =$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN']." status reseted successfully.";
			header("Location:rider.php?action=view&success=1&succe_msg=".$succe_msg);
			exit;
		}
		else{
			header("Location:rider.php?success=2");exit;
		}
	}
	/* $vName = isset($_POST['vName'])?$_POST['vName']:'';
		$vLname = isset($_POST['vLname'])?$_POST['vLname']:'';
		$vEmail = isset($_POST['vEmail'])?$_POST['vEmail']:'';
		$vPassword = isset($_POST['vPassword'])?$_POST['vPassword']:'';
		$vPhone = isset($_POST['vPhone'])?$_POST['vPhone']:'';
		$vCode = isset($_POST['vCode'])?$_POST['vCode']:'';
		$vCountry = isset($_POST['vCountry'])?$_POST['vCountry']:'';
		$vLang = isset($_POST['vLang'])?$_POST['vLang']:'';
		$vPass = $generalobj->encrypt($vPassword);
		$tbl_name = "register_user";
		
		if(isset($_POST['submit'])) {
		
		$q = "INSERT INTO ";
		$where = '';
		
		if($id != '' ){
		$q = "UPDATE ";
		$where = " WHERE `iUserId` = '".$id."'";
		}
		
		
		$query = $q ." `".$tbl_name."` SET
		`vName` = '".$vName."',
		`vLastName` = '".$vLname."',
		`vCountry` = '".$vCountry."',
		`vCode` = '".$vCode."',
		`vEmail` = '".$vEmail."',
		`vLoginId` = '".$vEmail."',
		`vPassword` = '".$vPass."',
		`vPhone` = '".$vPhone."',
		`vLang` = '".$vLang."',
		`iCompanyId` = '".$iCompanyId."'"
		.$where;
		
		$obj->sql_query($query);
		$id = ($id != '')?$id:mysql_insert_id();
		header("Location:rider.php?id=".$id.'&success=1');
		
	} */
	
	$cmp_ssql = "";
	if(SITE_TYPE =='Demo'){
		$cmp_ssql = " And tRegistrationDate > '".WEEK_DATE."'";
	}
	
	if($action == 'view')
	{
		$sql = "SELECT * FROM register_user WHERE 1=1".$cmp_ssql;
		$data_drv = $obj->MySQLSelect($sql);
	}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD-->
<head>
<meta charset="UTF-8" />
		<title>Admin | <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?> </title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
		
		<? include_once('global_files.php');?>
		<!-- <script>
			$(document).ready(function(){
			$("#show-add-form").click(function(){
			$("#show-add-form").hide(1000);
			$("#add-hide-div").show(1000);
			$("#cancel-add-form").show(1000);
			});
			
			});
			</script>
			<script>
			$(document).ready(function(){
			$("#cancel-add-form").click(function(){
			$("#cancel-add-form").hide(1000);
			$("#show-add-form").show(1000);
			$("#add-hide-div").hide(1000);
			});
			
			});
			
		</script>	-->
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
					<div id="add-hide-show-div">
						<div class="row">
							<div class="col-lg-12">
								<h2><?php echo $langage_lbl_admin['LBL_RIDERS_TXT_ADMIN'];?> </h2>
								<a href="rider_action.php"><input type="button" id="show-add-form" value="ADD A <?=$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>" class="add-btn"></a>
								<input type="button" id="cancel-add-form" value="CANCEL" class="cancel-btn">
							</div>
						</div>
						<hr />
					</div>
					<? if($success == 1) { ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
							<?php echo isset($_REQUEST['succe_msg'])? $_REQUEST['succe_msg'] : ''; ?>
						</div><br/>
						<? }elseif ($success == 2) { ?>
						<div class="alert alert-danger alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
							"Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
						</div><br/>
					<? } ?>
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading driver-neww1">
										<b><?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?></b>
										<div class="button-group driver-neww">
											<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="">Select Option</span> <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="#" class="small" data-value="Active" tabIndex="-1"><input type="checkbox" id="checkbox" checked="checked" />&nbsp;Active</a></li>
												<li><a href="#" class="small" data-value="Inactive" tabIndex="-1"><input type="checkbox" id="checkbox"  checked="checked"/>&nbsp;Inactive</a></li>
												<li><a href="#" class="small" data-value="Deleted" tabIndex="-1"><input type="checkbox" id="checkbox"  checked="checked"/>&nbsp;Delete</a></li> 
											</ul>
										</div>
									</div>
									<div class="panel-body">
										<div class="table-responsive"  id="data_drv001">
											<table class="table table-striped table-bordered table-hover admin-td-button" id="dataTables-example">
												<thead>
													<tr>
														<th>NAME</th>
														<th>EMAIL</th>
														<th>SIGN UP DATE</th>
														<th>MOBILE</th>
														<th>STATUS</th>
														<th align="center" style="text-align:center;">ACTION</th>
													</tr>
												</thead>
												<tbody>
													<?php for($i=0;$i<count($data_drv);$i++) {?>
														<tr class="gradeA">
															<td><? echo $data_drv[$i]['vName'].' '.$data_drv[$i]['vLastName']; ?></td>
															<td><? echo $generalobjAdmin->clearEmail($data_drv[$i]['vEmail']); ?></td>
															<td data-order="<?=$data_drv[$i]['iUserId']; ?>"><? echo $data_drv[$i]['tRegistrationDate']; ?></td>
															<td class="center"><?= $generalobjAdmin->clearPhone($data_drv[$i]['vPhone']);?></td>
															<td width="10%" align="center">
																<? if($data_drv[$i]['eStatus'] == 'Active') {
																	$dis_img = "img/active-icon.png";
																	}else if($data_drv[$i]['eStatus'] == 'Inactive'){
																	$dis_img = "img/inactive-icon.png";
																	}else if($data_drv[$i]['eStatus'] == 'Deleted'){
																	$dis_img = "img/delete-icon.png";
																}?>
																<img src="<?=$dis_img;?>" alt="<?=$data_drv[$i]['eStatus']?>">   
															</td>
															<td  class="veh_act"  align="center" style="text-align:center;">
																<?php if($data_drv[$i]['eStatus']!="Deleted"){?>
																	<a href="rider_action.php?id=<?= $data_drv[$i]['iUserId']; ?>" data-toggle="tooltip" title="Edit <?=$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>">
																		<img src="img/edit-icon.png" alt="Edit">
																	</a>
																<?php }?>
																
																<a href="rider.php?iUserId=<?= $data_drv[$i]['iUserId']; ?>&status=Active" data-toggle="tooltip" title="Active <?=$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>">
																	<img src="img/active-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
																</a>
																<a href="rider.php?iUserId=<?= $data_drv[$i]['iUserId']; ?>&status=Inactive" data-toggle="tooltip" title="Inactive <?=$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>">
																	<img src="img/inactive-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
																</a>
																<?php if($data_drv[$i]['eStatus']!="Deleted"){?>
																	<form name="delete_form" id="delete_form" method="post" action="" onSubmit="return confirm_delete()" class="margin0">
																		<input type="hidden" name="hdn_del_id" id="hdn_del_id" value="<?= $data_drv[$i]['iUserId']; ?>">
																		<input type="hidden" name="action" id="action" value="delete">
																		<button class="remove_btn001" data-toggle="tooltip" title="Delete <?=$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>">
																			<img src="img/delete-icon.png" alt="Delete">
																		</button>
																	</form>
																	
																	<form name="reset_form" id="reset_form" method="post" action="" onSubmit="return confirm('Are you sure?you want to reset <?= $data_drv[$i]['vName'].' '.$data_drv[$i]['vLastName'];?> account?')" class="margin0">
																		<input type="hidden" name="res_id" id="res_id" value="<?= $data_drv[$i]['iUserId']; ?>">
																		<input type="hidden" name="action" id="action" value="reset">
																		<button class="remove_btn001" data-toggle="tooltip" title="Reset <?=$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>">
																			<img src="img/reset-icon.png" alt="Reset">
																		</button>
																	</form>
																<?php }?>
															</td>
														</tr>
													<? } ?>
													
												</tbody>
											</table>
										</div>
										
									</div>
								</div>
							</div> <!--TABLE-END-->
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<!--END PAGE CONTENT -->
		</div>
		<!--END MAIN WRAPPER -->
		
		
		<? include_once('footer.php');?>
		<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
		<script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
		<script>
            var options = ["Active","Inactive","Deleted"];
			
			$( '.dropdown-menu a' ).on( 'click', function( event ) {
                //alert(options);
				var $target = $( event.currentTarget ),
				val = $target.attr( 'data-value' ),
				$inp = $target.find( 'input' ),
				idx;
				
				if ( ( idx = options.indexOf( val ) ) > -1 ) {
					
                    options.splice( idx, 1 );
                    setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
					} else {
                    options.push( val );
                    setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
				}
				//alert(options);
				$( event.target ).blur();
				
				//console.log( options );
				//alert(options);
				var request = $.ajax({
					type: "POST",
					url: 'change_rider_list.php',
					data: {result:JSON.stringify(options)},
					success: function (data)
					{
                        $("#data_drv001").html(data);
						//document.getElementById("code").value = data;
						//window.location = 'profile.php';
					}
				});
				return false;
			});
			
            $(document).ready(function () {
				$('#dataTables-example').dataTable({
					"order": [[ 2, "desc" ]]
				});
			});
            function confirm_delete()
            {
				var confirm_ans = confirm("Are You sure You want to Delete this <?=$langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>?");
				return confirm_ans;
				//document.getElementById(id).submit();
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
		</script>
	</body>
	<!-- END BODY-->
</html>
