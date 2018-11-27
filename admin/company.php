<?
	ob_start();
	include_once('../common.php');
	
	if(!isset($generalobjAdmin)){
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();
	
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$iCompanyId = isset($_REQUEST['iCompanyId']) ? $_REQUEST['iCompanyId'] : '';
	$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
	$action 	= isset($_REQUEST['action'])?$_REQUEST['action']:'view';
	$success	= isset($_REQUEST['success'])?$_REQUEST['success']:0;
	$hdn_del_id	= isset($_REQUEST['hdn_del_id'])?$_REQUEST['hdn_del_id']:'';
	$script		= "Company";
	
	$sql = "select * from country";
	$db_country = $obj->MySQLSelect($sql);
	//echo"<pre>";print_r($db_country);exit;
	
	$sql = "select * from language_master where eStatus = 'Active'";
	$db_lang = $obj->MySQLSelect($sql);
	
	if ($iCompanyId != '' && $status != '') {
		if(SITE_TYPE !='Demo'){
			$query = "UPDATE company SET eStatus = '" . $status . "' WHERE iCompanyId = '" . $iCompanyId . "'";
			$obj->sql_query($query);
			$var_msg="Company ".$status." Successfully.";
			header("Location:company.php?success=1&var_msg=".$var_msg);exit;
		}
		else{
			header("Location:company.php?success=2");
			echo "<script>document.location='company.php?success=2';</script>";
			exit;
		}
		$sql="SELECT * FROM company WHERE iCompanyId = '" . $iCompanyId . "'";
		$db_status = $obj->MySQLSelect($sql);
		$maildata['EMAIL'] =$db_status[0]['vEmail'];
		$maildata['NAME'] = $db_status[0]['vCompany'];
		
		$maildata['DETAIL']="Your Account is ".$db_status[0]['eStatus'];
		
		$generalobj->send_email_user("ACCOUNT_STATUS",$maildata);
	}
	if($action == 'delete' && $hdn_del_id != '')
	{
		
		$query = "UPDATE company SET eStatus = 'Deleted' WHERE iCompanyId = '".$hdn_del_id."'";
		$obj->sql_query($query);
		$action = "view";
		
	}
	
	$cmp_ssql = "";
	if(SITE_TYPE =='Demo'){
		$cmp_ssql = " And tRegistrationDate > '".WEEK_DATE."'";
	}
	
	if($action == 'view')
	{
		$sql = "SELECT * FROM company WHERE eStatus != 'Deleted' $cmp_ssql order by tRegistrationDate desc";
		$data_drv  	= $obj->MySQLSelect($sql);
		
	}
	
	//echo "<Pre>";print_r($sql);exit;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<title>Admin | Company</title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
		
		<? include_once('global_files.php');?>
		<script>
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
			
		</script>
	</head>
	
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
								<h2>Company</h2>
								<a href="company_action.php"><input type="button" id="show-add-form" value="ADD A COMPANY" class="add-btn"></a>
								<input type="button" id="cancel-add-form" value="CANCEL" class="cancel-btn">
							</div>
						</div>
						<hr />
					</div>
					<? if($success == 1) { ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
							<?=isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : "Company Updated SuccessFully.";?>
						</div><br/>
						<? }elseif ($success == 2) { ?>
						<div class="alert alert-danger alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
							"Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
						</div><br/>
					<? }?>
					 
					<div class="table-list">
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										Company
									</div>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover" id="dataTables-example">
												<thead>
													<tr>
														<th>COMPANY NAME</th>
														<!--<th>EMAIL</th>-->
														<th><?php echo $langage_lbl_admin['LBL_DASHBOARD_DRIVERS_ADMIN'];?></th>
														<th>MOBILE</th>
														<th>SIGN UP DATE</th>
														<th>STATUS</th>
														<th>EDIT DOCUMENT</th>
														<th align="center" style="text-align:center;">ACTION</th>
														<!--<th>Delete</th>-->
													</tr>
												</thead>
												<tbody>
													
													<?for($i=0;$i<count($data_drv);$i++)
														{
															$sql = "SELECT count(iDriverId) as count from register_driver where iCompanyId = '".$data_drv[$i]['iCompanyId']."'";
															$db_cnt = $obj->MySQLSELECT($sql);
															$data_drv[$i]['count'] = $db_cnt[0]['count'];
															//echo "<pre>";print_r($db_cnt);echo "</pre>";
														?>
														<tr class="gradeA">
															<td><? echo $data_drv[$i]['vCompany']; ?></td>
															<!--<td><? ?></td>-->
															<td><a href="driver.php?iCompanyid=<?= $data_drv[$i]['iCompanyId']; ?>" target="_blank"><? echo $data_drv[$i]['count']; ?></a></td>
															<!--<td class="center"><? echo $data_drv[$i]['vServiceLoc']; ?></td>-->
															<!--<td><? echo $data_drv[$i]['vPhone']; ?></td>-->
															<td><?= $generalobjAdmin->clearPhone($data_drv[$i]['vPhone']);?></td>
															<td><? echo $data_drv[$i]['tRegistrationDate'];?>
																<!-- --->
																<? if($data_drv[$i]['iCompanyId']==1) {?>
																	<td width="10%" align="center">
																		 <b align="center">-----</b>
																	</td>
																	
																	<? }else {?>
																	<td width="10%" align="center">
																	<? if($data_drv[$i]['eStatus'] == 'Active') {
																			$dis_img = "img/active-icon.png";
																		}else if($data_drv[$i]['eStatus'] == 'Inactive'){
																			 $dis_img = "img/inactive-icon.png";
																		}else if($data_drv[$i]['eStatus'] == 'Deleted'){
																			$dis_img = "img/delete-icon.png";
																		}
																		?>
																		<img src="<?=$dis_img ?>" alt="<?=$data_drv[$i]['eStatus']?>">
																	</td>
																<? }?>
																<td align="center" width="10%">
																	<? if($data_drv[$i]['iCompanyId']==1) {?> 
																		<b align="center">-----</b>
																	</td>
																	<? }else {?>
																	<a href="company_document_action.php?id=<?= $data_drv[$i]['iCompanyId']; ?>&action=edit">
																		<img src="img/edit-doc.png" alt="Edit Document" >
																	</a>
																<? }?>	
															</td>
															<td class="center" width="12%"  align="center" style="text-align:center;">
																<a href="company_action.php?id=<?=$data_drv[$i]['iCompanyId'];?>" data-toggle="tooltip" title="Edit">
																	<img src="img/edit-icon.png" alt="Edit">
																</a>
																<a href="company.php?iCompanyId=<?= $data_drv[$i]['iCompanyId']; ?>&status=Active" data-toggle="tooltip" title="Active Company">
																	<? if($data_drv[$i]['iCompanyId']!=1) {?>
																		<img src="img/active-icon.png" alt="Active" >
																	<? } ?>
																</a>
																<a href="company.php?iCompanyId=<?= $data_drv[$i]['iCompanyId']; ?>&status=Inactive " data-toggle="tooltip" title="Inactive Company">
																	<? if($data_drv[$i]['iCompanyId']!=1) {?>
																		<img src="img/inactive-icon.png" alt="Inactive" >	
																	<? } ?>
																</a>
											
															</td>
															<!--<td class="center" width="10%">
																<form name="delete_form" id="delete_form" method="post" action="" onsubmit="return confirm_delete()" class="margin0">
																<input type="hidden" name="hdn_del_id" id="hdn_del_id" value="<?=$data_drv[$i]['iCompanyId'];?>">
																<input type="hidden" name="action" id="action" value="delete">
																<button class="btn btn-danger">
																<i class="icon-remove icon-white"></i> Delete
																</button>
																</form>
															</td>-->
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
					<!-- </div> -->
				<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<!--END PAGE CONTENT -->
		</div>
		<!--END MAIN WRAPPER -->
		
		
		<? include_once('footer.php');?>
		<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
		<script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
		<script>
			$(document).ready(function () {
				$('#dataTables-example').dataTable({
					"order": [[ 3, "desc" ]]
				});
			});
			function confirm_delete()
			{
				var confirm_ans = confirm("Are You sure You want to Delete Company?");
				return confirm_ans;
				//document.getElementById(id).submit();
			}
			function changeCode(id)
			{
				var request = $.ajax({
					type: "POST",
					url: 'change_code.php',
					data: 'id='+id,
					
					success: function(data)
					{
						document.getElementById("code").value = data ;
						//window.location = 'profile.php';
					}
				});
			}
		</script>
	</body>
	<!-- END BODY-->
</html>
