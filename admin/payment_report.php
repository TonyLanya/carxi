<?
include_once('../common.php');
$tbl_name 	= 'trips';
if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$ENABLE_TIP_MODULE = $generalobj->getConfigurations("configurations","ENABLE_TIP_MODULE");


$abc = 'admin,company';

$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


 //$generalobj->setRole($abc,$url);
$script='Payment Report';

$sql = "select * from company WHERE 1=1";
$db_company = $obj->MySQLSelect($sql);

$sql = "select * from register_driver WHERE 1=1";
$db_driver_app = $obj->MySQLSelect($sql);

$sql = "select * from register_user WHERE 1=1 ORDER BY vName";
$db_passanger = $obj->MySQLSelect($sql);

//echo "<pre>";print_r($db_driver_app);exit;

$action=(isset($_REQUEST['action'])?$_REQUEST['action']:'');
$ssql='';


if($action!='')
{
	$startDate=$_REQUEST['startDate'];
	$iCompanyId = $_REQUEST['iCompanyId'];
	$endDate=$_REQUEST['endDate'];
  $iDriverId = $_REQUEST['iDriverId'];
  $iUserId = $_REQUEST['iUserId'];
  $eDriverPaymentStatus = $_REQUEST['eDriverPaymentStatus'];
  $vTripPaymentMode = $_REQUEST['vTripPaymentMode'];
  
	if($startDate!=''){
		$ssql.=" AND Date(tEndDate) >='".$startDate."'";
	}
	if($endDate!=''){
		$ssql.=" AND Date(tEndDate) <='".$endDate."'";
	}
  
  if($iCompanyId!=''){
		if($iDriverId!=''){
      $ssql.=" AND tr.iDriverId = '".$iDriverId."' AND rd.iCompanyId = '".$iCompanyId."'";
    }else{
      $sql = "select iDriverId from register_driver WHERE iCompanyId = '".$iCompanyId."' ";
		  $db_driver2 = $obj->MySQLSelect($sql);
      if(count($db_driver2)>0)
  		{
  			for($i=0;$i<count($db_driver2);$i++)
  			{
  				 $id.=$db_driver2[$i]['iDriverId'].",";
  			}
  			$id=rtrim($id,",");
  		  $ssql.=" AND tr.iDriverId IN($id)";
  		}else{
        $ssql.=" AND tr.iDriverId = ''";
      }                        
    }
	}else{
    if($iDriverId!=''){
		  $ssql.=" AND tr.iDriverId = '".$iDriverId."'";
	  }
  }
  
	
	if($iUserId!=''){
		$ssql.=" AND tr.iUserId = '".$iUserId."'";
	}
  
  if($vTripPaymentMode!=''){
     $ssql.=" AND tr.vTripPaymentMode = '".$vTripPaymentMode."'";
		/*if($vTripPaymentMode == 'Mbirr'){
      $ssql.=" AND tr.vTripPaymentMode = 'Cash' AND eMBirr = 'Yes'";
    }else{
      $ssql.=" AND tr.vTripPaymentMode = '".$vTripPaymentMode."'";
    }*/  
	}
  
  if($eDriverPaymentStatus!=''){
		$ssql.=" AND tr.eDriverPaymentStatus = '".$eDriverPaymentStatus."'";
	}
}
//$sql = "SELECT * from trips LEFT JOIN register_drivers WHERE 1=1 ".$ssql." ORDER BY iTripId DESC";
$sql = "SELECT tr.*,c.vCompany FROM trips AS tr LEFT JOIN register_driver AS rd ON tr.iDriverId = rd.iDriverId LEFT JOIN company as c ON rd.iCompanyId = c.iCompanyId  WHERE 1 ".$ssql." ORDER BY tr.iTripId DESC";  
$db_trip = $obj->MySQLSelect($sql);
#echo '<pre>'; print_R($db_trip); echo '</pre>';exit;
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
#echo "<pre>";print_r($_REQUEST);exit;
# Code For Settle Payment of Driver
$actionpayment = $_REQUEST['actionpayment'];
$ePayDriver = $_REQUEST['ePayDriver'];
if($actionpayment == "pay_driver" && $ePayDriver == "Yes"){
#echo "<pre>";print_r($_REQUEST);exit;
   $iTripId = $_REQUEST['iTripId'];
   for($k=0;$k<count($iTripId);$k++){
     $sql = "SELECT ePaymentDriverStatus from payments WHERE iTripId = '".$iTripId[$k]."' and ePaymentDriverStatus = 'UnPaid'";
     $db_pay = $obj->MySQLSelect($sql);
     if(count($db_pay) > 0){
       $query = "UPDATE payments SET ePaymentDriverStatus = 'Paid' WHERE iTripId = '" .$iTripId[$k]. "'";
       $obj->sql_query($query);
       
       $query = "UPDATE trips SET eDriverPaymentStatus = 'Settelled', ePayment_request = 'Yes' WHERE iTripId = '" .$iTripId[$k]. "'";
       $obj->sql_query($query);
     }else{
       $query = "UPDATE trips SET eDriverPaymentStatus = 'Settelled', ePayment_request = 'Yes' WHERE iTripId = '" .$iTripId[$k]. "'";
       $obj->sql_query($query);
     }
   }
   header("Location:payment_report.php?".$_SERVER['QUERY_STRING']);
   exit;
}
# Code For Settle Payment of Driver
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD-->
<head>
	<meta charset="UTF-8" />
    <title><?=$SITE_NAME?> | Payment Report</title>
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
                <div class="col-lg-12">
				 <h2>Payment Report</h2>
                 </div>
				</div>
				<hr />
					<div class="table-list">
						<div class="row">
								<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading driver-neww1 driver-neww2">
                                    <b>Payment Report</b>
                                    </div>
									<div class="panel-body">
										<div class="table-responsive">
											<div class="alert alert-error" id="alert" style="display: none;" >
												<strong>Oh snap!</strong>
												<p></p>
											</div>
											<form name="search" id="frmsearch" action="javascript:void(0);" method="get" onSubmit="return checkvalid()">
												<input type="hidden" name="action" id="action" value="search" />
												<div class="Posted-date mytrip-page mytrip-page-select mytrip-page-select1">
													<input type="hidden" name="action" value="search" />
													<h3>Search <?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?>  by Date</h3>
													<span>
													<a onClick="return todayDate('dp4','dp5');"><?=$langage_lbl['LBL_MYTRIP_Today']; ?></a>
													<a onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Yesterday']; ?></a>
													<a onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Week']; ?></a>
													<a onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous_Week']; ?></a>
													<a onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Month']; ?></a>
													<a onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous Month']; ?></a>
													<a onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Year']; ?></a>
													<a onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous_Year']; ?></a>
													</span> 
													<span>
													<input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value="" readonly />
													<input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value="" readonly />
                                                    <select name="iCompanyId" id="iCompanyId" class="form-control input-sm driver-trip-detail-select" style="width:18%;display:table-row-group;">
														<option value="">Search By Company Name</option>
														<?for($j=0;$j<count($db_company);$j++){?>
														<option value="<?=$db_company[$j]['iCompanyId'];?>" <?if($iCompanyId == $db_company[$j]['iCompanyId']){?>selected <?}?>><?=$db_company[$j]['vCompany'];?></option>
														<?}?>
													 </select>
                                                    <select name="iDriverId" id="iDriverId" class="form-control input-sm driver-trip-detail-select" style="display:table-row-group;">
														<option value="">Search By  <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name</option>
														<?for($j=0;$j<count($db_driver_app);$j++){?>
														<option value="<?=$db_driver_app[$j]['iDriverId'];?>" <?if($iDriverId == $db_driver_app[$j]['iDriverId']){?>selected <?}?>><?=$db_driver_app[$j]['vName'];?> <?=$db_driver_app[$j]['vLastName'];?></option>
														<?}?>
													</select>
                                                    <select name="iUserId" id="iUserId" class="form-control input-sm" style="width:18%;display:table-row-group;">
													<option value="">Search By <?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?> Name</option>
													<?for($j=0;$j<count($db_passanger);$j++){?>
													<option value="<?=$db_passanger[$j]['iUserId'];?>" <?if($iUserId == $db_passanger[$j]['iUserId']){?>selected <?}?>><?=$db_passanger[$j]['vName'];?> <?=$db_passanger[$j]['vLastName'];?></option>
													<?}?>
												  </select>
                                                    <select name="vTripPaymentMode" id="vTripPaymentMode" class="form-control input-sm" style="width:18%;display:table-row-group;">
													<option value="">Search By Payment Type</option>
													<option value="Cash" <?if($vTripPaymentMode == "Cash"){?>selected <?}?>>Cash</option>
													<option value="Card" <?if($vTripPaymentMode == "Card"){?>selected <?}?>>Card</option>
													<option value="Paypal" <?if($vTripPaymentMode == "Paypal"){?>selected <?}?>>Paypal</option>
												  </select>
                                                   <select name="eDriverPaymentStatus" id="eDriverPaymentStatus" class="form-control input-sm" style="width:18%;display:table-row-group;">
													<option value="">Search By  <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Payment Status</option>
													<option value="Settelled" <?if($eDriverPaymentStatus == "Settelled"){?>selected <?}?>>Settelled</option>
													<option value="Unsettelled" <?if($eDriverPaymentStatus == "Unsettelled"){?>selected <?}?>>Unsettelled</option>
												  </select>
													<b><button class="driver-trip-btn" onClick="search_filters();"><?=$langage_lbl['LBL_Search']; ?></button>
													<?if($action != ""){ ?>
													 <?php if($iCompanyId != "" && $iDriverId != ""){ ?>
														<a href="payment_report.php" class="add-btn driver-trip-btn"><?=$langage_lbl['LBL_RESET']; ?></a>
														<a style="text-align: center;" href="javascript:void(0);" class="btn btn-danger driver-trip-btn" onClick="exportlist();">Export Drivers List</a>
														<a style="text-align: center;" class="btn btn-default driver-trip-btn" onClick="javascript:window.open('print_payment_report.php?iCompanyId=<?=$iCompanyId?>&iDriverId=<?=$iDriverId?>&iUserId=<?=$iUserId?>&eDriverPaymentStatus=<?=$eDriverPaymentStatus?>&vTripPaymentMode=<?=$vTripPaymentMode?>&startDate=<?=$startDate?>&endDate=<?=$endDate?>','','width=1150,height=900,scrollbars=yes');">Print</a>
														<?}else{?>
														<a href="payment_report.php" class="add-btn driver-trip-btn"><?=$langage_lbl['LBL_RESET']; ?></a>
														<a href="javascript:void(0);" class="btn btn-danger driver-trip-btn" onClick="exportlist();">Export Drivers List</a>
														 <?}?>
														 <?}?>
														</b> 
													</span>
												</div>
											</form>
                      <form name="frmpayment" id="frmpayment" method="post" action="">
                          <input type="hidden" id="actionpayment" name="actionpayment" value="pay_driver">
                          <input type="hidden"  name="iTripId" id="iTripId" value="">
                          <input type="hidden"  name="ePayDriver" id="ePayDriver" value="">
 											<table class="table table-striped table-bordered table-hover" id="dataTables-example123" <?if($action == ""){?>style="display:none;"<?}else{?> style="display:;" <?}?>>
												<thead>
													<tr>
														<th><?php echo $langage_lbl_admin['LBL_RIDE_NO_ADMIN'];?> </th>
														<th>Driver Name</th>
														<th><?php echo $langage_lbl_admin['LBL_PASSANGER_TXT_ADMIN'];?>  Name</th>
														<th>Trip Date </th>
														<!--<th>Address</th>-->
														<th>Total Fare</th>
														<th>Platform Fees</th>
														<th>Promo Code Discount</th>
														<th>Wallet Debit</th>
														<?php if($ENABLE_TIP_MODULE == "Yes"){?>
														<th>Tip</th>
														<?php }?>
														<th>Driver pay Amount</th>
														<th>Trip Status</th>
														<th>Payment method</th>
														<th>Driver Payment Status</th> 
														<th></th>                            
													</tr>
												</thead>
												<tbody>
													<?
                          if(count($db_trip) > 0){
                            $tot_fare = 0.00;
                            $tot_site_commission = 0.00;
                            $tot_promo_discount = 0.00;
                            $tot_driver_refund = 0.00;
                            $tot_wallentPayment = 0.00;
                          	
  													for($i=0;$i<count($db_trip);$i++)
                            {
                              $sq="select concat(vName,' ',vLastName) as drivername from register_driver where iDriverId='".$db_trip[$i]['iDriverId']."'";
                              $name=$obj->MySQLSelect($sq);
							  
                              $db_trip[$i]["drivername"]=$name[0]["drivername"];
                              $totalfare = $db_trip[$i]['fTripGenerateFare'];
                              $site_commission = $db_trip[$i]['fCommision'];
                              $promocodediscount = $db_trip[$i]['fDiscount'];
                              $wallentPayment = $db_trip[$i]['fWalletDebit'];
                              //$driver_payment = $totalfare+$promocodediscount-$site_commission;
							  $driver_payment = $totalfare - $site_commission;
                              
                              $tot_fare = $tot_fare+$totalfare;
                              $tot_site_commission = $tot_site_commission+$site_commission;
                              $tot_promo_discount = $tot_promo_discount+$promocodediscount;
                              $tot_wallentPayment = $tot_wallentPayment+$wallentPayment;
                              $tot_driver_refund = $tot_driver_refund+$driver_payment;
                             
                                 $paymentmode = $db_trip[$i]['vTripPaymentMode'];
								 
								 $sq="select concat(vName,' ',vLastName) as passanger from register_user where iUserId='".$db_trip[$i]['iUserId']."'";
                              $name2=$obj->MySQLSelect($sq);
                               
                              $db_trip[$i]["passanger"]=$name2[0]["passanger"];
  													?>
  															<tr class="gradeA">
  															  <td><?=$db_trip[$i]['vRideNo'];?></td>
															  <td><?=$db_trip[$i]['drivername'];?></td>
															  <td><?=$db_trip[$i]['passanger'];?></td>
															  <td><?= date('d-m-Y',strtotime($db_trip[$i]['tTripRequestDate']));?></td>
																
																<td align="center">
																<?php
																	if($db_trip[$i]['fTripGenerateFare'] != "" && $db_trip[$i]['fTripGenerateFare'] != 0)
																	{
																		echo $generalobj->trip_currency($db_trip[$i]['fTripGenerateFare']);
																	}
																	else
																	{
																		echo '-';
																	}
																?>
																</td>
																
																<td align="center"><?php if($db_trip[$i]['fCommision'] != "" && $db_trip[$i]['fCommision'] != 0) { echo $generalobj->trip_currency($db_trip[$i]['fCommision']); }else { echo '-'; } ?></td>
																
																<td align="center"><?php if($db_trip[$i]['fDiscount'] != "" && $db_trip[$i]['fDiscount'] != 0) { echo $generalobj->trip_currency($db_trip[$i]['fDiscount']); }else { echo '-'; }?></td>
																
																<td align="center"><?php if($db_trip[$i]['fWalletDebit'] != "" && $db_trip[$i]['fWalletDebit'] != 0) { echo $generalobj->trip_currency($db_trip[$i]['fWalletDebit']); }else { echo '-'; }?></td>
																<td>
																	<?php
																	if($db_trip[$i]['fTipPrice']!="0")	
																	{
																		$total_tip += $db_trip[$i]['fTipPrice'];;
																		echo $generalobj->trip_currency($db_trip[$i]['fTipPrice']);
																		//echo $db_trip[$i]['fTipPrice'];
																	}
																	else
																	{
																		echo "-";
																	}	
																	?>
																</td>
																
																<td align="center">
																  <?php
																	  if($driver_payment != "" && $driver_payment != 0)
																	  {
																			echo $generalobj->trip_currency($driver_payment);
																		}
																		else
																		{
																			echo '-'; 
																		}
																	?>
																</td>
																<td><?=$db_trip[$i]['iActive'];?></td>
															  <td><?=$paymentmode;?></td>
															  <td><?=$db_trip[$i]['eDriverPaymentStatus'];?></td>
															  <td>
																  <? 
																	  if($db_trip[$i]['eDriverPaymentStatus'] == 'Unsettelled'){
																  ?>
																	  <input class="validate[required]" type="checkbox" value="<?=$db_trip[$i]['iTripId']?>" id="iTripId_<?=$db_trip[$i]['iTripId']?>" name="iTripId[]">
																  <?
																	  }
																  ?>
																</td>
  															</tr>
  													<? } ?>
                            <tr class="gradeA">
                              <td colspan="8" align="right">Total Fare</td>
                              <td align="right" colspan="2"><?=$generalobj->trip_currency($tot_fare);?></td>
							  <td colspan="4" align="right"></td>
                            </tr>
                            <tr class="gradeA">
                              <td colspan="8" align="right">Total Platform Fees</td>
                              <td  align="right" colspan="2"><?=$generalobj->trip_currency($tot_site_commission);?></td>
							  <td colspan="4" align="right"></td>
                            </tr>
                            <tr class="gradeA">
                              <td colspan="8" align="right">Total Promo Discount</td>
                              <td  align="right" colspan="2"><?=$generalobj->trip_currency($tot_promo_discount);?></td>
							  <td colspan="4" align="right"></td>
                            </tr>
							<tr class="gradeA">
                              <td colspan="8" align="right">Total Wallet Debit</td>
                              <td  align="right" colspan="2"><?=$generalobj->trip_currency($tot_wallentPayment);?></td>
							  <td colspan="4" align="right"></td>
                            </tr>
							<?php if($ENABLE_TIP_MODULE == "Yes"){?>
							<tr class="gradeA">
								<td colspan="8" align="right">Total Tip Amount</td>
                              <td  align="right" colspan="2"><?=$generalobj->trip_currency($total_tip);?></td>
							  <td colspan="4" align="right"></td>
                            </tr>
							<tr class="gradeA">
                              <td colspan="8" align="right">Total Driver Payment</td>
                              <td  align="right" colspan="2"><?=$generalobj->trip_currency($tot_driver_refund + $total_tip);?></td>
							  <td colspan="4" align="right"></td>
                            </tr>
							<?}else{?>
							<tr class="gradeA">
								<td colspan="8" align="right">Total Driver Payment</td>
								<td  align="right" colspan="2"><?=$generalobj->trip_currency($tot_driver_refund);?></td>
							  <td colspan="4" align="right"></td>
                            </tr>
							<?}?>
                            <tr class="gradeA">
                              <td colspan="10" align="right"><div class="row payment-report-button">
                            <span>
                            <a onClick="javascript:Paytodriver(); return false;" href="javascript:void(0);"><button class="btn btn-primary ">Mark As Settelled</button></a>
        					</span>
        					</div></td>
							<td colspan="4" align="right"></td>
                            </tr>
                          
                          <?}else{?>
                          <tr class="gradeA">
                               <td colspan="12" style="text-align:center;"> No Payment Details Found.</td>
                          </tr>
                          <?}?>
                          

												</tbody>
											</table>
                      </form>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
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
		 function todayDate()
		 {
			 $("#dp4").val('<?= $Today;?>');
			 $("#dp5").val('<?= $Today;?>');
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
     
     function redirectpaymentpage(url)
     {   
        //$("#frmsearch").reset();
        document.getElementById("action").value = '';
        document.getElementById("frmsearch").reset();
        window.location=url;
     }
     
     function getCheckCount(frmpayment)
     {
      	var x=0;
      	var threasold_value=0;
      	for(i=0;i < frmpayment.elements.length;i++)
      	{	if ( frmpayment.elements[i].checked == true) 
      			{x++;}
      	}
      	return x;
     }

     
     function Paytodriver(){
        y = getCheckCount(document.frmpayment);
       
        if(y>0)
      	{
          ans = confirm("Are you sure you want to Pay To Driver?");
          if(ans == false)
          {
             return false;
          }
          $("#ePayDriver").val('Yes');
          document.frmpayment.submit();
        }
        else{
          alert("Select Trip for Pay To Driver");
          return false;
        }
      }
      
	function exportlist(){    
		document.search.action="export_driver_details.php";
		document.search.submit();
	}

	function search_filters() {
		document.search.action="";
		document.search.submit();
	}
  
     
     /*$('#dataTables-example').DataTable( {
        paging: false
      } );*/
     
    </script>
</body>
	<!-- END BODY-->
</html>
