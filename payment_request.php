<?
include_once('common.php');
#echo"<pre>";print_r($_SESSION);exit;
$sql="SELECT `vCurrencySymbol` FROM `language_master` WHERE `vCode`='".$_SESSION['sess_lang']."'";
$cur_code = $obj->MySQLSelect($sql);
$curr_code=$cur_code[0]['vCurrencySymbol'];
$var_msg = isset($_REQUEST["var_msg"]) ? $_REQUEST["var_msg"] : '';

if($_SESSION['sess_user']== "driver")
{
  $sql = "SELECT * FROM register_".$_SESSION['sess_user']." WHERE iDriverId='".$_SESSION['sess_iUserId']."'";
  $db_booking = $obj->MySQLSelect($sql);

  $sql = "SELECT fThresholdAmount, Ratio, vName, vSymbol FROM currency WHERE vName='".$db_booking[0]['vCurrencyDriver']."'";
  $db_curr_ratio = $obj->MySQLSelect($sql);
}
else
{
  $sql = "SELECT * FROM register_".$_SESSION['sess_user']." WHERE iUserId='".$_SESSION['sess_iUserId']."'";
  $db_booking = $obj->MySQLSelect($sql);  

  $sql = "SELECT fThresholdAmount, Ratio, vName, vSymbol FROM currency WHERE vName='".$db_booking[0]['vCurrencyPassenger']."'";
  $db_curr_ratio = $obj->MySQLSelect($sql);
}
$tripcursymbol=$db_curr_ratio[0]['vSymbol'];
$tripcur=$db_curr_ratio[0]['Ratio'];
$tripcurname=$db_curr_ratio[0]['vName'];
$tripcurthholsamt=$db_curr_ratio[0]['fThresholdAmount'];

$tbl_name 	= 'register_driver';
$script="Payment Request";
 $generalobj->check_member_login();
 $abc = 'admin,driver,company';
 $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 $generalobj->setRole($abc,$url);
$action=(isset($_REQUEST['action'])?$_REQUEST['action']:'');
$ssql='';

$paidtype=(isset($_REQUEST['paidStatus']) && $_REQUEST['paidStatus'] !='')?$_REQUEST['paidStatus']:$langage_lbl['LBL_MYEARNING_RECENT_RIDE'];

//$sql = "SELECT d.vName,d.vLastName,sum(t.iFare),count(t.iDriverId) from register_driver d left join trips t on d.iDriverId = t.iDriverId where d.iDriverId = '29' "

/*  $sql = "SELECT u.vName, u.vLastName,t.tEndDate, d.vAvgRating, t.iFare, d.iDriverId,t.fRatioDriver,t.vCurrencyDriver, t.tSaddress, d.vName AS name, d.vLastName AS lname,t.eCarType,t.iTripId,vt.vVehicleType
FROM register_driver d
RIGHT JOIN trips t ON d.iDriverId = t.iDriverId
LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId
LEFT JOIN  register_user u ON t.iUserId = u.iUserId
WHERE d.iDriverId = '".$_SESSION['sess_iUserId']."'".$ssql." ORDER BY t.iTripId DESC"; */
$class1 = $class2 = $class3 = '';
if($paidtype == $langage_lbl['LBL_PAYMENT_REQUEST_PAYMENT']) {
	$class2 = 'active';
	$ssql = " AND t.ePayment_request = 'Yes' AND t.eDriverPaymentStatus = 'Unsettelled'";
}else if($paidtype == $langage_lbl['LBL_MYEARNING_PAID_TRIPS']) {
	$class3 = 'active';
	$ssql = " AND t.ePayment_request = 'Yes' AND t.eDriverPaymentStatus = 'Settelled'";
}else {
	$class1 = 'active';
	$ssql = " AND t.ePayment_request = 'No' AND t.eDriverPaymentStatus = 'Unsettelled' ";
}
$sql = "SELECT t.*, t.iTripId,t.tSaddress, t.tEndDate,t.tDaddress,t.iFare,t.fCommision,t.ePayment_request FROM trips t
WHERE t.iDriverId = '".$_SESSION['sess_iUserId']."'".$ssql." AND t.iActive='Finished' ORDER BY t.iTripId DESC";
$db_dtrip = $obj->MySQLSelect($sql);
#echo "<pre>";print_r($db_dtrip);exit;

$type="Available";

