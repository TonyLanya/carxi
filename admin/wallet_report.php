<?
include_once('../common.php');
#echo "str = ".$_SERVER['QUERY_STRING'];
$tbl_name 	= 'user_wallet';
if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$abc = 'admin,company';

$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


 //$generalobj->setRole($abc,$url);
$script='Wallet Report';


$action=(isset($_REQUEST['action'])?$_REQUEST['action']:'');
$ssql='';

 $sql = "select * from register_driver";
$db_driver_disp = $obj->MySQLSelect($sql);


//echo "<pre>"; print_r($db_driver); exit;
$sql = "select * from register_user";		
$db_rider_dis = $obj->MySQLSelect($sql);
	

if($action!='')
{

	$startDate=$_REQUEST['startDate'];	
	$endDate=$_REQUEST['endDate'];	
	$eUserType = $_REQUEST['eUserType'];	
	$eFor = $_REQUEST['eFor'];
	$Payment_type = $_REQUEST['Payment_type'];  

	if($eUserType == "Driver"){

		$iDriverId = $_REQUEST['iDriverId'];
		$iUserId = "";
		$user_available_balance = $generalobj->get_user_available_balance($iDriverId,$eUserType);
	}

	if($eUserType == "Rider"){

		$iUserId = $_REQUEST['iUserId'];
		$iDriverId = "";	
		 $user_available_balance = $generalobj->get_user_available_balance($iUserId,$eUserType);
	}

 	if($iDriverId!=''){
		$ssql.=" iUserId = '".$iDriverId."'";
	  }
	  if($iUserId!=''){
		$ssql.="iUserId = '".$iUserId."'";
	}
  
	if($startDate!=''){
		$ssql.=" AND Date(dDate) >='".$startDate."'";		 

	}
	if($endDate!=''){
		$ssql.=" AND Date(dDate) <='".$endDate."'";
	}    

	/*if($startDate!='' && $endDate!=''){

		if($eUserType == "Driver"){

		$iDriverId = $_REQUEST['iDriverId'];
		$user_available_balance = $generalobj->get_user_available_balance_admin($iDriverId ,$eUserType,$startDate,$endDate);	 

		}else{

			$iUserId = $_REQUEST['iUserId'];
			$user_available_balance = $generalobj->get_user_available_balance_admin($iUserId ,$eUserType,$startDate,$endDate);	
		}
		
	} */     

	if($eUserType){
		  $ssql.=" AND eUserType = '".$eUserType."'";

	}
	if($eFor!=''){
		 $ssql.=" AND eFor = '".$eFor."'";
			
	}
	  
	if($Payment_type!=''){
			$ssql.=" AND eType = '".$Payment_type."'";
	}

 	//$user_available_balance = $generalobj->get_user_available_balance_admin($iDriverId,$iUserId ,$eUserType,$startDate,$endDate,$eFor,$Payment_type);	


}

/*For Currency Entry in User Wallet*/
/* $sql = "SELECT * FROM currency WHERE eStatus = 'Active'";
$db_currency = $obj->MySQLSelect($sql);
for($i=0;$i<count($db_currency);$i++)
{
	if($db_currency[$i]['vName'] == 'GBP')
	{
		$fRatio_GBP = $db_currency[$i]['Ratio'];
	}
	
	if($db_currency[$i]['vName'] == 'USD')
	{
		$fRatio_USD = $db_currency[$i]['Ratio'];
	}
	
	if($db_currency[$i]['vName'] == 'EUR')
	{
		$fRatio_EUR = $db_currency[$i]['Ratio'];
	}		
} */
/*For Currency Entry in User Wallet End*/

