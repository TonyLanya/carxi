<?
include_once('../common.php');
$tbl_name 	= 'trips';
if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$abc = 'admin,company';
$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

 //$generalobj->setRole($abc,$url);
$script='Driver Payment Report';

#echo "<pre>";print_r($_REQUEST);exit;


# Code For Settle Payment of Driver
$iCountryCode = '';
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

//Country
$sql = "select iCountryId,vCountry,vCountryCode from country WHERE eStatus = 'Active'";
$db_country = $obj->MySQLSelect($sql);

//Select dates
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


$startDate = $monday;
$endDate = $sunday;
$ssql = "";
$success = 0;

if($action != "" && $action == 'newsearch') {
	
	$iCountryCode = $_REQUEST['iCountryCode'];
	$startDate = date("Y-m-d",strtotime($_REQUEST['startDate']));
	$endDate = date("Y-m-d",strtotime($_REQUEST['endDate']));
	
	if($startDate!=''){
		$ssql.=" AND Date(tEndDate) >='".$startDate."'";
	}
	if($endDate!=''){
		$ssql.=" AND Date(tEndDate) <='".$endDate."'";
	}
	
	$sql = "select register_driver.iDriverId,eDriverPaymentStatus,concat(vName,' ',vLastName) as dname,vCountry,vBankAccountHolderName,vAccountNumber,vBankLocation,vBankName,vBIC_SWIFT_Code from register_driver 
	LEFT JOIN trips ON trips.iDriverId=register_driver.iDriverId
	WHERE vCountry = '".$iCountryCode."' AND eDriverPaymentStatus='Unsettelled' $ssql GROUP BY register_driver.iDriverId";
	$db_payment = $obj->MySQLSelect($sql);
	
	for($i=0;$i<count($db_payment);$i++) {
		$db_payment[$i]['cashPayment'] = $generalobjAdmin->getAllCashCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
		$db_payment[$i]['cardPayment'] = $generalobjAdmin->getAllCardCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
		$db_payment[$i]['transferAmount'] = $generalobjAdmin->getTransforAmountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
	}
	
	#echo "<pre>";print_r($db_payment);exit;
}


