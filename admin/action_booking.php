<?php
error_reporting(0);
include_once('../include_taxi_webservices.php');
include_once(TPATH_CLASS.'configuration.php');

require_once('../assets/libraries/stripe/config.php');
require_once('../assets/libraries/stripe/stripe-php-2.1.4/lib/Stripe.php');
require_once('../assets/libraries/pubnub/autoloader.php');
include_once(TPATH_CLASS .'Imagecrop.class.php');
include_once(TPATH_CLASS .'twilio/Services/Twilio.php');
include_once('../generalFunctions.php');
include_once('../send_invoice_receipt.php');

//check if no driver is selected then choose nearst one
if(!isset($_POST['iDriverId']) || $_POST['iDriverId'] == '')
{
$drivId = isset($_POST['drivId']) ? $_POST['drivId'] : '';
$_POST['iDriverId'] = $drivId;
}
// echo '<pre>';
// print_r($_POST);
// die();
include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();
$tbl_name = 'register_user';
$tbl_name1 = 'cab_booking';

$vName = isset($_POST['vName']) ? $_POST['vName'] : '';
$vLastName = isset($_POST['vLastName']) ? $_POST['vLastName'] : '';
$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
$vPhoneCode = isset($_POST['vPhoneCode']) ? $_POST['vPhoneCode'] : '';
$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
$vCity = isset($_POST['vCity']) ? $_POST['vCity'] : '';
$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : 'Inactive';
$vInviteCode = isset($_POST['vInviteCode']) ? $_POST['vInviteCode'] : '';
$vImgName = isset($_POST['vImgName']) ? $_POST['vImgName'] : '';
$vCurrencyPassenger = isset($_POST['vCurrencyPassenger']) ? $_POST['vCurrencyPassenger'] : '';
$vPass = $generalobj->encrypt($vPassword);

$sql = "select * from currency where eStatus='Active' AND eDefault='Yes'";
$db_country = $obj->MySQLSelect($sql);

$sql1 = "select * from language_master where eStatus='Active' AND eDefault='Yes'";
$db_language= $obj->MySQLSelect($sql1);

$sql="select cn.vCountry,cn.vPhoneCode from country cn inner join
configurations c on c.vValue=cn.vCountryCode where c.vName='DEFAULT_COUNTRY_CODE_WEB'";
$db_con = $obj->MySQLSelect($sql);