if($_REQUEST['action'] == "paymentmember"){	
	
	$eUserType = $_REQUEST['eUserType'];
	if($eUserType == "Driver"){
		$iUserId = $_REQUEST['iDriverId'];

	}else{

		$iUserId = $_REQUEST['iUserId'];
	}
	
	#echo "fRatio_GBP = ".$fRatio_GBP." fRatio_USD = ".$fRatio_USD." fRatio_EUR = ".$fRatio_EUR; exit;

	$iBalance = $_REQUEST['iBalance'];
	$eFor = $_REQUEST['eFor'];
	$eType = $_REQUEST['eType'];
	$iTripId = 0;	
	$tDescription = ' Amount '.$_REQUEST['iBalance'].' debited from your account for withdrawl request';
	$ePaymentStatus = 'Unsettelled';
	$dDate =   Date('Y-m-d H:i:s');
	
	$generalobj->InsertIntoUserWallet($iUserId,$eUserType,$iBalance,$eType,$iTripId,$eFor,$tDescription,$ePaymentStatus,$dDate);
	
	/* $sql = "INSERT INTO `user_wallet` (`iUserId`,`eUserType`,`iBalance`,`eType`,`iTripId`, `eFor`, `tDescription`, `ePaymentStatus`, `dDate`, fRatio_GBP, fRatio_EUR, fRation_USD) VALUES ('" .$iUserId . "','".$eUserType."', '" . $iBalance . "','" . $eType . "', '" . $iTripId . "', '" . $eFor . "', '" .$tDescription. "', '" .$ePaymentStatus. "', '" .$dDate. "', '".$fRatio_GBP."', '".$fRatio_EUR."', '".$fRatio_USD."')";		
	$result = $obj->sql_query($sql); */			
	
	header("Location:wallet_report.php?".$_SERVER['QUERY_STRING']);
	exit;
}

if($_REQUEST['action'] == "addmoney"){

	
	$eUserType = $_REQUEST['eUserType'];

	if($eUserType == "Driver"){
		$iUserId = $_REQUEST['iDriverId'];

	}else{

		$iUserId = $_REQUEST['iUserId'];
	}	
	$iBalance = $_REQUEST['iBalance'];
	$eFor = $_REQUEST['eFor'];
	$eType = $_REQUEST['eType'];
	$iTripId = 0;	
	$tDescription = ' Amount '.$_REQUEST['iBalance'].' credited into your account from administrator';
	$ePaymentStatus = 'Unsettelled';
	$dDate =   Date('Y-m-d H:i:s');
	/*$sql = "INSERT INTO `user_wallet` (`iUserId`,`eUserType`,`iBalance`,`eType`,`iTripId`, `eFor`, `tDescription`, `ePaymentStatus`, `dDate`, fRatio_GBP, fRatio_EUR, fRation_USD) VALUES ('" . $iUserId . "','".$eUserType."', '" . $iBalance . "','" . $eType . "', '" . $iTripId . "', '" . $eFor . "', '" .$tDescription. "', '" .$ePaymentStatus. "', '" .$dDate. "', '".$fRatio_GBP."', '".$fRatio_EUR."', '".$fRatio_USD."')";
		
	$result = $obj->sql_query($sql);	*/	
	$generalobj->InsertIntoUserWallet($iUserId,$eUserType,$iBalance,$eType,$iTripId,$eFor,$tDescription,$ePaymentStatus,$dDate);	
	
	 header("Location:wallet_report.php?".$_SERVER['QUERY_STRING']);
   exit;
}

$sql = "SELECT * From user_wallet where ".$ssql ." ORDER BY iUserWalletId ASC";  
$db_result= $obj->MySQLSelect($sql);
//echo "<pre>"; print_r($db_result);
//exit;

//echo '<pre>'; print_R($db_result); echo '</pre>';//exit;
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


