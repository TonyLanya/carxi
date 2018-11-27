<?

include_once('../common.php');
$tbl_name 	= 'register_driver';
if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
 $abc = 'admin,company';

$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$actionType = isset($_REQUEST['type'])?$_REQUEST['type']:"";
 //$generalobj->setRole($abc,$url);
#echo $actionType;exit;
#echo"<pre>";print_r($_REQUEST);exit;
$script='Trips';

$sql = "select * from company WHERE eStatus != 'Deleted'";
$db_company = $obj->MySQLSelect($sql);

$sql = "select * from register_driver WHERE eStatus != 'Deleted'";
$db_company = $obj->MySQLSelect($sql);
$action=(isset($_REQUEST['action'])?$_REQUEST['action']:'');
$ssql='';
if($action!='')
{
	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];
	if($startDate!=''){
		$ssql.=" AND Date(t.tStartDate) >='".$startDate."'";
	}
	if($endDate!=''){
		$ssql.=" AND Date(t.tEndDate) <='".$endDate."'";
	}
}
$ssl = "";


$cmp_ssql = "";
if(SITE_TYPE =='Demo'){
	$cmp_ssql = " And t.tEndDate > '".WEEK_DATE."'";
}
if($actionType == "onRide") {
	$ssl = " AND (t.iActive = 'On Going Trip' OR t.iActive = 'Active') AND t.eCancelled='No'";
}else if($actionType == "cancel") {
	$ssl = " AND t.iActive = 'Canceled' OR t.eCancelled='yes'";
}else if($actionType == "complete") {
	$ssl = " AND t.iActive = 'Finished' AND t.eCancelled='No'";
}
 $sql = "SELECT u.vName, u.vLastName, d.vAvgRating, t.tStartDate ,t.tEndDate, t.tTripRequestDate,t.vCancelReason,t.vCancelComment, t.iFare,t.eType,d.iDriverId, t.tSaddress,t.vRideNo, t.tDaddress, t.fWalletDebit , d.vName AS name,c.vName AS comp,c.vCompany, d.vLastName AS lname,t.eCarType,t.iTripId,vt.vVehicleType,t.iActive 
FROM register_driver d
RIGHT JOIN trips t ON d.iDriverId = t.iDriverId
LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId
LEFT JOIN  register_user u ON t.iUserId = u.iUserId JOIN company c ON c.iCompanyId=d.iCompanyId
WHERE 1=1".$ssql.$ssl.$cmp_ssql."
ORDER BY t.iTripId DESC";

$db_trip = $obj->MySQLSelect($sql);
#echo "<pre>";print_r($sql);exit;
/* $sql = "select iDriverId from register_driver where iCompanyId = '".$_SESSION['sess_iCompanyId']."'";
$db_sql = $obj->MySQLSelect($sql);
for($i=0;$i<count($db_sql);$i++)
{
   $db[$i] = $db_sql[$i]['iDriverId'];
}
$ids = implode(",",$db);
$sql = "SELECT t.tSaddress,t.tDaddress,t.iFare,t.iActive,t.iDriverId,d.vName from trips t left join register_driver d on t.iDriverId = d.iDriverId where d.iDriverId IN (".$ids.")";
$db_trip = $obj->MySQLSelect($sql); */
#echo '<pre>'; print_R($db_trip); echo '</pre>';
$Today=Date('Y-m-d');
$tdate=date("d")-1;
$mdate=date("d");
$Yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

$curryearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")));
$curryearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")));
$prevyearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")-1));
$prevyearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")-1));

$currmonthFDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$tdate,date("Y")));
$currmonthTDate = date("Y-m-d",mktime(0,0,0,date("m")+1,date("d")-$mdate,date("Y")));
$prevmonthFDate = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d")-$tdate,date("Y")));
$prevmonthTDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$mdate,date("Y")));

$monday = date( 'Y-m-d', strtotime( 'sunday this week -1 week' ) );
$sunday = date( 'Y-m-d', strtotime( 'saturday this week' ) );