if (isset($_POST['submit'])) {

	$pickups = explode(',', $_POST['from_lat_long']); // from latitude-Longitude
	$dropoff = explode(',', $_POST['to_lat_long']); // To latitude-Longitude
    $vSourceLatitude = isset($pickups[0]) ? trim(str_replace("(","",$pickups[0])) : '';
    $vSourceLongitude = isset($pickups[1]) ? trim(str_replace(")","",$pickups[1])) : '';
	$vDestLatitude = isset($dropoff[0]) ? trim(str_replace("(","",$dropoff[0])) : '';
    $vDestLongitude = isset($dropoff[1]) ? trim(str_replace(")","",$dropoff[1])) : '';
	$vDistance = isset($_POST['distance']) ? (round(number_format($_POST['distance']/1000))) : '';
	$vDuration = isset($_POST['duration']) ? (round(number_format($_POST['duration']/60))) : '';
	$iUserId = isset($_POST['iUserId']) ? $_POST['iUserId'] : '';
	 $iDriverId = isset($_POST['iDriverId']) ? $_POST['iDriverId'] : $_POST['drivId'];
	$dBooking_date = isset($_POST['dBooking_date']) ? $_POST['dBooking_date'] : '';
	$vSourceAddresss = isset($_POST['vSourceAddresss']) ? $_POST['vSourceAddresss'] : '';
	$tDestAddress = isset($_POST['tDestAddress']) ? $_POST['tDestAddress'] : '';
	$eAutoAssign = isset($_POST['eAutoAssign']) ? $_POST['eAutoAssign'] : 'No';
	$eStatus1 = 'Assign';
	$iVehicleTypeId = isset($_POST['iVehicleTypeId']) ? $_POST['iVehicleTypeId'] : '';
	$iCabBookingId = isset($_POST['iCabBookingId']) ? $_POST['iCabBookingId'] : '';

	$SQL1 = "SELECT vName,vLastName,vEmail,iUserId FROM $tbl_name WHERE vEmail = '$vEmail'";
  if($vEmail == '')
  {
  	$SQL1 = "SELECT vName,vLastName,vEmail,iUserId FROM $tbl_name WHERE vPhone = '$vPhone'";
  }
  else {
    $SQL1 = "SELECT vName,vLastName,vEmail,iUserId FROM $tbl_name WHERE vEmail = '$vEmail'";
  }
	$email_exist = $obj->MySQLSelect($SQL1);
	$iUserId = $email_exist[0]['iUserId'];
    if(count($email_exist) == 0 && $iCabBookingId == "") {
        $q = "INSERT INTO ";
        $where = '';
        $query = $q . " `" . $tbl_name . "` SET
                `vName` = '" . $vName . "',
                `vLastName` = '" . $vLastName . "',
                `vEmail` = '" . $vEmail . "',
                `vPassword` = 'DShj8tGU',
                `vPhone` = '" . $vPhone . "',
                `vCountry` = '" . $vCountry . "',
                `vPhoneCode` = '" . $vPhoneCode . "',
                `eStatus` = '" . $eStatus . "',
                `vImgName` = '" . $vImgName . "',
                 `vCurrencyPassenger` = '" . $db_country[0]['vName'] . "',
                `vLang` = '" . $db_language[0]['vCode']. "',
                `vInviteCode` = '" . $vInviteCode . "'";
        $obj->sql_query($query);
		$iUserId = mysql_insert_id();
    }
    //if($iUserId == "" || $iUserId == "0" || $iDriverId == "" || $iDriverId == "0" || $vSourceAddresss == "" || $tDestAddress == ""){
    if(($iUserId == "" || $iUserId == "0" || $vSourceAddresss == "") && $APP_TYPE != "UberX"){
       $var_msg = "Booking details is not add/update because missing information";
       if($iCabBookingId == ""){
           header("location:add_booking.php?success=0&var_msg=".$var_msg); exit;
       }else{
       header("location:add_booking.php?booking_id=".$iCabBookingId."success=0&var_msg=".$var_msg); exit;
       }
    }else if(($iUserId == "" || $iUserId == "0" || $vSourceAddresss == "") && $APP_TYPE == "UberX"){
		$var_msg = "Booking details is not add/update because missing information";
       if($iCabBookingId == ""){
           header("location:add_booking.php?success=0&var_msg=".$var_msg); exit;
       }else{
       header("location:add_booking.php?booking_id=".$iCabBookingId."success=0&var_msg=".$var_msg); exit;
       }
	}
    //if($_POST['rideType'] == "manual"){
		$rand_num=rand ( 10000000 , 99999999 );
		$q1 = "INSERT INTO ";
		$whr = ",`vBookingNo`='".$rand_num."'";
		$edit = "";
		if($iCabBookingId != "" && $iCabBookingId != '0') {
			$q1 = "UPDATE ";
			$whr = " WHERE `iCabBookingId` = '" . $iCabBookingId . "'";
			$edit = '1';
		}
		$query1 = $q1 . " `" . $tbl_name1 . "` SET
                `iUserId` = '" . $iUserId . "',
                `iDriverId` = '" . $iDriverId . "',
                `vSourceLatitude` = '" . $vSourceLatitude . "',
                `vSourceLongitude` = '" . $vSourceLongitude . "',
                `vDestLatitude` = '" . $vDestLatitude . "',
                `vDestLongitude` = '" . $vDestLongitude . "',
				`vDistance` = '" . $vDistance . "',
				`vDuration` = '" . $vDuration . "',
                `dBooking_date` = '" . $dBooking_date . "',
                `vSourceAddresss` = '" . $vSourceAddresss . "',
                `tDestAddress` = '" . $tDestAddress . "',
                `eStatus`='" . $eStatus1 . "',
                `eAutoAssign`='" . $eAutoAssign . "',
				`eCancelBy`='',
                `iVehicleTypeId` = '" . $iVehicleTypeId . "'".$whr;

        $obj->sql_query($query1);
		$sql="select vName,vLastName,vEmail,iDriverVehicleId,vPhone,vcode,iGcmRegId,eDeviceType from register_driver where iDriverId=".$iDriverId;
		$driver_db=$obj->MySQLSelect($sql);
		//echo "<pre>";print_r($driver_db);
      //print_r($driver_db);die;
		$Data1['vRider']=$email_exist[0]['vName']." ".$email_exist[0]['vLastName'];
		$Data1['vDriver']=$driver_db[0]['vName']." ".$driver_db[0]['vLastName'];
		$Data1['vDriverMail']=$driver_db[0]['vEmail'];
		$Data1['vRiderMail']=$email_exist[0]['vEmail'];
		$Data1['vSourceAddresss']=$vSourceAddresss;
		$Data1['tDestAddress']=$tDestAddress;
		$Data1['dBookingdate']=$dBooking_date;
		$Data1['vBookingNo']=$rand_num;

		if($edit == '1')
		{
			$sql="select vBookingNo from cab_booking where `iCabBookingId` = '" . $iCabBookingId . "'";
			$cab_id=$obj->MySQLSelect($sql);
			$Data1['vBookingNo']=$cab_id[0]['vBookingNo'];
		}
		//$Data1['vDistance']=$vDistance;
		//$Data1['vDuration']=$vDuration;

		//echo "<pre>";print_r($Data1);exit;
		$return = $generalobj->send_email_user("MANUAL_TAXI_DISPATCH_DRIVER",$Data1);
		$return1 = $generalobj->send_email_user("MANUAL_TAXI_DISPATCH_RIDER",$Data1);

		// Start Send SMS
		$query = "SELECT * FROM driver_vehicle WHERE iDriverVehicleId=".$driver_db[0]['iDriverVehicleId'];
        $db_driver_vehicles = $obj->MySQLSelect($query);

		$vPhone = $vPhone;
        $vcode = $db_con[0]['vPhoneCode'];
        $Booking_Date = @date('d-m-Y',strtotime($dBooking_date));
        $Booking_Time = @date('H:i:s',strtotime($dBooking_date));

        $query = "SELECT * FROM register_user WHERE iUserId=".$iUserId;
        $db_user= $obj->MySQLSelect($query);
        $Pass_name = $vName.' '.$vLastName;
		$vcode = $db_user[0]['vPhoneCode'];
		$maildata['DRIVER_NAME'] = $Data1['vDriver'];
        $maildata['PLATE_NUMBER'] = $db_driver_vehicles[0]['vLicencePlate'];
        $maildata['BOOKING_DATE'] = $Booking_Date;
        $maildata['BOOKING_TIME'] =  $Booking_Time;
        $maildata['BOOKING_NUMBER'] = $Data1['vBookingNo'];
		//Send sms to User
		$message_layout = $generalobj->send_messages_user("USER_SEND_MESSAGE",$maildata);
        $return4 = $generalobj->sendUserSMS($vPhone,$vcode,$message_layout,"");
		//Send sms to Driver
		$vPhone = $driver_db[0]['vPhone'];
         $vcode1 = $driver_db[0]['vcode'];

        $maildata1['PASSENGER_NAME'] = $Pass_name;
        $maildata1['BOOKING_DATE'] = $Booking_Date;
        $maildata1['BOOKING_TIME'] =  $Booking_Time;
        $maildata1['BOOKING_NUMBER'] = $Data1['vBookingNo'];

		$message_layout = $generalobj->send_messages_user("DRIVER_SEND_MESSAGE",$maildata1);
        $return5 = $generalobj->sendUserSMS($vPhone,$vcode1,$message_layout,"");


$success = 1;
//$return && $return1
		if($success){

			$var_msg = "Booking Has Been Added Successfully.";

      /* testing function */


        $driver_id_auto = $iDriverId;
        $message        = isset($_REQUEST["message"]) ? $_REQUEST["message"] : '';
        $passengerId    = $iUserId;
        $cashPayment    =isset($_REQUEST["CashPayment"]) ? $_REQUEST["CashPayment"] : '';
        $selectedCarTypeID    = isset($_REQUEST["SelectedCarTypeID"]) ? $_REQUEST["SelectedCarTypeID"] : '';

        $PickUpLatitude    = $vSourceLatitude;
        $PickUpLongitude    = $vSourceLongitude;

        $DestLatitude    = $vDestLatitude;
        $DestLongitude    = $vDestLongitude;
        $DestAddress    = 'Mohali, Punjab, India';
        $promoCode    =isset($_REQUEST["PromoCode"]) ? $_REQUEST["PromoCode"] : '';
        $eType    = 'Ride';
        $iPackageTypeId    = isset($_REQUEST["iPackageTypeId"]) ? $_REQUEST["iPackageTypeId"] : '';
        $vReceiverName    = get_value('register_driver', 'vName', 'iDriverId',$iDriverId,'','true').' '.get_value('register_driver', 'vLastName', 'iDriverId',$iDriverId,'','true');
        $vReceiverMobile    = get_value('register_driver', 'vPhone', 'iDriverId',$iDriverId,'','true');
        $tPickUpIns    =isset($_REQUEST["tPickUpIns"]) ? $_REQUEST["tPickUpIns"] : '';
        $tDeliveryIns    =isset($_REQUEST["tDeliveryIns"]) ? $_REQUEST["tDeliveryIns"] : '';
        $tPackageDetails    =isset($_REQUEST["tPackageDetails"]) ? $_REQUEST["tPackageDetails"] : '';
        $vDeviceToken    = get_value('register_user', 'iGcmRegId', 'iUserId',$passengerId,'','true');
        $iUserPetId    =isset($_REQUEST["iUserPetId"]) ? $_REQUEST["iUserPetId"] : '0';

        $trip_status  = "Requesting";

        checkmemberemailphoneverification($passengerId,"Passenger");

        //$alertMsg = "Passenger is waiting for you";
        /*$vLangCode=get_value('register_driver', 'vLang', 'iDriverId',$driver_id_auto,'','true');
        if($vLangCode == "" || $vLangCode == NULL){
           $vLangCode = get_value('language_master', 'vCode', 'eDefault','Yes','','true');
        }*/
        $vLangCode = get_value('language_master', 'vCode', 'eDefault','Yes','','true');

        $languageLabelsArr= getLanguageLabelsArr($vLangCode,"1");
        $userwaitinglabel = $languageLabelsArr['LBL_TRIP_USER_WAITING'];
        $alertMsg = $userwaitinglabel;

        $Data = getOnlineDriverArr($PickUpLatitude,$PickUpLongitude);

        $iGcmRegId=get_value('register_user', 'iGcmRegId', 'iUserId',$passengerId,'','true');

        if($vDeviceToken != "" && $vDeviceToken != $iGcmRegId){
          $returnArr['Action'] = "0";
          $returnArr['message'] = "SESSION_OUT";
          echo json_encode($returnArr);
          exit;
        }

        $passengerFName = get_value('register_user', 'vName', 'iUserId',$passengerId,'','true');
        $passengerLName = get_value('register_user', 'vLastName', 'iUserId',$passengerId,'','true');
        $final_message['Message'] = "CabRequested";
        $final_message['sourceLatitude'] = strval($PickUpLatitude);
        $final_message['sourceLongitude'] = strval($PickUpLongitude);
        $final_message['PassengerId'] = strval($passengerId);
        $final_message['PName'] = $passengerFName. " " .$passengerLName;
        $final_message['PPicName'] = get_value('register_user', 'vImgName', 'iUserId',$passengerId,'','true');
        $final_message['PFId'] = get_value('register_user', 'vFbId', 'iUserId',$passengerId,'','true');
        $final_message['PRating'] = get_value('register_user', 'vAvgRating', 'iUserId',$passengerId,'','true');
        $final_message['PPhone'] = get_value('register_user', 'vPhone', 'iUserId',$passengerId,'','true');
        $final_message['PPhoneC'] = get_value('register_user', 'vPhoneCode', 'iUserId',$passengerId,'','true');
        $final_message['REQUEST_TYPE'] = $eType;
        $final_message['PACKAGE_TYPE'] = $eType == "Deliver"?get_value('package_type', 'vName', 'iPackageTypeId',$iPackageTypeId,'','true'):'';
        $final_message['destLatitude'] = strval($DestLatitude);
        $final_message['destLongitude'] = strval($DestLongitude);
        $final_message['MsgCode'] = strval(mt_rand(1000, 9999));
        $final_message['admin_status'] = '1';
        $msg_encode  = json_encode($final_message,JSON_UNESCAPED_UNICODE);



        $ePickStatus=get_value('vehicle_type', 'ePickStatus', 'iVehicleTypeId',$selectedCarTypeID,'','true');
        $eNightStatus=get_value('vehicle_type', 'eNightStatus', 'iVehicleTypeId',$selectedCarTypeID,'','true');

        $fPickUpPrice = 1;
        $fNightPrice = 1;

        $data_surgePrice = checkSurgePrice($selectedCarTypeID,"");

        if($data_surgePrice['Action'] == "0"){
          if($data_surgePrice['message'] == "LBL_PICK_SURGE_NOTE"){
            $fPickUpPrice=$data_surgePrice['SurgePriceValue'];
          }else{
            $fNightPrice=$data_surgePrice['SurgePriceValue'];
          }
        }

        $str_date = @date('Y-m-d H:i:s', strtotime('-1440 minutes'));
            $sql = "SELECT iGcmRegId,eDeviceType,iDriverId FROM register_driver WHERE iDriverId IN (".$driver_id_auto.") AND tLastOnline > '$str_date' AND vAvailability='Available'";
            $result = $obj->MySQLSelect($sql);
//print_r($result);die;
        // echo "Res:count:".count($result);exit;
            if(count($result) == 0 || $driver_id_auto == "" || count($Data) == 0){
          $returnArr['Action'] = "0";
          $returnArr['message'] = "NO_CARS";
                echo json_encode($returnArr);
                //exit;
            }

        if($cashPayment=='true'){
          $tripPaymentMode="Cash";
        }else{
          $tripPaymentMode="Card";
          }

        $where = " iUserId = '$passengerId'";

        $Data_update_passenger['vTripStatus']=$trip_status;

        if(($generalobj->getConfigurations("configurations","PAYMENT_ENABLED")) == 'Yes'){
          $Data_update_passenger['vTripPaymentMode']=$tripPaymentMode;
        }else{
          $Data_update_passenger['vTripPaymentMode']="Cash";
        }

        $Data_update_passenger['iSelectedCarType']=$selectedCarTypeID;
        $Data_update_passenger['tDestinationLatitude']=$DestLatitude;
        $Data_update_passenger['tDestinationLongitude']=$DestLongitude;
        $Data_update_passenger['tDestinationAddress']=$DestAddress;
        $Data_update_passenger['vCouponCode']=$promoCode;
        $Data_update_passenger['fPickUpPrice']=$fPickUpPrice;
        $Data_update_passenger['fNightPrice']=$fNightPrice;
        $Data_update_passenger['eType']=$eType;
        $Data_update_passenger['iPackageTypeId']= $eType == "Deliver"?$iPackageTypeId:'';
        $Data_update_passenger['vReceiverName']=$eType == "Deliver"?$vReceiverName:'';
        $Data_update_passenger['vReceiverMobile']=$eType == "Deliver"?$vReceiverMobile:'';
        $Data_update_passenger['tPickUpIns']=$eType == "Deliver"?$tPickUpIns:'';
        $Data_update_passenger['tDeliveryIns']=$eType == "Deliver"?$tDeliveryIns:'';
        $Data_update_passenger['tPackageDetails']=$eType == "Deliver"?$tPackageDetails:'';
        $Data_update_passenger['iUserPetId']=$iUserPetId;

        $id = $obj->MySQLQueryPerform("register_user",$Data_update_passenger,'update',$where);

        $ENABLE_PUBNUB = $generalobj->getConfigurations("configurations","ENABLE_PUBNUB");
        $PUBNUB_PUBLISH_KEY = $generalobj->getConfigurations("configurations","PUBNUB_PUBLISH_KEY");
        $PUBNUB_SUBSCRIBE_KEY = $generalobj->getConfigurations("configurations","PUBNUB_SUBSCRIBE_KEY");

        if($ENABLE_PUBNUB == "Yes"){

          $pubnub = new Pubnub\Pubnub($PUBNUB_PUBLISH_KEY, $PUBNUB_SUBSCRIBE_KEY);
          $filter_driver_ids = str_replace(' ', '', $driver_id_auto);
          $driverIds_arr = explode(",",$filter_driver_ids);

          $message= stripslashes(preg_replace("/[\n\r]/","",$message));

          $deviceTokens_arr_ios = array();
          $registation_ids_new = array();

          for($i=0;$i<count($driverIds_arr); $i++){


            addToUserRequest($passengerId,$driverIds_arr[$i],$msg_encode,$final_message['MsgCode']);
            addToDriverRequest($driverIds_arr[$i],$passengerId,0,"Timeout");

            /* For PubNub Setting */
            $iAppVersion=get_value("register_driver", 'iAppVersion', "iDriverId",$driverIds_arr[$i],'','true');
            $eDeviceType=get_value("register_driver", 'eDeviceType', "iDriverId",$driverIds_arr[$i],'','true');
            $vDeviceToken=get_value("register_driver", 'iGcmRegId', "iDriverId",$driverIds_arr[$i],'','true');
            /* For PubNub Setting Finished */

            // if($iAppVersion > 1 && $eDeviceType == "Android"){

              $channelName = "CAB_REQUEST_DRIVER_".$driverIds_arr[$i];
              // $info = $pubnub->publish($channelName, $message);
              $info = $pubnub->publish($channelName, $msg_encode );

            // }else{
              // if($eDeviceType == "Android"){
                // array_push($registation_ids_new, $vDeviceToken);
              // }else{
                // array_push($deviceTokens_arr_ios, $vDeviceToken);
              // }
            // }

            if($eDeviceType != "Android"){
              array_push($deviceTokens_arr_ios, $vDeviceToken);
            }

          }

          if(count($registation_ids_new) > 0){
            $Rmessage = array("message" => $message);

             $result = send_notification($registation_ids_new, $Rmessage,0);
          }
          if(count($deviceTokens_arr_ios) > 0){
            sendApplePushNotification(1,$deviceTokens_arr_ios,"",$alertMsg,0);
          }

        }else{
          $deviceTokens_arr_ios = array();
          $registation_ids_new = array();

          foreach ($result as $item) {
            if($item['eDeviceType'] == "Android"){
              array_push($registation_ids_new, $item['iGcmRegId']);
            }else{
              array_push($deviceTokens_arr_ios, $item['iGcmRegId']);
            }

            addToUserRequest($passengerId,$item['iDriverId'],$msg_encode,$final_message['MsgCode']);
            addToDriverRequest($item['iDriverId'],$passengerId,0,"Timeout");
          }

          if(count($registation_ids_new) > 0){
            // $Rmessage = array("message" => $message);
            $Rmessage = array("message" => $msg_encode);

             $result = send_notification($registation_ids_new, $Rmessage,0);

          }
          if(count($deviceTokens_arr_ios) > 0){
            // sendApplePushNotification(1,$deviceTokens_arr_ios,$message,$alertMsg,1);
            sendApplePushNotification(1,$deviceTokens_arr_ios,$msg_encode,$alertMsg,0);
          }
        }

        $returnArr['Action'] = "1";
            echo json_encode($returnArr);

      /* //testing function*/

			header("location:cab_booking.php?success=1&vassign=$edit"); exit;
		}else{
			$error = 1;
			$var_msg = $langage_lbl['LBL_ERROR_OCCURED'];
		}
		//$msg = "Booking Has Been Added Successfully.";
		header("location:cab_booking.php?success=1&vassign=$edit"); exit;
	//}
   // include_once("go_booking.php");
}else {
	header("location:cab_booking.php?success=1&vassign=$edit"); exit;
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title>Admin | Add New Booking </title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <?
        include_once('global_files.php');
        ?>
        <!-- On OFF switch -->
        <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
        <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
        <!-- Google Map Js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
	<script type='text/javascript' src='../assets/map/gmaps.js'></script>
    </head>
    <!-- END  HEAD-->
    <!-- BEGIN BODY-->
    <body class="padTop53 " >

        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <?
            include_once('header.php');
            include_once('left_menu.php');
            ?>
            <!--PAGE CONTENT -->
            <input type="hidden" name="distance" id="distance" value="<?php echo $_POST['distance']; ?>">
            <input type="hidden" name="duration" id="duration" value="<?php echo $_POST['duration']; ?>">
            <input type="hidden" name="from" id="from" value="<?php echo $_POST['from']; ?>">
            <input type="hidden" name="to" id="to" value="<?php echo $_POST['to']; ?>">
            <input type="hidden" name="from_lat_long" id="from_lat_long" value="<?php echo $_POST['from_lat_long']; ?>" >
            <input type="hidden" name="to_lat_long" id="to_lat_long" value="<?php echo $_POST['to_lat_long']; ?>" >
            <input type="hidden" value="1" id="location_found" name="location_found">
            <div id="content">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Continue Booking</h2>
                        </div>
                    </div>
                    <hr />
                    <div class="body-div">
                        <div class="form-group">
                            <? if ($success == 1) {?>
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">�</button>
                                <?php
                                if ($ksuccess == "1") {
                                    ?>
                                    Record Insert Successfully.
                                <?php } else {
                                    ?>
                                    Record Updated Successfully.
                                <?php } ?>

                            </div><br/>
                            <? } ?>

                            <? if ($success == 2) {?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">�</button>
                                "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                            </div><br/>
                            <? } ?>
                            <div class="col-lg-5">
                                <h3 class="title_set">Send Request to Drivers</h3>
                                <form name="all_request_form" action="javascript:void(0);" id="all_request_form" method="post" >
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="submit" class="save btn-info padding_set" id="send_to_all" value="Send Request to All">
                                    </div>
                                </div>
                                </form>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>OR</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <a class="save btn-info padding_set" id="send_to_specific">Send Request to Specific one</a>
                                    </div>
                                </div>

                                <form name="specific_request_form" action="javascript:void(0);" id="all_request_form" method="post" >
                                <?php if(!empty($Data)) { ?>
                                <div class="row show_specific">
                                    <div class="col-lg-12">
                                    <?php for($ji=0;$ji<count($Data);$ji++){ ?>
                                    <input type="radio" name="set_driver" value="">&nbsp;&nbsp;<?php echo $Data[$ji]['vName'].' '.$Data[$ji]['vLastName']; ?><br>
                                    <?php } ?>
                                    </div>
                                </div>
                                <div class="row show_specific">
                                    <div class="col-lg-12">
                                        <input type="submit" class="btn btn-success" value="Send" >
                                    </div>
                                </div>
                                </form>
                                <?php }else { ?>
                                <div class="row show_specific">
                                    <div class="col-lg-12">
                                        <h5>No Drivers Found.</h5>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>OR</h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <a class="save btn-info padding_set" id="send_to_others">Send Request to Other area's</a>
                                    </div>
                                </div>
                                <form name="other_request_form" action="javascript:void(0);" id="all_request_form" method="post" >
                                <?php if(!empty($Data)) { ?>
                                <div class="row show_others">
                                    <div class="col-lg-12">
                                    <?php for($ji=0;$ji<count($Data);$ji++){ ?>
                                    <input type="radio" name="other_driver" value="">&nbsp;&nbsp;<?php echo $Data[$ji]['vName'].' '.$Data[$ji]['vLastName']; ?><br>
                                    <?php } ?>
                                    </div>
                                </div>
                                <div class="row show_others">
                                    <div class="col-lg-12">
                                        <input type="submit" class="btn btn-success" value="Send" >
                                    </div>
                                </div>
                                </form>
                                <?php }else { ?>
                                <div class="row show_others">
                                    <div class="col-lg-12">
                                        <h5>No Drivers Found.</h5>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-lg-7">
                                    <div class="gmap-div"><div id="map-canvas" class="gmap3"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--END PAGE CONTENT -->
        </div>
        <!--END MAIN WRAPPER -->


        <?
        include_once('footer.php');
        ?>

        <script>
            $('.show_specific').hide();
            $('.show_others').hide();
            $('#send_to_specific').click(function(){
               $('.show_specific').slideToggle();
               $('.show_others').slideUp();
            });

            $('#send_to_others').click(function(){
               $('.show_others').slideToggle();
               $('.show_specific').slideUp();
            });
        </script>
        <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>

        <?php
function getaddress($lat,$lng)
{
   $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=true';
   $json = @file_get_contents($url);
   $data=json_decode($json);
   $status = $data->status;
   if($status=="OK")
   {
     return $data->results[0]->formatted_address;
   }
   else
   {
     return "Address Not Found";
   }
}

for($i=0;$i<count($Data);$i++){
   $time = time();
   $last_online_time = strtotime($Data[$i]['tLastOnline']);
   $time_difference = $time-$last_online_time;
   if($time_difference <= 300 && $Data[$i]['vAvailability'] == "Available"){
      $Data[$i]['vAvailability'] = "Available";
   }else{
      $Data1[$i]['vAvailability'] = "Not Available";
   }
   $Data[$i]['vServiceLoc'] = getaddress($Data[$i]['vLatitude'],$Data[$i]['vLongitude']);
}
#echo "<pre>";print_r($db_records);exit;
#echo "<pre>"; print_r($db_records);echo "</pre>";
$locations = array();

#marker Add
foreach ($Data as $key => $value) {
  if($value['vAvailability'] == "Available"){
      $locations[] = array(
              'google_map' => array(
                      'lat' => $value['vLatitude'],
                      'lng' => $value['vLongitude'],
              ),
              'location_address' => $value['vServiceLoc'],
              'location_name'    => $value['FULLNAME'],
              'location_online_status'    => $value['vAvailability'],
      );
  }
}
?>

<?php
/* Set Default Map Area Using First Location */
$map_area_lat = isset( $locations[0]['google_map']['lat'] ) ? $locations[0]['google_map']['lat'] : '';
$map_area_lng = isset( $locations[0]['google_map']['lng'] ) ? $locations[0]['google_map']['lng'] : '';
?>
<script type="text/javascript" src="js/gmap3.js"></script>
<script>


        $(function(){
        var from = $('#from').val();
        var to = $('#to').val();
        var waypts = [];
        if (from != '') {
                    //alert("in from "+from);
                    $("#map-canvas").gmap3({
                        getlatlng: {
                            address: from,
                            callback: function (results) {
                                console.log(results[0]);
                                $("#from_lat_long").val(results[0].geometry.location);
                            }
                        }
                    });
                }
                if (to != '') {
                    $("#map-canvas").gmap3({
                        getlatlng: {
                            address: to,
                            callback: function (results) {
                                $("#to_lat_long").val(results[0].geometry.location);
                            }
                        }
                    });
                }

                $("#map-canvas").gmap3({
                    getroute: {
                        options: {
                            origin: from,
                            destination: to,
                            waypoints: waypts,
                            travelMode: google.maps.DirectionsTravelMode.DRIVING
                        },
                        callback: function (results, status) {
                            chk_route = status;
                            if (!results)
                                return;
                            $(this).gmap3({
                                map: {
                                    options: {
                                        zoom: 8,
                                        //       center: [51.511214, -0.119824]
                                        center: [58.0000, 20.0000]
                                    }
                                },
                                directionsrenderer: {
                                    options: {
                                        directions: results
                                    }
                                }
                            });
                        }
                    }
                });

                $("#map-canvas").gmap3({
                    getdistance: {
                        options: {
                            origins: from,
                            destinations: to,
                            travelMode: google.maps.TravelMode.DRIVING
                        },
                        callback: function (results, status) {
                            var html = "";
                            if (results) {
                                for (var i = 0; i < results.rows.length; i++) {
                                    var elements = results.rows[i].elements;
                                    for (var j = 0; j < elements.length; j++) {
                                        switch (elements[j].status) {
                                            case "OK":
                                                html += elements[j].distance.text + " (" + elements[j].duration.text + ")<br />";
                                                document.getElementById("distance").value = elements[j].distance.text;
                                                document.getElementById("duration").value = elements[j].duration.text;
                                                document.getElementById("location_found").value = 1;
                                                break;
                                            case "NOT_FOUND":
                                                document.getElementById("location_found").value = 0;
                                                break;
                                            case "ZERO_RESULTS":
                                                document.getElementById("location_found").value = 0;
                                                break;
                                        }
                                    }
                                }
                            } else {
                                html = "error";
                            }
                            $("#results").html(html);
                        }
                    }
                });
            });
</script>


    </body>
    <!-- END BODY-->
</html>