//$user_available_balance = $generalobj->get_user_available_balance_admin($iDriverId,$iUserId ,$eUserType,$startDate,$endDate,$eFor,$Payment_type);

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD-->
<head>
<meta charset="UTF-8" />
<title>
<?=$SITE_NAME?>
| User Wallet</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="keywords" />
<meta content="" name="description" />
<meta content="" name="author" />
<? include_once('global_files.php');?>
<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
</head>
<!-- END  HEAD-->
<!-- BEGIN BODY-->
<body class="padTop53">
<!-- MAIN WRAPPER -->
<div id="wrap">
  <? include_once('header.php'); ?>
  <? include_once('left_menu.php'); ?>
  <!--PAGE CONTENT -->
  <div id="content">
    <div class="inner">
      <div class="row">
      <div class="col-lg-12">
        <h2>User Wallet Report</h2>
        </div>
      </div>
      <hr />
      <div class="">
        <div class="table-list">
          <div class="row">
            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-heading driver-neww1 driver-neww2">
                <b>User Wallet Report</b>
                 </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <div class="alert alert-error" id="alert" style="display: none;" > <strong>Oh snap!</strong>
                      <p></p>
                    </div>
                    <form name="search" id="frmsearch" action="" method="get" onSubmit="return checkvalid()">
                      <input type="hidden" name="action" id="action" value="search" />
                      <div class="Posted-date mytrip-page mytrip-page-select mytrip-page-select1">
                        <input type="hidden" name="action" value="search" />
                        <h3>Search by Date</h3>
                        <span> <a onClick="return todayDate('dp4','dp5');">
                        <?=$langage_lbl['LBL_MYTRIP_Today']; ?>
                        </a> <a onClick="return yesterdayDate('dFDate','dTDate');">
                        <?=$langage_lbl['LBL_MYTRIP_Yesterday']; ?>
                        </a> <a onClick="return currentweekDate('dFDate','dTDate');">
                        <?=$langage_lbl['LBL_MYTRIP_Current_Week']; ?>
                        </a> <a onClick="return previousweekDate('dFDate','dTDate');">
                        <?=$langage_lbl['LBL_MYTRIP_Previous_Week']; ?>
                        </a> <a onClick="return currentmonthDate('dFDate','dTDate');">
                        <?=$langage_lbl['LBL_MYTRIP_Current_Month']; ?>
                        </a> <a onClick="return previousmonthDate('dFDate','dTDate');">
                        <?=$langage_lbl['LBL_MYTRIP_Previous Month']; ?>
                        </a> <a onClick="return currentyearDate('dFDate','dTDate');">
                        <?=$langage_lbl['LBL_MYTRIP_Current_Year']; ?>
                        </a> <a onClick="return previousyearDate('dFDate','dTDate');">
                        <?=$langage_lbl['LBL_MYTRIP_Previous_Year']; ?>
                        </a> </span> <span>
                        <input type="text" id="dp4" name="startDate" placeholder="From Date" class="form-control" value=""/>
                        <input type="text" id="dp5" name="endDate" placeholder="To Date" class="form-control" value=""/>
                        <select name="eUserType" id="eUserType" class="form-control input-sm driver-trip-detail-select" style="width:18%;display:table-row-group;" onChange="return show_hide_user_type(this.value);">
                          <option value="">Search By User type</option>
                          <option value="Driver" <?if($eUserType == "Driver"){?>selected <?}?> > <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> </option>
                          <option value="Rider" <?if($eUserType == "Rider"){?>selected <?}?>> <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?> </option>
                        </select>
                        <select name="iDriverId" id="sec_driver" class="form-control input-sm driver-trip-detail-select" style="width:18%;display:table-row-group;">
                          <option value="">Search By <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name</option>
                          <?for($j=0;$j<count($db_driver_disp);$j++){?>
                          <option value="<?=$db_driver_disp[$j]['iDriverId'];?>" <?if($iDriverId == $db_driver_disp[$j]['iDriverId']){?>selected <?}?>>
                          <?=$db_driver_disp[$j]['vName'];?>
                          <?=$db_driver_disp[$j]['vLastName'];?>
                          </option>
                          <? if($iDriverId == $db_driver_disp[$j]['iDriverId']){ 
														
																	$USERNAME = $db_driver_disp[$j]['vName']." ".$db_driver_disp[$j]['vLastName'];
																} }?>
                        </select>
                        <select name="iUserId"  id="sec_rider" class="form-control input-sm " style="width:18%; display:table-row-group;">
                          <option value="">Search By <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?> Name</option>
                          <?for($j=0;$j<count($db_rider_dis);$j++){?>
                          <option value="<?=$db_rider_dis[$j]['iUserId'];?>" <?if($iUserId == $db_rider_dis[$j]['iUserId']){?>selected <?}?>>
                          <?=$db_rider_dis[$j]['vName'];?>
                          <?=$db_rider_dis[$j]['vLastName'];?>
                          </option>
                          <? if($iUserId == $db_rider_dis[$j]['iUserId']){ 
														
																	$USERNAME = $db_rider_dis[$j]['vName']." ".$db_rider_dis[$j]['vLastName'];
																} }?>
                        </select>
                        <select name="Payment_type" id="Payment_type" class="form-control input-sm " style="width:18%;display:table-row-group;">
                          <option value="">Search By Payment type</option>
                          <option value="Credit" <?if($Payment_type == "Credit"){?>selected <?}?> >Credit</option>
                          <option value="Debit" <?if($Payment_type == "Debit"){?>selected <?}?> >Debit</option>
                        </select>
                        <select name="eFor" id="eFor" class="form-control input-sm " style="width:18%;display:table-row-group;">
                          <option value="">Search By Balance Type</option>
                          <option value="Deposit" <?if($eFor == "Deposit"){?>selected <?}?>>Deposit</option>
                          <option value="Booking" <?if($eFor == "Booking"){?>selected <?}?>>Booking</option>
                          <option value="Refund" <?if($eFor == "Refund"){?>selected <?}?>>Refund</option>
                          <option value="Withdrawl" <?if($eFor == "Withdrawl"){?>selected <?}?>>Withdrawl</option>
                          <option value="Charges" <?if($eFor == "Charges"){?>selected <?}?>>Charges</option>
                          <option value="Referrer"<?if($eFor == "Referrer"){?>selected <?}?>>Referrer</option>
                        </select>
                        <b>
                        <button class="driver-trip-btn" onclick = "return validate_checkusetype();" >
                        <?=$langage_lbl['LBL_Search']; ?>
                        </button>
                        <a href="wallet_report.php" class="add-btn driver-trip-btn">
                        <?=$langage_lbl['LBL_RESET']; ?>
                        </a> </b> </span> </div>
                    </form>
                    <form name="frmpayment" id="frmpayment" method="post" action="">
                      <input type="hidden" id="actionpayment" name="actionpayment" value="pay_driver">
                      <input type="hidden"  name="iTripId" id="iTripId" value="">
                      <input type="hidden"  name="ePayDriver" id="ePayDriver" value="">
                      <table class="table table-striped table-bordered table-hover" id="dataTables-example123" <?if($action == ""){?>style="display:none;"<?}else{?> style="display:;" <?}?>>
                        <thead>
                          <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th><?php echo $langage_lbl_admin['LBL_TRIP_NO'];?></th>
                            <th>Transaction Date</th>
                            <th>Balance Type</th>
                            <th>Type</th>
                            <th>Balance</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?
												  if(count($db_result) > 0){
												 $prevbalance = 0;
													
													for($i=0;$i<count($db_result);$i++)
													{
														//echo "<pre>"; print_r($db_result);
														if($db_result[$i]['eType'] == "Credit"){
														   $db_result[$i]['currentbal'] = $prevbalance+$db_result[$i]['iBalance'];
														}else{
														   $db_result[$i]['currentbal'] = $prevbalance-$db_result[$i]['iBalance'];
														}
														
														$prevbalance = $db_result[$i]['currentbal'];

														if($db_result[$i]['iTripId'] > 0){

															$sql_query ="SELECT * FROM `trips` WHERE iTripId =".$db_result[$i]['iTripId'];
															$db_result_trips = $obj->MySQLSelect($sql_query);
															$ride_number = $db_result_trips[0]['vRideNo'];

														}else{

															$ride_number = '--';
														}	?>
                          <tr class="gradeA">
                            <td><?=$db_result[$i]['tDescription'];?></td>
                            <!-- <td>$ <?=$db_result[$i]['iBalance'];?></td>-->
                            <td><?=$generalobj->trip_currency($db_result[$i]['iBalance']);?></td>
                            <td><?php echo $ride_number;?></td>
                            <td><?= date('d-m-Y',strtotime($db_result[$i]['dDate']));?></td>
                            <td><?php echo $db_result[$i]['eFor'];?></td>
                            <td><?php echo $db_result[$i]['eType'];?></td>
                            <!-- <td class="center">$ <?=$db_result[$i]['currentbal'];?></td>-->
                            <td><?=$generalobj->trip_currency($db_result[$i]['currentbal']);?></td>
                          </tr>
                          <?php	  }		  ?>
                          <tr class="gradeA">
                            <td colspan="6" align="right">Total Balance</td>
                            <!--<td rowspan="1" colspan="1" align="center" class="center">$ <?=$user_available_balance;?> </td> -->
                            <td rowspan="1" colspan="1" align="center" class="center"><?=$generalobj->trip_currency($user_available_balance);?></td>
                          </tr>
                          <? } else{?>
                          <tr class="gradeA">
                            <td colspan="12" style="text-align:center;"> No User Wallet Details Found.</td>
                          </tr>
                          <?}?>
                        </tbody>
                      </table>
                    </form>
                  </div>
                  <div class="singlerow-login-log wallet-report">
                  <span> <a href="javascript:void(0);" onClick="open_paymentmember();" class="add-btn">Payment To member</a> <a style="text-align: center;margin-left:10px;" href="javascript:void(0);" class="btn btn-danger" href="javascript:void(0);" data-toggle="modal" onclick="open_addmonery_popup();">ADD MONEY</a></span> </div>
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
<!--- start popup-->
<div class="col-lg-12">
  <div class="modal fade" id="uiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-content image-upload-1 popup-box1">
      <div class="upload-content" style="width:260px;">
        <div class="addusername"><b style="font-size:20px;"><?PHP echo  $USERNAME;?></b></div>
        <h4>
          <?=$langage_lbl['LBL_WITHDRAW_REQUEST'];?>
        </h4>
        <form class="form-horizontal" id="payment_member" method="POST" enctype="multipart/form-data" action="" name="payment_member">
          <input type="hidden" id="action" name="action" value="paymentmember">
          <input type="hidden"  name="eTransRequest" id="eTransRequest" value="">
          <input type="hidden"  name="eType" id="eType" value="Debit">
          <input type="hidden"  name="eFor" id="eFor" value="Withdrawl">
          <input type="hidden"  name="iDriverId" id="iDriverId" value="<?=$iDriverId;?>">
          <input type="hidden"  name="iUserId" id="iUserId" value="<?=$iUserId;?>">
          <input type="hidden"  name="eUserType" id="eUserType" value="<?=$eUserType;?>">
          <input type="hidden"  name="User_Available_Balance" id="User_Available_Balance" value="<?=$user_available_balance;?>">
          <div class="col-lg-13">
            <div class="input-group input-append" >
              <h5>
                <?=$langage_lbl['LBL_ENTER_AMOUNT']; ?>
              </h5>
              <input type="text" name="iBalance" id="iBalance" class="form-control iBalance" value="">
              <!-- <span class="input-group-addon add-on"><i class="icon-calendar"></i></span> -->
            </div>
          </div>
          <input type="button" onClick="check_payment_member();"  class="save" name="<?=$langage_lbl['LBL_save']; ?>" value="<?=$langage_lbl['LBL_Save']; ?>">
          <input type="button" class="cancel" data-dismiss="modal" name="<?=$langage_lbl['LBL_BTN_PROFILE_CANCEL_TRIP_TXT']; ?>" value="<?=$langage_lbl['LBL_BTN_PROFILE_CANCEL_TRIP_TXT']; ?>">
        </form>
        <div style="clear:both;"></div>
      </div>
    </div>
  </div>