$Pmonday = date( 'Y-m-d', strtotime('sunday this week -2 week'));
$Psunday = date( 'Y-m-d', strtotime('saturday this week -1 week'));

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD-->
<head>
	<meta charset="UTF-8" />
    <title><?=$SITE_NAME?> | <?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?> </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="keywords" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    <? include_once('global_files.php');?>

    <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
				 <h2><?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?> </h2>
				</div>
				<hr />
                <div class="">
					<div class="table-list">
						<div class="row">
								<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading driver-neww1 driver-neww2">
										<b><?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?> </b>
									</div>
									<div class="panel-body">
										<div class="table-responsive">
											<div class="alert alert-error" id="alert" style="display: none;" >
												<strong>Oh snap!</strong>
												<p></p>
											</div>
											<form name="search" action="" method="post" onSubmit="return checkvalid()">
												<div class="Posted-date mytrip-page">
													<input type="hidden" name="action" value="search" />
													<h3><?=$langage_lbl['LBL_MYTRIP_SEARCH_RIDES_POSTED_BY_DATE']; ?></h3>
													<span>
													<a style="cursor:pointer" onClick="return todayDate('dp4','dp5');"><?=$langage_lbl['LBL_MYTRIP_Today']; ?></a>
													<a style="cursor:pointer" onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Yesterday']; ?></a>
													<a style="cursor:pointer" onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Week']; ?></a>
													<a style="cursor:pointer" onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous_Week']; ?></a>
													<a style="cursor:pointer" onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Month']; ?></a>
													<a style="cursor:pointer" onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous Month']; ?></a>
													<a style="cursor:pointer" onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Year']; ?></a>
													<a style="cursor:pointer" onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous_Year']; ?></a>
													</span> 
													<span>
													<input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value="" readonly="" style="cursor:default; background-color: #fff" />
													<input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value="" readonly="" style="cursor:default; background-color: #fff"/>
													<b><button class="driver-trip-btn"><?=$langage_lbl['LBL_Search']; ?></button>
														<button onClick="reset();" class="driver-trip-btn"><?=$langage_lbl['LBL_RESET']; ?></button></b> 
													</span>
												</div>
											</form>
											<div class="row">
											 <div class="col-lg-3">
                                                  <label>Filter by <?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?>  Status :</label>
											  </div>
											 <div class="col-lg-3">
                                                  <select class="form-control" name = 'vStatus' onChange="setRideStatus(this.value);">
                                                       <option value="">All</option>
													   <option value="onRide" <?php if($actionType == "onRide") { echo "selected"; } ?>>On Going <?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?> </option>
													   <option value="complete" <?php if($actionType == "complete") { echo "selected"; } ?>>Completed</option>
													   <option value="cancel" <?php if($actionType == "cancel") { echo "selected"; } ?>>Cancelled</option>
                                                  </select>
                                             </div>
                                        </div>
											<table class="table table-striped table-bordered table-hover" id="dataTables-example">
												<thead>
													<tr>
													<?php if($APP_TYPE != 'UberX'){ ?> 
														<th><?php echo $langage_lbl_admin['LBL_TRIP_TYPE_TXT_ADMIN'];?></th>
														<?php } ?> 
														<th><?php echo $langage_lbl_admin['LBL_TRIP_NO'];?></th>
														<th>Address</th>
														<th><?php echo $langage_lbl_admin['LBL_TRIP_DATE_ADMIN'];?> </th>
														<th>Company</th>
														<th><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> </th>
														<th><?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?> </th>
														<th><?php echo $langage_lbl_admin['LBL_DRIVER_TRIP_FARE_TXT'];?></th>
														<th><?php echo $langage_lbl_admin['LBL_TEXI_ADMIN'];?> </th>
														<th>View Invoice</th>
													</tr>
												</thead>
												<tbody>
													<?
													for($i=0;$i<count($db_trip);$i++)
													{

															//print_r($db_trip);  exit;
															$eType = $db_trip[$i]['eType'];
															$trip_type = ($eType == 'Ride')? 'Ride': 'Delivery';
															?>
															<tr class="gradeA">
															<?php if($APP_TYPE != 'UberX'){ ?> 

																<td>
																	<?=$trip_type;?>
																</td>
																<?php } ?> 
																<td>
																	<?=$db_trip[$i]['vRideNo'];?>
																</td>
																<td width="30%" data-order="<?=$db_trip[$i]['iTripId']?>"><? echo $db_trip[$i]['tSaddress'];
																if($APP_TYPE != "UberX"){echo ' -> '.$db_trip[$i]['tDaddress']; } ?>
																</td>
																<!--<td><?= date('d F, Y',strtotime($db_trip[$i]['tStartDate']));?></td>-->
																<td><?if($db_trip[$i]['tEndDate']=="0000-00-00 00:00:00"){echo date('d-F-Y',strtotime($db_trip[$i]['tTripRequestDate']));}else{echo date('d-F-Y',strtotime($db_trip[$i]['tEndDate']));}?></td>
																<td>
																	<?=$db_trip[$i]['vCompany'];?>
																</td>
																<td>
																	<?=$db_trip[$i]['name']." ".$db_trip[$i]['lname'];?>
																</td>

																<td>
																	<?=$db_trip[$i]['vName']." ".$db_trip[$i]['vLastName'];?>
																</td>
																<td align="center">
																	<?=$generalobj->trip_currency($db_trip[$i]['iFare'] + $db_trip[$i]['fWalletDebit']);?>
																</td>
																<td align="center">
																	<?=$db_trip[$i]['vVehicleType'];?>

																</td>
																<td align="center" width="10%">
																<?if($db_trip[$i]['iFare']!=0 OR $db_trip[$i]['iActive'] == 'Finished'){?>
																  <a href="javascript:void(0);" onclick='javascript:window.open("invoice.php?iTripId=<?=$db_trip[$i]['iTripId']?>","_blank")';">
																	<button class="btn btn-primary">
																		<i class="icon-th-list  icon-white"><b>View Invoice</b></i>
																	</button>
																 </a>
																<?}else
																{
																	if($db_trip[$i]['iActive']== "Active" OR $db_trip[$i]['iActive']== "On Going Trip")
																	{
																		echo "On Ride";
																	}
																	else if($db_trip[$i]['iActive']== "Canceled"  && $db_trip[$i]['vCancelReason'] != '')
																	{ ?>
																		<a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#uiModal1_<?=$db_trip[$i]['iTripId'];?>">Cancel Reason</a>
																		
																	<?php }
																	else if($db_trip[$i]['iActive']== "Canceled" ){

																		echo "Cancelled";
																	}
																	else
																	{
																		echo $db_trip[$i]['iActive'];
																	}
																	
																}?>
																</td>
															</tr>
															  <div class="clear"></div>
															 <div class="modal fade" id="uiModal1_<?=$db_trip[$i]['iTripId'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																  <div class="modal-content image-upload-1" style="width:400px;">
																	   <div class="upload-content" style="width:350px;">
																			<h3>Trip Cancel Reason</h3>	
																			<h4>Cancel Reason: <?=$db_trip[$i]['vCancelReason']." ".$db_trip[$i]['vCancelComment'];?></h4>
																			<form class="form-horizontal" id="frm6" method="post" enctype="multipart/form-data" action="" name="frm6">
																				 										 
																				 
																				 <input type="button" class="save" data-dismiss="modal" name="cancel" value="Close">
																			</form>
																	   </div>
																  </div>
															 </div>

													<? } ?>
													

												</tbody>
											</table>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div class="clear"></div>
			</div>
        </div>
       <!--END PAGE CONTENT -->
    </div>
    <!--END MAIN WRAPPER -->

	<? include_once('footer.php');?>
	<link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
	<script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
	<script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
	<script src="../assets/js/jquery-ui.min.js"></script>
	<script src="../assets/plugins/uniform/jquery.uniform.min.js"></script>
	<script src="../assets/plugins/inputlimiter/jquery.inputlimiter.1.3.1.min.js"></script>
	<script src="../assets/plugins/chosen/chosen.jquery.min.js"></script>
	<script src="../assets/plugins/colorpicker/js/bootstrap-colorpicker.js"></script>
	<script src="../assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
	<script src="../assets/plugins/validVal/js/jquery.validVal.min.js"></script>
	<script src="../assets/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
	<script src="../assets/plugins/timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="../assets/plugins/autosize/jquery.autosize.min.js"></script>
	<script src="../assets/plugins/jasny/js/bootstrap-inputmask.js"></script>
	<script src="../assets/js/formsInit.js"></script>
    <script>
         $(document).ready(function () {
			 if('<?=$startDate?>'!=''){
				 $("#dp4").val('<?=$startDate?>');
				 $("#dp4").datepicker('update' , '<?=$startDate?>');
			 }
			 if('<?=$endDate?>'!=''){
				 $("#dp5").datepicker('update' , '<?= $endDate;?>');
				 $("#dp5").val('<?= $endDate;?>');
			 }
             $('#dataTables-example').dataTable({
				  "order": [[ 0, "desc" ]]
				 });
			 formInit();
         });
		 
		 function setRideStatus(actionStatus) {
			 window.location.href = "trip.php?type="+actionStatus;
		 }
		 function todayDate()
		 {
			 $("#dp4").val('<?= $Today;?>');
			 $("#dp5").val('<?= $Today;?>');
		 }
		 function reset() {
			location.reload();
			
		}	
		 function yesterdayDate()
		 {
			 $("#dp4").val('<?= $Yesterday;?>');
			 $("#dp4").datepicker('update' , '<?= $Yesterday;?>');
			 $("#dp5").datepicker('update' , '<?= $Yesterday;?>');
			 $("#dp4").change();
			 $("#dp5").change();
			 $("#dp5").val('<?= $Yesterday;?>');
		 }
		 function currentweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $monday;?>');
			 $("#dp4").datepicker('update' , '<?= $monday;?>');
			 $("#dp5").datepicker('update' , '<?= $sunday;?>');
			 $("#dp5").val('<?= $sunday;?>');
		 }
		 function previousweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $Pmonday;?>');
			 $("#dp4").datepicker('update' , '<?= $Pmonday;?>');
			 $("#dp5").datepicker('update' , '<?= $Psunday;?>');
			 $("#dp5").val('<?= $Psunday;?>');
		 }
		 function currentmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $currmonthFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $currmonthFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $currmonthTDate;?>');
			 $("#dp5").val('<?= $currmonthTDate;?>');
		 }
		 function previousmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevmonthFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $prevmonthFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $prevmonthTDate;?>');
			 $("#dp5").val('<?= $prevmonthTDate;?>');
		 }
		 function currentyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $curryearFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $curryearFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $curryearTDate;?>');
			 $("#dp5").val('<?= $curryearTDate;?>');
		 }
		 function previousyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevyearFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $prevyearFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $prevyearTDate;?>');
			 $("#dp5").val('<?= $prevyearTDate;?>');
		 }
		 function checkvalid(){
			 if($("#dp5").val() < $("#dp4").val()){
				 alert("From date should be lesser than To date.")
				 return false;
			 }
		 }
    </script>
</body>
<!-- END BODY-->
</html>