?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_MYEARNING_PAYMENT_TXT']; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");
	$rtls = "";
	if($lang_ltr == "yes") {
		$rtls = "dir='rtl'";
	}
	?>
   
    <!-- <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" /> -->
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
		<div class="page-contant">
			<div class="page-contant-inner">
			  	<h2 class="header-page"><?=$langage_lbl['LBL_MY_EARN']; ?></h2>
		  		<!-- trips page -->
			  	<!-- <div class="trips-page"> -->
                <form name="frmreview" id="frmreview" method="post" action="">
					<input type="hidden" name="paidStatus" value="" id="paidStatus">
					<input type="hidden" name="action" value="" id="action">
					<input type="hidden" name="iRatingId" value="" id="iRatingId">
				</form>
				
				<div class="trips-table">
					<div class="payment-tabs">
						<ul>
							<li><a href="javascript:void(0);" onClick="getReview('<?=$langage_lbl['LBL_MYEARNING_RECENT_RIDE']; ?>');" class="<?=$class1; ?>"><?=$langage_lbl['LBL_MYEARNING_RECENT_RIDE']; ?></a></li>
							<li><a href="javascript:void(0);" onClick="getReview('<?=$langage_lbl['LBL_PAYMENT_REQUEST_PAYMENT']; ?>');" class="<?=$class2; ?>"><?=$langage_lbl['LBL_PAYMENT_REQUEST_PAYMENT']; ?></a></li>
							<li><a href="javascript:void(0);" onClick="getReview('<?=$langage_lbl['LBL_MYEARNING_PAID_TRIPS']; ?>');" class="<?=$class3; ?>"><?=$langage_lbl['LBL_MYEARNING_PAID_TRIPS']; ?></a></li>
						</ul>
					</div>
			      		<div class="trips-table-inner">
                        <div class="driver-trip-table">
			      			<form  name="frmbooking" id="frmbooking" method="post" action="payment_request_a.php">
								<input type="hidden" id="type" name="type" value="<?=$type;?>">
								<input type="hidden" id="action" name="action" value="send_equest">
								<input type="hidden"  name="eTransRequest" id="eTransRequest" value="">
								<input type="hidden"  name="iBookingId" id="iBookingId" value="">
			        			<?php 
									 if ($_REQUEST['success']==1) {?>
										<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> 
											<?= $var_msg ?>
										</div>
										<?}else if($_REQUEST['success']==2){ ?>
										<div class="alert alert-danger alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
											"Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
										</div>
										<?php 
										} else if(isset($_REQUEST['success']) && $_REQUEST['success']==0){?>
										<div class="alert alert-danger alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> 
											<?= $var_msg ?>
										</div>
										<? }
									?>
			        			<table width="100%" border="0" cellpadding="0" cellspacing="0" id="dataTables-example" <?php echo $rtls; ?>>
			          				<thead>
										<tr>
											<th><?=$langage_lbl['LBL_MYEARNING_ID']; ?></th>
											<th><?=$langage_lbl['LBL_TRIP_DATE_TXT']; ?></th>
											<th><?=$langage_lbl['LBL_FARE_TXT']; ?></th>
											<th><?=$langage_lbl['LBL_Commission']; ?></th>
											<th><?=$langage_lbl['LBL_MYEARNING_PAYMENT_TXT']; ?></th>
											<th><?=$langage_lbl['LBL_MYEARNING_INVOICE']; ?></th>
											<th><?=$langage_lbl['LBL_MYEARNING_REQUEST_PAYMENT']; ?></th>
										</tr>
									</thead>
									<tbody>
										<? $fareTotal = $commTotal = $payTotal = 0;
										  for($i=0;$i<count($db_dtrip);$i++)
										  {
												$db_dtrip[$i]['iTripId'] = base64_encode(base64_encode($db_dtrip[$i]['iTripId']));
												$pickup = $db_dtrip[$i]['tSaddress'];
												$Endup = $db_dtrip[$i]['tDaddress'];
												$fare = $generalobj->trip_currency_payment($db_dtrip[$i]['iFare'],$db_dtrip[$i]['fRatio_'.$tripcurname]);
												$Commission = $generalobj->trip_currency_payment($db_dtrip[$i]['fCommision'],$db_dtrip[$i]['fRatio_'.$tripcurname]);
												$payment=$fare-$Commission;
												$name = $db_dtrip[$i]['vName'].' '.$db_dtrip[$i]['vLastName'];
												$vstatus = $db_dtrip[$i]['ePayment_request'];
												?>
												<tr class="gradeA">
													<td ><?=$db_dtrip[$i]['vRideNo'];?></td>
													<td ><?= date('d M Y',strtotime($db_dtrip[$i]['tEndDate']));?></td>
													<td ><?= $tripcursymbol;?><?=$fare; $fareTotal += $fare; ?></td>
													<td><?= $tripcursymbol;?><?=$Commission; $commTotal += $Commission; ?></td>
													<td class="center"><?= $tripcursymbol;?><?=$payment; $payTotal += $payment; ?></td>
													<td class="center"><a href="invoice.php?iTripId=<?php echo $db_dtrip[$i]['iTripId']?>"><img src="assets/img/invoice1.png" ></a>
														<?/* if($vstatus=='Yes')
															{
																echo $langage_lbl['LBL_TRANSFER_REQUEST_SEND'];
															}
															else
															{
																echo $langage_lbl['LBL_TRANSFER_REQUEST_YET_PANDING']; 
															}
														*/?>
													</td>
													<td>
														<div class="checkbox-n">
														<input id="payment_<?=$db_dtrip[$i]['iTripId'];?>" name="iTripId[]" value="<?=$db_dtrip[$i]['iTripId'];?>" type="checkbox" <? if($db_dtrip[$i]['ePayment_request']=='Yes'){?> checked="checked" disabled <? }?> >
														<label for="payment_<?=$db_dtrip[$i]['iTripId'];?>"></label></div>
                                                    </td>
												</tr>
										  <? } ?>
												
									</tbody>
									<tfoot>
									<tr class="last_row_record">
										<td></td><td></td><td class="last_record_row"><?= $tripcursymbol;?> <?php echo /* $curr_code." ". */$fareTotal; ?></td><td class="last_record_row midddle_rw"><?= $tripcursymbol;?> <?php echo /* $curr_code." ". */$commTotal; ?></td><td class="last_record_row"> <?= $tripcursymbol;?><?php echo /* $curr_code." ". */$payTotal; ?></td><td></td><td></td>
									</tr>
									</tfoot>
		        				</table>
		        				<!--table>
									<tr class="">
										<td></td><td></td><td><?php echo $fareTotal; ?></td><td><?php echo $commTotal; ?></td><td><?php echo $payTotal; ?></td><td></td><td></td>
									</tr>
								</table-->
		      				</form>
		      			</div>
					<?php if($paidtype == $langage_lbl['LBL_MYEARNING_RECENT_RIDE']) { ?>
						<div class="singlerow-login-log"><a href="javascript:void(0);" onClick="javascript:check_skills_edit(); return false;"><?=$langage_lbl['LBL_Send_transfer_Request']; ?></a></div>
                        <div class="your-requestd"><b><?=$langage_lbl['LBL_THRESHOLDAMOUNT_NOTE1']; ?></b> <?=$langage_lbl['LBL_THRESHOLDAMOUNT_NOTE2']; ?><?='  '.$tripcursymbol.' ' . number_format($tripcurthholsamt,2, '.', ''); ?></div>
					<?php } ?>
					</div>
			    	</div>
			    	<? //if(SITE_TYPE=="Demo"){?>
				   <!-- <div class="record-feature"> 
				    	<span><strong>“Edit / Delete Record Feature”</strong> has been disabled on the Demo Admin Version you are viewing now.
				      	This feature will be enabled in the main product we will provide you.</span> 
			      	</div>	-->
				      <?php //}?>
			  	<!-- </div> -->
			  <!-- -->
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
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript">
     	function getCheckCount(frmbooking)
		{
			var x=0;
			var threasold_value=0;
			for(i=0;i < frmbooking.elements.length;i++)
			{	if ( frmbooking.elements[i].checked == true && frmbooking.elements[i].disabled == false) 
					{x++;}
			}
			return x;
		}
	
	
		function check_skills_edit(){
			y = getCheckCount(document.frmbooking);
			if(y>0)
			{  
			 	$("#eTransRequest").val('Yes');
			    document.frmbooking.submit();
			}
			else{
			  	alert("Select Ride for send transfer request")
			  	return false;
		  	}
		}
		$(document).ready(function () {
         	$('#dataTables-example').dataTable({
				fixedHeader: {
					footer: true
				},
				"aaSorting": []});
         });
    </script>
    
        
    <script type="text/javascript">
    $(document).ready(function(){
        $("[name='dataTables-example_length']").each(function(){
            $(this).wrap("<em class='select-wrapper'></em>");
            $(this).after("<em class='holder'></em>");
        });
        $("[name='dataTables-example_length']").change(function(){
            var selectedOption = $(this).find(":selected").text();
            $(this).next(".holder").text(selectedOption);
        }).trigger('change');
    })
	function getReview(type)
	{
		$('#paidStatus').val(type);
	//	window.location.href = "payment_request.php?paidStatus="+type;
		document.frmreview.submit();	
	}
</script>
    <!-- End: Footer Script -->
</body>
</html>