</div>
<!--- end popup -->
<!--- start popup-->
<div class="col-lg-12">
  <div class="modal fade" id="Addmoney" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-content image-upload-1 popup-box1">
      <div class="upload-content" style="width:260px;">
        <div class="addusername"><b style=" font-size:20px;"><?PHP echo  $USERNAME;?></b></div>
        <h4>
          <?=$langage_lbl['LBL_ADD_MONEY'];?>
        </h4>
        <form class="form-horizontal" id="add_money_frm" method="POST" enctype="multipart/form-data" action="" name="add_money_frm">
          <input type="hidden" id="action" name="action" value="addmoney">
          <input type="hidden"  name="eTransRequest" id="eTransRequest" value="">
          <input type="hidden"  name="eType" id="eType" value="Credit">
          <input type="hidden"  name="eFor" id="eFor" value="Deposit">
          <input type="hidden"  name="iDriverId" id="iDriverId" value="<?=$iDriverId;?>">
          <input type="hidden"  name="iUserId" id="iUserId" value="<?=$iUserId;?>">
          <input type="hidden"  name="eUserType" id="eUserType" value="<?=$eUserType;?>">
          <input type="hidden"  name="User_Available_Balance" id="User_Available_Balance" value="<?=$user_available_balance;?>">
          <div class="col-lg-13">
            <div class="input-group input-append" >
              <h5>
                <?=$langage_lbl['LBL_ENTER_AMOUNT']; ?>
              </h5>
              <input type="text" name="iBalance" id="iBalance" class="form-control iBalance" value="">
              <!-- <span class="input-group-addon add-on"><i class="icon-calendar"></i></span> -->
            </div>
          </div>
          <input type="button" onClick="check_add_money();" class="save" name="<?=$langage_lbl['LBL_save']; ?>" value="<?=$langage_lbl['LBL_Save']; ?>">
          <input type="button" class="cancel" data-dismiss="modal" name="<?=$langage_lbl['LBL_BTN_PROFILE_CANCEL_TRIP_TXT']; ?>" value="<?=$langage_lbl['LBL_BTN_PROFILE_CANCEL_TRIP_TXT']; ?>">
        </form>
        <div style="clear:both;"></div>
      </div>
    </div>
  </div>