if($action == "pay_driver" && $_REQUEST['ePayDriver'] == "Yes"){
	$iCountryCode = $_REQUEST['prev_country'];
	$startDate = date("Y-m-d",strtotime($_REQUEST['prev_start']));
	$endDate = date("Y-m-d",strtotime($_REQUEST['prev_end']));
	
	if($startDate!=''){
		$ssql.=" AND Date(tEndDate) >='".$startDate."'";
	}
	if($endDate!=''){
		$ssql.=" AND Date(tEndDate) <='".$endDate."'";
	}
	if(SITE_TYPE !='Demo'){
		foreach($_REQUEST['iDriverId'] as $ids) {
			$sql1 = " UPDATE trips set eDriverPaymentStatus = 'Settelled'
			WHERE iDriverId = '".$ids."' AND eDriverPaymentStatus='Unsettelled' $ssql";
			$obj->sql_query($sql1);
		}
		//echo "<pre>";print_r($db_payment1);exit;
		$success = 1;
	}else {
		$success = 2;
	}
	
	$sql = "select register_driver.iDriverId,eDriverPaymentStatus,concat(vName,' ',vLastName) as dname,vCountry,vBankAccountHolderName,vAccountNumber,vBankLocation,vBankName,vBIC_SWIFT_Code from register_driver 
	LEFT JOIN trips ON trips.iDriverId=register_driver.iDriverId
	WHERE vCountry = '".$iCountryCode."' AND eDriverPaymentStatus='Unsettelled' $ssql GROUP BY register_driver.iDriverId";
	$db_payment = $obj->MySQLSelect($sql);
	
	for($i=0;$i<count($db_payment);$i++) {
		$db_payment[$i]['cashPayment'] = $generalobjAdmin->getAllCashCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
		$db_payment[$i]['cardPayment'] = $generalobjAdmin->getAllCardCountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
		$db_payment[$i]['transferAmount'] = $generalobjAdmin->getTransforAmountbyDriverId($db_payment[$i]['iDriverId'],$ssql);
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
    <title><?=$SITE_NAME?> | Driver Payment Report</title>
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
				 <h2>Driver Payment Report</h2>
				</div>
				<hr />
				<? if($success == 1) { ?>
				 <div class="alert alert-success alert-dismissable">
					  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						Record(s) mark as settlled successful.
				 </div><br/>
				 <? }elseif ($success == 2) { ?>
				   <div class="alert alert-danger alert-dismissable">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						"Mark as Settlled Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
				   </div><br/>
				 <? } ?>
				
				<div class="">
					<div class="table-list">
						<div class="row">
								<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										Driver Payment Report
									</div>
									<div class="panel-body">
										<div class="table-responsive">
											<div class="alert alert-error" id="alert" style="display: none;" >
												<strong>Oh snap!</strong>

												<p></p>
											</div>
											
											<form name="search" action="" method="post" onSubmit="return checkvalid()">
												<div class="Posted-date mytrip-page">
													<input type="hidden" name="action" value="newsearch" />
													<h3>Select Duration</h3>
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
													<input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value="" required/>
													<input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value="" required/>
													<b><button class="driver-trip-btn" ><?=$langage_lbl['LBL_Search']; ?></button>
														<button type="button" onClick="redirectpaymentpage('driver_pay_report.php');" class="driver-trip-btn">Reset</button></b> 
													</span>
													
													<?php if(count($db_payment) > 0){ ?>
													<span><b><button type="button" class="driver-trip-btn" onclick="exportlist();">Export</button></b>
													</span>
													<?php } ?>
												</div>
											</form>
											
                      <form name="frmpayment" id="frmpayment" method="post" action="javascript:void(0);">
						<input type="hidden" id="actionpay" name="action" value="pay_driver">
						<input type="hidden" name="ePayDriver" id="ePayDriver" value="">
						<input type="hidden" name="prev_country" id="prev_country" value="<?php echo $iCountryCode; ?>">
						<input type="hidden" name="prev_start" id="prev_start" value="<?php echo $startDate; ?>">
						<input type="hidden" name="prev_end" id="prev_end" value="<?php echo $endDate; ?>">
 											<table class="table table-striped table-bordered table-hover" id="dataTables-example123" <?if($action == ""){?>style="display:none;"<?}else{?> style="display:;" <?}?>>
												<thead>
													<tr>
														<th>Driver Code</th>
														<th>Driver Name</th>
														<th>Driver Account Name</th>
														<th>Bank Name</th>
														<th>Account Number</th>
														<th>Sort Code</th>
														<th>Total Cash Payment</th>
														<th>Total Card Payment</th>
														<th>Amount to Transfer</th>
														<th>Driver Payment Status</th> 
														<th></th>                            
													</tr>
												</thead>
												<tbody>
													<?
                          if(count($db_payment) > 0){
                          	
							for($i=0;$i<count($db_payment);$i++)
                            {
                             
  													?>
  															<tr class="gradeA">
  															  <td><?=$db_payment[$i]['iDriverId'];?></td>
															  <td><?=$db_payment[$i]['dname'];?></td>
															  <td><?=($db_payment[$i]['vBankAccountHolderName'] != "")?$db_payment[$i]['vBankAccountHolderName']:'---';?></td>
															  <td><?=($db_payment[$i]['vBankName'] != "")?$db_payment[$i]['vBankName']:'---';?></td>
															  <td><?=($db_payment[$i]['vAccountNumber'] != "")?$db_payment[$i]['vAccountNumber']:'---';?></td>
															  <td><?=($db_payment[$i]['vBIC_SWIFT_Code'] != "")?$db_payment[$i]['vBIC_SWIFT_Code']:'---';?></td>
															  <td><?=$db_payment[$i]['cashPayment'];?></td>
															  <td><?=$db_payment[$i]['cardPayment'];?></td>
															  <td><?=$db_payment[$i]['transferAmount'];?></td>
															  
															  <td><?=$db_payment[$i]['eDriverPaymentStatus'];?></td>
															  <td>
																  <? 
																	  if($db_payment[$i]['eDriverPaymentStatus'] == 'Unsettelled'){
																  ?>
																	  <input class="validate[required]" type="checkbox" value="<?=$db_payment[$i]['iDriverId']?>" id="iTripId_<?=$db_payment[$i]['iDriverId']?>" name="iDriverId[]">
																  <?
																	  }
																  ?>
																</td>
  															</tr>
  													<? } ?>
                            <tr class="gradeA">
                              <td colspan="12" align="right"><div class="row">
        													<span style="margin:26px 13px 0 0;">
        														<a onclick="javascript:Paytodriver(); return false;" href="javascript:void(0);"><button class="btn btn-primary ">Mark As Settelled</button></a>
        													</span>
        											</div></td>
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
        </div>
       <!--END PAGE CONTENT -->
    </div>
    <!--END MAIN WRAPPER -->
	
	<form name="_submit_this" id="_submit_this" action="" >
		
	</form>
	

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
				 alert("From date should be lesser than To date.");
				 return false;
			 }
		 }
     
     function redirectpaymentpage(url)
     {   
        //$("#frmsearch").reset();
        // document.getElementById("action").value = '';
        // document.getElementById("frmsearch").reset();
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
		  $("#frmpayment").attr('action','');
          document.frmpayment.submit();
        }
        else{
          alert("Select record for Pay To Driver");
          return false;
        }
      }
      
		function exportlist(){
			$("#actionpay").val('export');
			$("#frmpayment").attr('action',"export_driver_pay_details.php");
			document.frmpayment.submit();
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