</div>
<!--- end popup -->
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
         	
         	
         	

			//$('#iDriverId').hide();
			var eusertype = $("#eUserType").val();
			if(eusertype == ""){
				$('.singlerow-login-log').hide();
			}else{
			$('.singlerow-login-log').show();
			
			}			
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
          document.frmbooking.submit();
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

		function validate_username(name){
			
			
			var request = $.ajax({
                 type: "POST",
                 url: 'ajax_user_wallet.php',
                 data: {name: name},
                 success: function (data)
                 {
					$('#iDriverId').show();
					
					$('#iDriverId').html(data);
                   
                 }
            });
			
		}		
		function validate_checkusetype(){		
			
			var eusertype = $("#eUserType").val();
			var username_driver = $("#sec_driver").val();
			//alert(eusertype);
			var username_rider = $("#sec_rider").val();
			//alert(username_rider);

			/*if(eusertype == "" && (username_driver == "" || username_rider == "" )){
				
				alert("Please Select usertype and user name");
				return false;
			}*/	

			if(eusertype == ""){

				alert("Please Select Usertype ");
				return false;

			}	
			if(eusertype == "Driver" && username_driver == "" ){

				alert("Please Select Driver name");
				return false;

			}	


			if(eusertype == "Rider" && username_rider == "" ){

				alert("Please Select Rider name");
				return false;

			}	
			
		}
		 function open_paymentmember(){	
			$('#uiModal').modal('show'); 		
		}
		function open_addmonery_popup(){
			$('#Addmoney').modal('show'); 
			}
		
		 function check_payment_member(){		
			var maxamount = document.getElementById("User_Available_Balance").value;		
			var requestamount = document.getElementById("iBalance").value;		
			if(requestamount == ''){
				alert("Please Enter Withdraw Amount");
				return false;
			}
			if(parseFloat(requestamount) > parseFloat(maxamount)){
				alert("Please Enter Withdraw Amount Less Than " + maxamount );
				return false;
			}
			   
				document.payment_member.submit();						
		} 
		
		function check_add_money(){
			
			document.add_money_frm.submit();
			}
     
		 $(".iBalance").keydown(function (e) {
			        // Allow: backspace, delete, tab, escape, enter and .
			        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			             // Allow: Ctrl+A
			            (e.keyCode == 65 && e.ctrlKey === true) ||
			             // Allow: Ctrl+C
			            (e.keyCode == 67 && e.ctrlKey === true) ||
			             // Allow: Ctrl+X
			            (e.keyCode == 88 && e.ctrlKey === true) ||
			             // Allow: home, end, left, right
			            (e.keyCode >= 35 && e.keyCode <= 39)) {
			                 // let it happen, don't do anything
			                 return;
			        }
			        // Ensure that it is a number and stop the keypress
			        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			            e.preventDefault();
			        }
			    });
     /*$('#dataTables-example').DataTable( {
        paging: false
      } );*/

      function show_hide_user_type(username){      	
	      	if(username == "Driver"){


	      		$('#sec_driver').show();
	      		$('#sec_rider').hide();

	      	}else if(username == "Rider"){

	      		$('#sec_rider').show();
	      		$('#sec_driver').hide();

	      	}else{
	      		$('#sec_driver').hide();
	      		$('#sec_rider').hide();

	      	}

      }

     
    </script>
<?php 

	if($action!='')
	{ ?>
<script>

		usertype = document.getElementById('eUserType').value;			
			if(usertype == "Driver"){
				$('#sec_driver').show();
	      		

			}else{

				$('#sec_rider').hide();
			}
		show_hide_user_type(usertype)


		</script>
<?php }else{ ?>
<script>
				$('#sec_rider').hide();
         		$('#sec_driver').hide();
        	 </script>
<?php }

    ?>
</body>
<!-- END BODY-->
</html>